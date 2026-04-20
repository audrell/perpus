<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Traits\HasRoles;


class LoanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            // Ambil data dengan DataTables (AJAX)
            $query = Loan::with('member')->latest();

            // Filter berdasarkan role
            if ($user->hasRole('user')) {
                // User hanya lihat pinjaman mereka sendiri
                $query->where('user_id', $user->id);
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                // Tambah kolom nomor urut
                ->addColumn('nomor', function () {
                    static $counter = 0;
                    return ++$counter;
                })
                // Tampilkan nama member
                ->addColumn('member_name', fn(Loan $loan) => $loan->member->name ?? '-')
                // Format tanggal pinjam
                ->addColumn('loaned_at', fn(Loan $loan) => $loan->loaned_at->format('d/m/Y'))
                // Format tenggat
                ->addColumn('due_date', fn(Loan $loan) => $loan->due_date->format('d/m/Y'))
                // Status dengan warna badge
                ->addColumn('status', function (Loan $loan) {
                    $statusBadge = '';
                    if ($loan->status === 'BORROWED') {
                        $isLate = Carbon::today()->gt($loan->due_date);
                        $statusBadge = $isLate ? '<span class="badge badge-danger">TERLAMBAT</span>' : '<span class="badge badge-warning">DIPINJAM</span>';
                    } else {
                        $statusBadge = '<span class="badge badge-success">DIKEMBALIKAN</span>';
                    }

                    // Tambah badge approval status
                    if ($loan->approval_status === 'PENDING') {
                        $statusBadge .= '<br><span class="badge badge-warning"
                        style="font-size:.7rem;margin-top:2px;">PENDING</span>';
                    } elseif ($loan->approval_status === 'APPROVED') {
                        $statusBadge .= '<br><span class="badge badge-success"
                        style="font-size:.7rem;margin-top:2px;">APPROVED</span>';
                    } elseif ($loan->approval_status === 'REJECTED') {
                        $statusBadge .= '<br><span class="badge badge-secondary"
                        style="font-size:.7rem;margin-top:2px;">REJECTED</span>';
                    }

                    return $statusBadge;
                })
                // Total denda
                ->addColumn('fine_total', fn(Loan $loan) => $loan->status === 'RETURNED' ? 'Rp ' . number_format($loan->fine_total, 0, ',', '.') : '-')
                // Tombol aksi
                ->addColumn('action', function (Loan $loan) {
                    $user = Auth::user();
                    $btns =
                        '<a href="' .
                        route('loans.show', $loan->id) .
                        '"
                    class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i>
                </a>';


                    // Admin: approve/reject/delete jika PENDING
                    dd($user->getRoleNames());
                    if ($user->hasRole('admin') && $loan->approval_status === 'PENDING') {
                        $btns .=
                            ' <form action="' .
                            route('book-loans.approve', $loan->id) .
                            '"
                        method="POST" style="display:inline;margin-left:3px;">
                        <input type="hidden" name="_token" value="' .
                            csrf_token() .
                            '">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-check"></i>
                        </button>
                    </form>';
                        // Tombol reject & delete...
                    }

                    return $btns;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('auth.loans.index');
    }

    public function approve($id)
    {
        $loan = Loan::with('loanDetails.book')->findOrFail($id);

        DB::beginTransaction();
        try {
            // 1. UPDATE STATUS
            $loan->update(['approval_status' => 'APPROVED']);

            // 2. PERULANGAN KURANGI STOK
            foreach ($loan->loanDetails as $item) {
                if ($item->book) {
                    $item->book->decrement('quantity_available');
                }
            }

            DB::commit();
            return redirect()->route('loans.index')->with('success', 'Peminjaman disetujui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('loans.index')
                ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $loan = Loan::with('loanItems.book')->findOrFail($id);

        DB::beginTransaction();

        try {
            $loan->update([
                'approval_status' => 'REJECTED',
            ]);

            foreach ($loan->loanItems as $item) {
                if ($item->book) {
                    $item->book->increment('quantity_available');
                }
            }

            DB::commit();

            return response()->json([
                'success' => 'Peminjaman #' . $loan->loan_code . ' telah ditolak dan stok buku dikembalikan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Gagal menolak peminjaman: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        $loan = Loan::with(['member', 'loanItems.book'])->findOrFail($id);

        return view('auth.loans.modals.show', compact('loan'))->render();
    }

    public function returnBook($id)
    {
        $loan = Loan::with('loanItems.book')->findOrFail($id);

        if ($loan->approval_status !== 'APPROVED') {
            return response()->json(['message' => 'Hanya buku APPROVED yang bisa dikembalikan'], 400);
        }

        DB::beginTransaction();

        try {
            $loan->update([
                'approval_status' => 'RETURNED', // Tes pakai kata yang sudah pasti ada
            ]);

            foreach ($loan->loanItems as $item) {
                if ($item->book) {
                    $item->book->increment('quantity_available');
                }
            }

            DB::commit();

            return response()->json([
                'success' => 'Buku berhasil dikembalikan dan stok diperbarui!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Gagal: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_ids' => 'required|array', // Harus pilih minimal 1 buku
        ]);

        // 2. Mulai Transaksi Database
        DB::beginTransaction();

        $generatedCode = Loan::generateLoanCode();

        try {
            // 3. Simpan ke Tabel Loans (Header)
            $loan = Loan::create([
                'loan_code' => $generatedCode, // otomatis jadi LN-0001-dst
                'member_id' => $request->member_id,
                'user_id' => Auth::id(), // mmengambil ID user yang lagi login
                'loaned_at' => now(),
                'due_date' => now()->addDays(7), // contoh: pinjam 7 hari
                'status' => 'BORROWED',
                'approval_status' => 'PENDING',
            ]);

            // 4. Simpan ke Tabel LoanDetails & Update Stok Buku
            foreach ($request->book_ids as $bookId) {
                // Simpan detail
                LoanDetail::create([
                    'loan_id' => $loan->id,
                    'book_id' => $bookId,
                ]);

                // Kurangi stok available di tabel books
                $book = Book::find($bookId);
                if ($book->quantity_available > 0) {
                    //$book->decrement('quantity_available');
                } else {
                    throw new \Exception("Stok buku {$book->title} habis!");
                }
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Peminjaman berhasil dibuat: ' . $generatedCode);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $members = \App\Models\Member::all();
        // Hanya ambil buku yang stoknya masih ada
        $books = \App\Models\Book::where('quantity_available', '>', 0)->get();

        return view('auth.loans.modals.create', compact('members', 'books'));
    }
}

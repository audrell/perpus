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

class LoanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filter = $request->get('status_filter', 'active');

            // Eager load relasi member supaya tidak berat (N+1 query)
            $query = Loan::with(['member']);

            // Logika Filter
            if ($filter == 'active') {
                $query->whereIn('approval_status', ['PENDING', 'APPROVED']);
            } elseif ($filter == 'returned') {
                $query->where('approval_status', 'RETURNED');
            } elseif ($filter == 'rejected') {
                $query->where('approval_status', 'REJECTED');
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('member_name', function ($row) {
                    return $row->member ? $row->member->name : '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button class="btn btn-info btn-sm show-btn" data-id="' . $row->id . '"><i class="fas fa-eye"></i></button> ';

                    if ($row->approval_status == 'PENDING') {
                        $btn .= '<button class="btn btn-success btn-sm approve-btn" data-id="' . $row->id . '"><i class="fas fa-check"></i></button> ';
                        $btn .= '<button class="btn btn-danger btn-sm reject-btn" data-id="' . $row->id . '"><i class="fas fa-times"></i></button>';
                    } elseif ($row->approval_status == 'APPROVED') {
                        $btn .= '<button class="btn btn-warning btn-sm return-btn" data-id="' . $row->id . '"><i class="fas fa-undo"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('auth.loans.index');
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['approval_status' => 'APPROVED']);

        return response()->json(['success' => 'Peminjaman berhasil disetujui']);
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
                'returned_at' => now(),
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
                    $book->decrement('quantity_available');
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

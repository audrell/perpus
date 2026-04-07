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
            $loans = \App\Models\Loan::with('member')->latest()->get();

            return DataTables::of($loans)
                ->addIndexColumn()
                ->addColumn('member_name', function ($row) {
                    return $row->member->name ?? '-';
                })
                // Di LoanController bagian index()
                ->editColumn('approval_status', function ($row) {
                    $color = [
                        'PENDING' => 'badge-secondary',
                        'APPROVED' => 'badge-success',
                        'REJECTED' => 'badge-danger',
                    ];
                    return '<span class="badge ' . $color[$row->approval_status] . '">' . $row->approval_status . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="#" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('auth.loans.index');
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
                'loan_code' => Loan::generateLoanCode(), // otomatis jadi LN-0001-dst
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

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'approval_status' => 'APPROVED',
            'approved_by' => Auth::id(), // admin yang klik setuju
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Peminjaman telah disetujui.');
    }

    public function returnBook($id)
    {
        $loan = Loan::with('loanItems')->findOrFail($id);

        $dueDate = $loan->due_date;
        $returnDate = now();
        $fine = 0;

        // dendaa
        if ($returnDate->gt($dueDate)) {
            $daysLate = $returnDate->diffInDays($dueDate);
            $fine = $daysLate * 2000;
        }

        DB::transaction(function () use ($loan, $returnDate, $fine) {
            //update status pinjaman
            $loan->update([
                'status' => 'RETURNED',
                'returned_at' => $returnDate,
                'fine_total' => $fine,
            ]);

            //kembalikan stok buku ke raak
            foreach ($loan->loanItems as $item) {
                $item->book->increment('quantity_available');
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Buku berhasil dikembalikan. Denda: Rp ' . number_format($fine));
    }
}

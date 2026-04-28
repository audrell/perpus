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
use App\Models\LoanItem;
use App\Models\SettingApp;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // if ($user->hasRole('member')) {
        //     dd('ini member');
        //     }
        //     else {
        //         dd('admin');
        //     }

        if ($request->ajax()) {
            // Ambil data dengan DataTables (AJAX)
            $query = Loan::with('member')->latest();

            // Filter berdasarkan role
            if ($user->hasRole('member')) {
                // User hanya lihat pinjaman mereka sendiri
                $query->where('user_id', $user->id);
            }

            if ($request->filled('status') && in_array($request->status, ['BORROWED', 'RETURNED', 'REJECTED'])) {
                $query->where('status', $request->status);
            }

            if ($request->filled('approval_status') && in_array($request->approval_status, ['PENDING', 'APPROVED', 'REJECTED'])) {
                $query->where('approval_status', $request->approval_status);
            }

            if ($request->filled('start_date')) {
                $query->whereDate('loaned_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('loaned_at', '<=', $request->end_date);
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
                    if ($loan->status === 'REJECTED') {
                        return '<span class="badge badge-secondary">DITOLAK</span>';
                    }

                    // 2. Cek RETURNED
                    if ($loan->status === 'RETURNED') {
                        return '<span class="badge badge-success">DIKEMBALIKAN</span>';
                    }

                    // 3. Status BORROWED — cek terlambat atau tidak
                    $isLate = Carbon::today()->gt($loan->due_date);
                    $statusBadge = $isLate ? '<span class="badge badge-danger">TERLAMBAT</span>' : '<span class="badge badge-warning">DIPINJAM</span>';

                    // 4. Tambah badge approval
                    if ($loan->approval_status === 'PENDING') {
                        $statusBadge .= '<br><span class="badge badge-info">PENDING</span>';
                    } elseif ($loan->approval_status === 'REJECTED') {
                        $statusBadge = '<span class="badge badge-secondary">DITOLAK</span>';
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

                    if ($user->hasRole('admin') && $loan->approval_status === 'PENDING') {
                        $btns .=
                            '
                <form action="' .
                            route('book-loans.approve', $loan->id) .
                            '" method="POST" style="display:inline;margin-left:3px;">
                <input type="hidden" name="_token" value="' .
                            csrf_token() .
                            '">
                <button type="submit" class="btn btn-success btn-sm">
                <i class="fa fa-check"></i>
                </button>
                </form>';

                        $btns .=
                            '
                <form action="' .
                            route('book-loans.reject', $loan->id) .
                            '" method="POST" style="display:inline;margin-left:3px;">
                <input type="hidden" name="_token" value="' .
                            csrf_token() .
                            '">
                <button type="submit" class="btn btn-danger btn-sm">
                <i class="fa-regular fa-circle-xmark"></i>
                </button>
                </form>';

                        $btns .=
                            '
                <form action="' .
                            route('book-loans.return', $loan->id) .
                            '" method="POST" style="display:inline;margin-left:3px;">
                <input type="hidden" name="_token" value="' .
                            csrf_token() .
                            '">
                <button type="submit" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-arrows-rotate"></i>
                </button>
                </form>';
                    }

                    return $btns;
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('loans.index');
    }

    public function create()
    {
        $user = Auth::user();

        // Hanya role 'user' yang bisa buat pinjaman
        if (!$user->hasRole('member')) {
            abort(403, 'Hanya role user yang bisa meminjam dari katalog.');
        }

        // Cek member terdaftar & aktif
        $member = $user->member;
        if (!$member || !$member->is_active) {
            return redirect()->route('loans.index')->with('error', 'Akun member tidak aktif / belum terdaftar.');
        }

        // Ambil buku yang tersedia
        $books = Book::where('quantity_available', '>', 0)->orderBy('title')->get();

        // Support pre-select book dari query param
        $preselectedBookId = (int) request('book_id', 0);
        if ($preselectedBookId && !$books->pluck('id')->contains($preselectedBookId)) {
            $preselectedBookId = null;
        }

        return view('loans.modals.create', compact('books', 'member', 'preselectedBookId'));
    }

    public function approve(Request $request, Loan $loan)
    {
        $user = auth()->user();

        // Hanya admin
        if (!$user || !$user->hasRole('admin')) {
            abort(403);
        }

        // Hanya bisa approve jika PENDING
        if ($loan->approval_status !== 'PENDING') {
            return back()->with('error', 'Tidak bisa approve. Status tidak PENDING.');
        }

        // Validasi
        // $request->validate([
        //     'approval_note' => 'nullable|string|max:500',
        // ]);

        // // Update
        // $loan->update([
        //     'approval_status' => 'APPROVED',
        //     'approved_by'     => $user->id,
        //     'approved_at'     => now(),
        //     'approval_note'   => $request->input('approval_note'),
        // ]);

        // return back()->with('success', 'Peminjaman disetujui.');

        // Update status & return stok buku

        DB::beginTransaction();
        try {
            // Return stok ke buku
            foreach ($loan->loanItems as $item) {
                $item->book->decrement('quantity_available', $item->qty);
            }

            // Update loan
            $loan->update([
                'approval_status' => 'APPROVED',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'approval_note' => $request->input('approval_note'),
            ]);

            DB::commit();
            return back()->with('success', 'Peminjaman disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, Loan $loan)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        if ($loan->approval_status !== 'PENDING') {
            return back()->with('error', "Loan #{$loan->loan_code} tidak bisa ditolak. Status approval saat ini: {$loan->approval_status}");
        }

        $request->validate([
            'approval_note' => 'nullable|string|max:500',
        ]);

        // Update status & return stok buku
        DB::beginTransaction();
        try {
            // Return stok ke buku
            foreach ($loan->loanItems as $item) {
                $item->book->increment('quantity_available', $item->qty);
            }

            // Update loan
            $loan->update([
                'status' => 'REJECTED',
                'approval_status' => 'REJECTED',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'approval_note' => $request->input('approval_note'),
            ]);

            DB::commit();
            return back()->with('success', 'Peminjaman ditolak. Stok buku dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function returnBook($id)
    {
        $loan = Loan::with('loanItems.book')->findOrFail($id);

        if ($loan->status !== 'BORROWED' || $loan->approval_status !== 'APPROVED') {
            return redirect()
                ->back()
                ->with('error', 'Tidak bisa dikembalikan. Status: ' . $loan->status . ' | Approval: ' . $loan->approval_status);
        }

        DB::beginTransaction();
        try {
            $setting = SettingApp::first();
            $finePerDay = $setting?->fine_per_day ?? 5000;
            $today = Carbon::today();

            $lateDays = $today->gt($loan->due_date) ? $today->diffInDays($loan->due_date) : 0;
            $fineTotal = $lateDays * $finePerDay;

            foreach ($loan->loanItems as $item) {
                if ($item->book) {
                    $item->book->increment('quantity_available', $item->qty);
                }
            }

            $loan->update([
                'status' => 'RETURNED',
                'returned_at' => $today,
                'fine_total' => $fineTotal,
            ]);

            DB::commit();

            $message = 'Buku berhasil dikembalikan.';
            if ($fineTotal > 0) {
                $message .= ' Denda: Rp ' . number_format($fineTotal, 0, ',', '.');
            }

            return redirect()->route('loans.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function returnLoan(Request $request, Loan $loan)
    {
        // Cek ownership & approval
        if ($loan->status !== 'BORROWED' || $loan->approval_status !== 'APPROVED') {
            return back()->with('error', 'Tidak bisa return loan ini.');
        }

        DB::beginTransaction();
        try {
            // Hitung denda
            $setting = SettingApp::first();
            $finePerDay = $setting?->fine_per_day ?? 5000; // Default 5000/hari
            $today = Carbon::today();

            $lateDays = 0;
            if ($today->gt($loan->due_date)) {
                $lateDays = $today->diffInDays($loan->due_date);
            }

            $fineTotalAmount = $lateDays > 0 ? $lateDays * $finePerDay : 0;

            // Return stok buku
            foreach ($loan->loanItems as $item) {
                $item->book->increment('quantity_available', $item->qty);
            }

            // Update loan
            $loan->update([
                'status' => 'RETURNED',
                'returned_at' => $today,
                'fine_total' => $fineTotalAmount,
            ]);

            DB::commit();

            $message = 'Buku berhasil dikembalikan.';
            if ($fineTotalAmount > 0) {
                $message .= ' Denda: Rp ' . number_format($fineTotalAmount, 0, ',', '.');
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $loan = Loan::with(['member', 'loanItems.book'])->findOrFail($id);

        return view('loans.modals.show', compact('loan'))->render();
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('member')) {
            abort(403, 'Hanya role user yang bisa membuat peminjaman.');
        }

        // Validasi input
        $request->validate([
            'due_date' => 'required|date|after_or_equal:today',
            'books' => 'required|array|min:1',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.qty' => 'required|integer|min:1',
        ]);

        $member = $user->member;
        if (!$member) {
            return back()
                ->withInput()
                ->withErrors(['books' => 'Data member tidak ditemukan.']);
        }

        // Mulai transaction
        DB::beginTransaction();

        try {
            // Create Loan
            $loan = Loan::create([
                'loan_code' => Loan::generateLoanCode(),
                'member_id' => $member->id,
                'user_id' => $user->id,
                'loaned_at' => Carbon::today(),
                'due_date' => $request->due_date,
                'status' => 'BORROWED',
                'approval_status' => 'PENDING', // Menunggu approval admin
            ]);

            // Kurangi stok buku & create loan items
            foreach ($request->books as $bookData) {
                $book = Book::lockForUpdate()->find($bookData['book_id']);

                // Validasi stok
                if ($book->quantity_available < $bookData['qty']) {
                    throw new \Exception("Stok {$book->title} tidak cukup");
                }

                // Kurangi stok
                //$book->decrement('quantity_available', $bookData['qty']);

                // Create loan item
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'book_id' => $bookData['book_id'],
                    'qty' => $bookData['qty'],
                ]);
            }

            DB::commit();

            return redirect()->route('loans.show', $loan->id)->with('success', 'Peminjaman berhasil dibuat. Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['books' => $e->getMessage()]);
        }
    }

    public function exportPdf(Request $request)
    {
        $authUser = Auth::user();

        $query = Loan::with('member')->latest();

        // User hanya bisa export data miliknya
        if ($authUser->hasRole('member')) {
            $query->where('user_id', $authUser->id);
        }

        // Optional filter status
        $filterStatus = strtoupper($request->input('status', ''));
        if (in_array($filterStatus, ['BORROWED', 'RETURNED'])) {
            $query->where('status', $filterStatus);
        } else {
            $filterStatus = null;
        }

        // Optional filter approval status
        $filterApprovalStatus = strtoupper($request->input('approval_status', ''));
        if (in_array($filterApprovalStatus, ['PENDING', 'APPROVED', 'REJECTED'])) {
            $query->where('approval_status', $filterApprovalStatus);
        } else {
            $filterApprovalStatus = null;
        }

        // Optional filter tanggal
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!empty($startDate)) {
            $query->whereDate('loaned_at', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('loaned_at', '<=', $endDate);
        }

        $loans = $query->get();
        $setting = SettingApp::first();

        // Generate PDF
        $pdf = Pdf::loadView('loans.pdf', compact('loans', 'setting', 'filterStatus', 'filterApprovalStatus', 'startDate', 'endDate'))->setPaper('a4', 'landscape');

        $filename = 'loans_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}

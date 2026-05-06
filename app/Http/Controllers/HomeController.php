<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
{

    $stats = [
        'users' => User::count(),
        'members' => Member::count(),
        'books' => Book::count(),
        'loans' => Loan::count(),
        'roles' => Role::count(),
        'permissions' => Permission::count(),
    ];

    $recentUsers = User::latest()->take(5)->get();

    // Report bulanan
    $month = $request->get('report_month', now()->format('Y-m'));
    [$year, $monthNum] = explode('-', $month);

    $reportQuery = Loan::with('loanItems')
        ->whereYear('loaned_at', $year)
        ->whereMonth('loaned_at', $monthNum);

    if ($request->filled('report_status')) {
        $reportQuery->where('approval_status', $request->report_status);
    }

    $reportLoans = $reportQuery->latest('loaned_at')->get();

    $totalBooksLoaned = $reportLoans->sum(fn($loan) => $loan->loanItems->sum('qty'));

    $statusCount = [
        'approved' => $reportLoans->where('approval_status', 'approved')->count(),
        'pending'  => $reportLoans->where('approval_status', 'pending')->count(),
        'rejected' => $reportLoans->where('approval_status', 'rejected')->count(),
    ];

    $loanStatusCount = [
        'dikembalikan' => $reportLoans->where('status', 'dikembalikan')->count(),
        'dipinjam'     => $reportLoans->where('status', 'dipinjam')->count(),
        'terlambat'    => $reportLoans->where('status', 'terlambat')->count(),
        'ditolak'      => $reportLoans->where('status', 'ditolak')->count(),
    ];

     $members = Member::all();

    return view('home', compact(
        'stats', 'recentUsers',
        'reportLoans', 'totalBooksLoaned',
        'statusCount', 'loanStatusCount',
        'month'
    ));
}
}

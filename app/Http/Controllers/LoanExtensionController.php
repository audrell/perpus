<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanExtensionController extends Controller
{
    public function create($loanId)
{
    $loan = Loan::with('loanItems.book')->findOrFail($loanId);

    // Validasi ownership
    if ($loan->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    // Cek bisa request extension
    if (!LoanExtension::canRequestExtension($loanId)) {
        return redirect()->route('loans.show', $loanId)
            ->with('error',
                'Tidak dapat mengajukan perpanjangan. Peminjaman sudah
                kadaluarsa atau sudah perpanjangan maksimal.');
    }

    $setting = SettingApp::first();
    $extensionDays = $setting?->extension_days ?? 7;

    return view('loan-extensions.create', compact('loan', 'extensionDays'));
}

public function store(Request $request, $loanId)
{
    $loan = Loan::findOrFail($loanId);

    if ($loan->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    if (!LoanExtension::canRequestExtension($loanId)) {
        return redirect()->route('loans.show', $loanId)
            ->with('error', 'Tidak dapat mengajukan perpanjangan.');
    }

    $validated = $request->validate([
        'extension_days' => 'required|integer|min:1|max:14',
        'reason'         => 'required|string|max:500',
    ]);

    // Hitung due_date baru
    $newDueDate = \Carbon\Carbon::parse($loan->due_date)
        ->addDays((int)$validated['extension_days']);

    // Create extension request
    LoanExtension::create([
        'loan_id'       => $loanId,
        'extension_days' => $validated['extension_days'],
        'new_due_date'  => $newDueDate,
        'reason'        => $validated['reason'],
        'requested_by'  => auth()->id(),
        'status'        => 'PENDING',
    ]);

    return redirect()->route('loans.show', $loanId)
        ->with('success',
            'Permohonan perpanjangan berhasil diajukan.
            Menunggu persetujuan admin.');
}

public function adminIndex()
{
    // List perpanjangan PENDING
    $extensions = LoanExtension::with(['loan.member', 'requestedBy', 'approvedBy'])
        ->where('status', 'PENDING')
        ->latest()
        ->paginate(15);

    // Approved list (untuk history)
    $approved = LoanExtension::with(['loan.member', 'requestedBy', 'approvedBy'])
        ->where('status', 'APPROVED')
        ->latest()
        ->take(5)
        ->get();

    return view('loan-extensions.admin-index',
        compact('extensions', 'approved'));
}

public function approve(Request $request, $extensionId)
{
    $extension = LoanExtension::findOrFail($extensionId);

    if ($extension->status !== 'PENDING') {
        return back()->with('error',
            'Perpanjangan ini sudah diproses.');
    }

    $request->validate([
        'admin_note' => 'nullable|string|max:500',
    ]);

    // Update extension
    $extension->update([
        'status'      => 'APPROVED',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
        'admin_note'  => $request->input('admin_note'),
    ]);

    // Update due_date di loan
    $extension->loan->update([
        'due_date' => $extension->new_due_date,
    ]);

    return back()->with('success',
        'Perpanjangan disetujui. Tenggat peminjaman diperbarui.');
}

public function reject(Request $request, $extensionId)
{
    $extension = LoanExtension::findOrFail($extensionId);

    if ($extension->status !== 'PENDING') {
        return back()->with('error',
            'Perpanjangan ini sudah diproses.');
    }

    $request->validate([
        'admin_note' => 'nullable|string|max:500',
    ]);

    $extension->update([
        'status'      => 'REJECTED',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
        'admin_note'  => $request->input('admin_note'),
    ]);

    return back()->with('success',
        'Perpanjangan ditolak.');
}


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanExtension extends Model
{
    protected $fillable = [
        'loan_id',
        'extension_days',
        'new_due_date',
        'status',
        'reason',
        'admin_note',
        'requested_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'new_due_date' => 'date',
        'approved_at'  => 'datetime',
    ];


    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    /**
     * Validasi apakah peminjaman bisa request perpanjangan
     *
     * Syarat:
     * 1. Status peminjaman harus BORROWED
     * 2. Tidak boleh lebih dari 3 hari setelah due_date (kadaluarsa)
     * 3. Maksimal sudah 2 kali perpanjangan yang disetujui
     */
    public static function canRequestExtension($loanId): bool
    {
        $loan = Loan::find($loanId);

        if (!$loan || $loan->status !== 'BORROWED') {
            return false;
        }

        if (\Carbon\Carbon::today()->diffInDays($loan->due_date) < -3) {
            return false;
        }

        $approvedCount = self::where('loan_id', $loanId)
            ->where('status', 'APPROVED')
            ->count();

        return $approvedCount < 2;  // Maksimal 2 kali perpanjangan
    }
}

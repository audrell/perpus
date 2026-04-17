<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = ['loan_code', 'member_id', 'user_id', 'loaned_id', 'due_date', 'returned_at', 'status', 'fine_total', 'approval_status', 'approved_by', 'approved_at', 'approval_note'];

    protected $casts = [
        'loaned_at' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date',
        'approved_at' => 'datetime',
    ];

    // relasi
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function loanItems()
    {
        return $this->hasMany(LoanDetail::class);
    }

    // buat Kode LN-0001
    public static function generateLoanCode(): string
    {
        $lastCode = self::lockForUpdate()->orderByDesc('id')->value('loan_code');

        $lastNumber = 0;
        if (!empty($lastCode) && preg_match('/^LN-(\d+)$/', $lastCode, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        return 'LN-' . str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
    }
}

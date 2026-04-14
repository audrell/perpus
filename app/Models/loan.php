<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $guarded = [];

    // ubah string tanggal otomatis jadi objek Carbon (biar gampang dihitung dendanya)
    protected $casts = [
        'loaned_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // buat Kode LN-0001
    public static function generateLoanCode()
    {
        $latest = self::latest()->first();
        if (!$latest) return 'LN-0001';

        $string = preg_replace("/[^0-9]/", "", $latest->loan_code);
        return 'LN-' . sprintf('%04d', (int)$string + 1);
    }

    public function loandetails() {
        return $this->hasMany(LoanDetail::class, 'loan_id');
    }


    // relasi
    public function member() { return $this->belongsTo(Member::class); }
    public function user() { return $this->belongsTo(User::class); } // pemilik transaksi
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function loanItems() { return $this->hasMany(LoanDetail::class, 'loan_id'); }
}

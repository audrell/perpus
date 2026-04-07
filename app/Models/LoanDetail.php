<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{

    protected $fillable = ['loan_id', 'book_id', 'is_returned', 'returned_at'];

    public function loan() {
    return $this->belongsTo(Loan::class);
}

public function book() {
    return $this->belongsTo(Book::class);
}
}

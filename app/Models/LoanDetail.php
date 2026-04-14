<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{

    protected $guarded = [];

    public function loan() {
    return $this->belongsTo(Loan::class, 'loan_id');
}

public function book() {
    return $this->belongsTo(Book::class, 'book_id');
}
}

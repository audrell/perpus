<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function up() {
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->enum('type', ['pemasukan', 'pengeluaran']);
        $table->integer('amount');
        $table->string('description');
        $table->timestamps();
    });
}
}

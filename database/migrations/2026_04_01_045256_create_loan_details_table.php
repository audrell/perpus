<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('loan_details', function (Blueprint $table) {
        $table->id();
        // ID Transaksi Peminjaman (Header)
        $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
        // ID Buku yang dipinjam
        $table->foreignId('book_id')->constrained('books')->onDelete('cascade');

        // Status per buku (0: Belum kembali, 1: Sudah kembali)
        $table->boolean('is_returned')->default(false);
        $table->dateTime('returned_at')->nullable();

        $table->timestamps();
    });
}
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_details');
    }
};

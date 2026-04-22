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
    Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->string('loan_code')->unique(); // LN-20240501-001
        $table->foreignId('member_id')->constrained('members'); 
        $table->foreignId('user_id')->constrained('users'); // Petugas/Pemilik Transaksi
        $table->foreignId('approved_by')->nullable()->constrained('users'); // Admin yang approve
        $table->date('loaned_at'); // Tanggal pinjam
        $table->date('due_date');  // Tanggal harus kembali
        $table->date('returned_at')->nullable(); // Tanggal asli kembali
        $table->enum('status', ['BORROWED', 'RETURNED'])->default('BORROWED');
        $table->enum('approval_status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
        $table->integer('fine_total')->default(0); // Total denda
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};

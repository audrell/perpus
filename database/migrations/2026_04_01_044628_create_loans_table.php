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

        // Foreign Keys
        $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users'); // Petugas/Pemilik Transaksi
        $table->foreignId('approved_by')->nullable()->constrained('users'); // Admin yang approve

        // Tanggal
        $table->dateTime('loaned_at'); // Tanggal pinjam
        $table->dateTime('due_date');  // Tanggal harus kembali
        $table->dateTime('returned_at')->nullable(); // Tanggal asli kembali

        // Status (Gunakan Enum agar kaku/aman)
        $table->enum('status', ['BORROWED', 'RETURNED'])->default('BORROWED');
        $table->enum('approval_status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');

        // Tambahan
        $table->dateTime('approved_at')->nullable();
        $table->text('approval_note')->nullable();
        $table->decimal('fine_total', 12, 2)->default(0); // Total denda

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

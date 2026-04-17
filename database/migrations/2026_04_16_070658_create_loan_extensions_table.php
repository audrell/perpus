<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_extensions', function (Blueprint $table) {
            $table->id();
            $table->timestamps('loan_id')->constrained('loans')->onDelete('cascade');
            $table->integer('extension_days')->default(7); // Jumlah hari perpanjangan
            $table->date('new_due_date'); // Tenggat baru jika disetujui
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING'); // Status permohonan
            $table->text('reason')->nullable(); // Alasan perpanjangan
            $table->text('admin_note')->nullable(); // Catatan admin
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade'); // User yang request
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // User yang approve
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_extensions');
    }
};

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
    Schema::table('loans', function (Blueprint $table) {
        if (!Schema::hasColumn('loans', 'approved_by')) {
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
        }
        if (!Schema::hasColumn('loans', 'approved_at')) {
            $table->datetime('approved_at')->nullable()->after('approved_by');
        }
        if (!Schema::hasColumn('loans', 'approval_note')) {
            $table->text('approval_note')->nullable()->after('approved_at');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
};

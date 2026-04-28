<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;  
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('BORROWED', 'RETURNED', 'REJECTED') NOT NULL DEFAULT 'BORROWED'");
}

public function down(): void
{
    DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('BORROWED', 'RETURNED') NOT NULL DEFAULT 'BORROWED'");
}
};

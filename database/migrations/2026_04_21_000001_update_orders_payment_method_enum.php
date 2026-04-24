<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN for enums, use a string column instead
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method VARCHAR(20) NOT NULL DEFAULT 'cod'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash','card','momo') NOT NULL DEFAULT 'cash'");
    }
};

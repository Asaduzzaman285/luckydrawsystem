<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add description column
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('description')->nullable()->after('status');
        });

        // Update ENUM types - SQLite doesn't support changing ENUMs easily, but for MySQL/PostgreSQL:
        // Since we are likely on a local dev environment, we'll use raw SQL if needed, 
        // but Laravel's DB::statement is safer.
        
        // For standard SQL compliance:
        // DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('deposit', 'withdrawal', 'ticket_purchase', 'prize_credit', 'transfer_in', 'transfer_out')");
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};

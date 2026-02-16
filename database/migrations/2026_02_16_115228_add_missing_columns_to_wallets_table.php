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
        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('lifetime_deposit', 10, 2)->default(0)->after('balance');
            $table->decimal('lifetime_withdrawal', 10, 2)->default(0)->after('lifetime_deposit');
            $table->timestamp('last_transaction_at')->nullable()->after('lifetime_withdrawal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            //
        });
    }
};

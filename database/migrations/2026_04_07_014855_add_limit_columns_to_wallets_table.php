<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        Schema::table('wallets', function (Blueprint $col) {
            $col->decimal('lifetime_winnings', 15, 2)->default(0)->after('balance');
            $col->decimal('lifetime_withdrawals', 15, 2)->default(0)->after('lifetime_winnings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $col) {
            $col->dropColumn(['lifetime_winnings', 'lifetime_withdrawals']);
        });
    }
};

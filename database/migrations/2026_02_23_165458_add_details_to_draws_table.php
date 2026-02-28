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
        Schema::table('draws', function (Blueprint $table) {
            $table->enum('selection_type', ['auto', 'manual'])->default('auto')->after('status');
            $table->integer('winning_digit')->nullable()->after('selection_type');
            $table->decimal('prize_pool_total', 15, 2)->nullable()->after('winning_digit');
            $table->decimal('total_sales', 15, 2)->nullable()->after('prize_pool_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('draws', function (Blueprint $table) {
            //
        });
    }
};

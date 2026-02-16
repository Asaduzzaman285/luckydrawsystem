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
        Schema::table('draws', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->decimal('total_pool', 12, 2)->default(0)->after('ticket_price');
            $table->timestamp('winner_selected_at')->nullable()->after('status');
            $table->enum('winner_selection_method', ['system', 'manual'])->nullable()->after('winner_selected_at');
            $table->string('seed_hash', 64)->nullable()->after('winner_selection_method');
            $table->foreignId('created_by')->nullable()->constrained('users')->after('seed_hash');
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

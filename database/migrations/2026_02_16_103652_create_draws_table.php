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
        Schema::create('draws', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('ticket_price', 8, 2);
            $table->integer('max_tickets')->nullable();
            $table->integer('sold_tickets')->default(0);
            $table->enum('status', ['draft', 'scheduled', 'live', 'closed', 'drawing', 'completed', 'cancelled'])->default('draft');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamp('draw_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draws');
    }
};

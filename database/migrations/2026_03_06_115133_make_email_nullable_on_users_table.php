<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            
            // Handle phone unique index carefully using Laravel 11 methods
            $indexes = Schema::getIndexes('users');
            $hasPhoneUnique = collect($indexes)->contains(fn($index) => $index['name'] === 'users_phone_unique');
            
            if (!$hasPhoneUnique) {
                $table->unique('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            
            $indexes = Schema::getIndexes('users');
            $hasPhoneUnique = collect($indexes)->contains(fn($index) => $index['name'] === 'users_phone_unique');
            
            if ($hasPhoneUnique) {
                $table->dropUnique(['phone']);
            }
        });
    }
};

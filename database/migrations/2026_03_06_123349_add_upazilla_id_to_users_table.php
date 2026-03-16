<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'upazilla_id')) {
                $table->unsignedBigInteger('upazilla_id')->nullable()->after('district_id');
                $table->foreign('upazilla_id')->references('id')->on('upazillas')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'upazilla_id')) {
                $table->dropForeign(['upazilla_id']);
                $table->dropColumn('upazilla_id');
            }
        });
    }
};

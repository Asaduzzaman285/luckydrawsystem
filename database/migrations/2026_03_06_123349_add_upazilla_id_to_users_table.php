<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint ) {
            if (!Schema::hasColumn('users', 'upazilla_id')) {
                ->unsignedBigInteger('upazilla_id')->nullable()->after('district_id');
                ->foreign('upazilla_id')->references('id')->on('upazillas')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint ) {
            if (Schema::hasColumn('users', 'upazilla_id')) {
                ->dropForeign(['upazilla_id']);
                ->dropColumn('upazilla_id');
            }
        });
    }
};

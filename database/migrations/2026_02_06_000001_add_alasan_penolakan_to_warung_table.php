<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warung', function (Blueprint $table) {
            if (!Schema::hasColumn('warung', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('warung', function (Blueprint $table) {
            if (Schema::hasColumn('warung', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }
        });
    }
};

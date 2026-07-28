<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warung', function (Blueprint $table) {
            // Menandakan apakah warung ini menerima pesanan/layanan catering atau tidak.
            $table->boolean('menerima_catering')->default(false)->after('harga_max');
        });
    }

    public function down(): void
    {
        Schema::table('warung', function (Blueprint $table) {
            $table->dropColumn('menerima_catering');
        });
    }
};

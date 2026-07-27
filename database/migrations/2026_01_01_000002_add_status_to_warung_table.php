<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warung', function (Blueprint $table) {
            // Data warung yang sudah ada dianggap sudah disetujui (approved)
            // supaya tidak tiba-tiba hilang dari halaman utama.
            // Warung baru yang didaftarkan pemilik akan di-set 'pending' secara manual di controller.
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('approved')
                ->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('warung', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

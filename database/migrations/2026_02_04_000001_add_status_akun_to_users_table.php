<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * status_akun dipakai khusus buat akun pemilik warung yang daftar lewat
     * form "Daftar sebagai Pemilik Warung": akun tersimpan dengan status
     * 'pending' (belum bisa login) sampai admin klik "Verifikasi" di menu
     * Kelola Akun Pemilik. Akun biasa (daftar lewat form register umum)
     * default-nya langsung 'verified' karena tidak perlu ditinjau siapa pun.
     *
     * Sengaja bikin kolom baru, bukan pakai email_verified_at bawaan
     * Laravel -- itu soal konfirmasi kepemilikan email, beda konsep dengan
     * verifikasi admin di sini, dan email_verified_at tidak dipakai/di-enforce
     * di mana pun pada aplikasi ini.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users ADD COLUMN status_akun ENUM('pending', 'verified') NOT NULL DEFAULT 'verified' AFTER role");
    }

    public function down(): void
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('status_akun');
        });
    }
};

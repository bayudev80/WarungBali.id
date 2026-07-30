<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel sederhana untuk mencatat kunjungan halaman secara real,
     * menggantikan angka "Pengunjung/Bulan" yang sebelumnya di-hardcode
     * di tampilan (32.000+ tanpa sumber data).
     *
     * Satu baris = satu kunjungan unik per (session, tanggal), supaya
     * reload berkali-kali dalam sesi yang sama tidak dihitung berulang.
     */
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100);
            $table->date('visited_date');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['session_id', 'visited_date']);
            $table->index('visited_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};

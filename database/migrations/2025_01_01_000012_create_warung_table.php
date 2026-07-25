<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warung', function (Blueprint $table) {
            $table->id('id_warung');
            $table->foreignId('id_user')->nullable()
                ->constrained('users', 'id_user')->nullOnDelete();
            $table->foreignId('id_kategori')->nullable()
                ->constrained('kategori', 'id_kategori')->nullOnDelete();
            $table->foreignId('id_kabupaten')->nullable()
                ->constrained('kabupaten', 'id_kabupaten')->nullOnDelete();
            $table->string('nama_warung');
            $table->string('alamat');
            $table->text('deskripsi')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->unsignedBigInteger('harga_min')->default(0);
            $table->unsignedBigInteger('harga_max')->default(0);
            $table->string('foto')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warung');
    }
};
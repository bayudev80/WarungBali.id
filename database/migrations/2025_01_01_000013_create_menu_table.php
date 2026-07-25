<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->foreignId('id_warung')
                ->constrained('warung', 'id_warung')->cascadeOnDelete();
            $table->string('nama_menu');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('harga')->default(0);
            $table->string('foto_menu')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('review', function (Blueprint $table) {
            // Satu user cuma boleh punya satu ulasan untuk satu warung.
            // Kalau user kirim ulasan lagi ke warung yang sama, ulasan lamanya
            // di-update (lihat ReviewController@store), bukan bikin baris baru.
            $table->unique(['id_user', 'id_warung'], 'review_user_warung_unique');
        });
    }

    public function down(): void
    {
        Schema::table('review', function (Blueprint $table) {
            $table->dropUnique('review_user_warung_unique');
        });
    }
};

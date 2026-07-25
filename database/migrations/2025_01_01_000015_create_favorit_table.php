<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorit', function (Blueprint $table) {
            $table->id('id_favorit');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')->cascadeOnDelete();
            $table->foreignId('id_warung')
                ->constrained('warung', 'id_warung')->cascadeOnDelete();
            $table->unique(['id_user', 'id_warung']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorit');
    }
};
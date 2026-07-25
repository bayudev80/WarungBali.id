<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review', function (Blueprint $table) {
            $table->id('id_review');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')->cascadeOnDelete();
            $table->foreignId('id_warung')
                ->constrained('warung', 'id_warung')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('komentar')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
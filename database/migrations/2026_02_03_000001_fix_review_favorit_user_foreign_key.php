<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Perbaikan bug: hapus pengguna gagal (500 / SQLSTATE 23000) karena
     * foreign key `id_user` di tabel `review` (dan berpotensi `favorit`)
     * ternyata di database TIDAK memakai ON DELETE CASCADE -- beda dengan
     * yang didefinisikan di migration create_review_table.php /
     * create_favorit_table.php (kemungkinan dulu tabelnya sempat dibuat
     * manual lewat SQL/phpMyAdmin, jadi constraint aslinya beda nama &
     * beda perilaku, contoh: `fk_review_user`).
     *
     * Migration ini CARI nama constraint yang benar-benar terpasang di
     * database (apa pun namanya), DROP, lalu pasang ulang dengan
     * ON DELETE CASCADE -- supaya kalau pengguna dihapus, ulasan &
     * favorit miliknya ikut terhapus otomatis, bukan malah menolak hapus.
     */
    public function up(): void
    {
        $this->fixCascade('review', 'id_user', 'users', 'id_user');
        $this->fixCascade('favorit', 'id_user', 'users', 'id_user');
    }

    private function fixCascade(string $table, string $column, string $refTable, string $refColumn): void
    {
        $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->where('REFERENCED_TABLE_NAME', $refTable)
            ->value('CONSTRAINT_NAME');

        // Kalau tidak ketemu constraint sama sekali, berarti kolomnya
        // memang belum punya foreign key -- tidak perlu diapa-apakan.
        if (!$constraint) {
            return;
        }

        // Cek dulu apakah constraint yang ada SUDAH cascade -- kalau
        // sudah, tidak perlu drop & bikin ulang (migration jadi aman
        // dijalankan berkali-kali / idempotent).
        $sudahCascade = DB::table('information_schema.REFERENTIAL_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $constraint)
            ->where('DELETE_RULE', 'CASCADE')
            ->exists();

        if ($sudahCascade) {
            return;
        }

        Schema::table($table, function (Blueprint $t) use ($constraint) {
            $t->dropForeign($constraint);
        });

        Schema::table($table, function (Blueprint $t) use ($column, $refTable, $refColumn) {
            $t->foreign($column)
                ->references($refColumn)->on($refTable)
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Sengaja tidak dikembalikan ke perilaku lama (tanpa cascade) --
        // karena perilaku lama itu sendiri yang jadi sumber bug.
    }
};
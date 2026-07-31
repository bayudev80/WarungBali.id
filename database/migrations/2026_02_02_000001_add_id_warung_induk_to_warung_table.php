<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Sengaja dipisah jadi 2 langkah (bukan digabung dalam satu closure)
        // karena MySQL/MariaDB sering menolak kalau "tambah kolom baru" dan
        // "tambah foreign key yang menunjuk ke tabel yang sama" (self-referencing)
        // dilakukan dalam SATU statement ALTER TABLE -- walau tipe datanya
        // sebenarnya sudah cocok (muncul sebagai error 3780 "incompatible").
        //
        // Setiap langkah juga dicek dulu apakah sudah ada, supaya migration ini
        // AMAN dijalankan ulang (idempotent) kalau percobaan sebelumnya sempat
        // gagal di tengah jalan (misalnya kolom sudah kebuat tapi constraint-nya belum).

        // Langkah 1: pastikan kolomnya ADA dan tipenya BENAR (bigint unsigned,
        // sama seperti id_warung). Kalau kolom ini sudah pernah kebuat dari
        // percobaan migrate sebelumnya yang gagal di tengah jalan, ada
        // kemungkinan tipenya salah/tidak konsisten -- makanya di-drop dulu
        // lalu dibuat ulang, supaya dijamin cocok dengan id_warung sebelum
        // foreign key-nya ditambahkan di langkah 2.
        if (Schema::hasColumn('warung', 'id_warung_induk')) {
            Schema::table('warung', function (Blueprint $table) {
                $table->dropColumn('id_warung_induk');
            });
        }

        Schema::table('warung', function (Blueprint $table) {
            // Kalau diisi, berarti warung ini adalah CABANG dari warung lain
            // (nunjuk ke id_warung milik warung utama/induk). Kalau kosong (null),
            // warung ini berdiri sendiri / berstatus warung utama.
            //
            // PENTING: kolom id_warung di database ini ternyata bertipe `int`
            // biasa (bukan bigint unsigned seperti default Laravel), jadi
            // kolom ini HARUS `integer` juga (bukan unsignedBigInteger) supaya
            // tipenya cocok waktu dibuatkan foreign key di langkah 2.
            $table->integer('id_warung_induk')->nullable()->after('id_warung');
        });

        // Langkah 2: baru tambahkan foreign key constraint-nya -- kalau belum ada.
        // nullOnDelete: kalau warung induknya dihapus, cabang ini tidak ikut
        // terhapus, cuma jadi "berdiri sendiri" lagi (id_warung_induk jadi null).
        $constraintSudahAda = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'warung')
            ->where('CONSTRAINT_NAME', 'warung_id_warung_induk_foreign')
            ->exists();

        if (!$constraintSudahAda) {
            Schema::table('warung', function (Blueprint $table) {
                $table->foreign('id_warung_induk')
                    ->references('id_warung')->on('warung')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('warung', function (Blueprint $table) {
            $table->dropForeign(['id_warung_induk']);
        });

        Schema::table('warung', function (Blueprint $table) {
            $table->dropColumn('id_warung_induk');
        });
    }
};
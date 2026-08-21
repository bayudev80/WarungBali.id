<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        $table  = 'menu';
        $column = 'id_warung';

        $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->where('REFERENCED_TABLE_NAME', 'warung')
            ->value('CONSTRAINT_NAME');

        if ($constraint) {
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
        }

        Schema::table($table, function (Blueprint $t) {
            $t->foreign('id_warung')
                ->references('id_warung')->on('warung')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
      
    }
};
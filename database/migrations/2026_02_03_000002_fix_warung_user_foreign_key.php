<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   
    public function up(): void
    {
        $table  = 'warung';
        $column = 'id_user';

        $tipeKolomUser = DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'users')
            ->where('COLUMN_NAME', 'id_user')
            ->value('COLUMN_TYPE'); 

        DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` {$tipeKolomUser} NULL");

        $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->where('REFERENCED_TABLE_NAME', 'users')
            ->value('CONSTRAINT_NAME');

        if ($constraint) {
            $sudahSetNull = DB::table('information_schema.REFERENTIAL_CONSTRAINTS')
                ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
                ->where('TABLE_NAME', $table)
                ->where('CONSTRAINT_NAME', $constraint)
                ->where('DELETE_RULE', 'SET NULL')
                ->exists();

            if ($sudahSetNull) {
               
                return;
            }

            Schema::table($table, function (Blueprint $t) use ($constraint) {
                $t->dropForeign($constraint);
            });
        }

        
        Schema::table($table, function (Blueprint $t) {
            $t->foreign('id_user')
                ->references('id_user')->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
       
    }
};
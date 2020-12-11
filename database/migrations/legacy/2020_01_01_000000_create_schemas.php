<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateSchemas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            '
                CREATE SCHEMA cadastro;
                CREATE SCHEMA Modules;
                CREATE SCHEMA pmieducar;
                CREATE SCHEMA Portal;
                CREATE SCHEMA relatorio;
                CREATE SCHEMA urbano;
            '
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared(
            '
                DROP SCHEMA cadastro;
                DROP SCHEMA Modules;
                DROP SCHEMA pmieducar;
                DROP SCHEMA Portal;
                DROP SCHEMA relatorio;
                DROP SCHEMA urbano;
            '
        );
    }
}

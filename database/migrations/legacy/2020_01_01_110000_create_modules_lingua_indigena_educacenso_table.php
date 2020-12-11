<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesLinguaIndigenaEducacensoTable extends Migration
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
                SET default_with_oids = false;

                CREATE TABLE Modules.lingua_indigena_educacenso (
                    id integer NOT NULL,
                    lingua character varying(255)
                );

                ALTER TABLE ONLY Modules.lingua_indigena_educacenso
                    ADD CONSTRAINT lingua_indigena_educacenso_pk PRIMARY KEY (id);
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
        Schema::dropIfExists('Modules.lingua_indigena_educacenso');
    }
}

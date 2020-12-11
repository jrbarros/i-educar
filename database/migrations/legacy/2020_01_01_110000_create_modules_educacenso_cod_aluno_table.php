<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesEducacensoCodAlunoTable extends Migration
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

                CREATE TABLE Modules.educacenso_cod_aluno (
                    cod_aluno integer NOT NULL,
                    cod_aluno_inep bigint NOT NULL,
                    nome_inep character varying(255),
                    fonte character varying(255),
                    created_at timestamp without time zone NOT NULL,
                    updated_at timestamp without time zone
                );

                ALTER TABLE ONLY Modules.educacenso_cod_aluno
                    ADD CONSTRAINT educacenso_cod_aluno_pk PRIMARY KEY (cod_aluno, cod_aluno_inep);
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
        Schema::dropIfExists('Modules.educacenso_cod_aluno');
    }
}

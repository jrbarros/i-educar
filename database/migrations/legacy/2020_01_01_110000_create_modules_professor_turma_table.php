<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesProfessorTurmaTable extends Migration
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

                CREATE SEQUENCE Modules.professor_turma_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    MINVALUE 0
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.professor_turma (
                    id integer DEFAULT nextval(\'Modules.professor_turma_id_seq\'::regclass) NOT NULL,
                    ano smallint NOT NULL,
                    instituicao_id integer NOT NULL,
                    turma_id integer NOT NULL,
                    servidor_id integer NOT NULL,
                    funcao_exercida smallint NOT NULL,
                    tipo_vinculo smallint,
                    permite_lancar_faltas_componente integer DEFAULT 0,
                    updated_at timestamp without time zone,
                    turno_id integer
                );

                ALTER TABLE ONLY Modules.professor_turma
                    ADD CONSTRAINT professor_turma_id_pk PRIMARY KEY (id);

                SELECT pg_catalog.setval(\'Modules.professor_turma_id_seq\', 1, false);
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
        Schema::dropIfExists('Modules.professor_turma');

        DB::unprepared('DROP SEQUENCE Modules.professor_turma_id_seq;');
    }
}

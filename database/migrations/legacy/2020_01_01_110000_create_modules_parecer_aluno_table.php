<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesParecerAlunoTable extends Migration
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

                CREATE SEQUENCE Modules.parecer_aluno_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.parecer_aluno (
                    id integer NOT NULL,
                    matricula_id integer NOT NULL,
                    parecer_descritivo smallint NOT NULL
                );

                ALTER SEQUENCE Modules.parecer_aluno_id_seq OWNED BY Modules.parecer_aluno.id;

                ALTER TABLE ONLY Modules.parecer_aluno
                    ADD CONSTRAINT parecer_aluno_pkey PRIMARY KEY (id);

                ALTER TABLE ONLY Modules.parecer_aluno
                    ADD CONSTRAINT modules_parecer_aluno_matricula_id_unique UNIQUE (matricula_id);

                ALTER TABLE ONLY Modules.parecer_aluno ALTER COLUMN id SET DEFAULT nextval(\'Modules.parecer_aluno_id_seq\'::regclass);

                CREATE INDEX idx_parecer_aluno_matricula_id ON Modules.parecer_aluno USING btree (matricula_id);

                SELECT pg_catalog.setval(\'Modules.parecer_aluno_id_seq\', 1, false);
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
        Schema::dropIfExists('Modules.parecer_aluno');
    }
}

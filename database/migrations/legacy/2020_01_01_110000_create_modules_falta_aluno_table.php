<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesFaltaAlunoTable extends Migration
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

                CREATE SEQUENCE Modules.falta_aluno_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.falta_aluno (
                    id integer NOT NULL,
                    matricula_id integer NOT NULL,
                    tipo_falta smallint NOT NULL
                );

                ALTER SEQUENCE Modules.falta_aluno_id_seq OWNED BY Modules.falta_aluno.id;

                ALTER TABLE ONLY Modules.falta_aluno
                    ADD CONSTRAINT falta_aluno_pkey PRIMARY KEY (id);

                ALTER TABLE ONLY Modules.falta_aluno
                    ADD CONSTRAINT modules_falta_aluno_matricula_id_unique UNIQUE (matricula_id);

                ALTER TABLE ONLY Modules.falta_aluno ALTER COLUMN id SET DEFAULT nextval(\'Modules.falta_aluno_id_seq\'::regclass);

                CREATE INDEX idx_falta_aluno_matricula_id ON Modules.falta_aluno USING btree (matricula_id);

                CREATE INDEX idx_falta_aluno_matricula_id_tipo ON Modules.falta_aluno USING btree (matricula_id, tipo_falta);

                SELECT pg_catalog.setval(\'Modules.falta_aluno_id_seq\', 2, true);
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
        Schema::dropIfExists('Modules.falta_aluno');
    }
}

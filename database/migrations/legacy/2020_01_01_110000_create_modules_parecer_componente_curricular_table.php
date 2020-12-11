<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesParecerComponenteCurricularTable extends Migration
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

                CREATE SEQUENCE Modules.parecer_componente_curricular_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.parecer_componente_curricular (
                    id integer NOT NULL,
                    parecer_aluno_id integer NOT NULL,
                    componente_curricular_id integer NOT NULL,
                    parecer text,
                    etapa character varying(2) NOT NULL
                );

                ALTER SEQUENCE Modules.parecer_componente_curricular_id_seq OWNED BY Modules.parecer_componente_curricular.id;

                ALTER TABLE ONLY Modules.parecer_componente_curricular
                    ADD CONSTRAINT parecer_componente_curricular_pkey PRIMARY KEY (parecer_aluno_id, componente_curricular_id, etapa);

                ALTER TABLE ONLY Modules.parecer_componente_curricular ALTER COLUMN id SET DEFAULT nextval(\'Modules.parecer_componente_curricular_id_seq\'::regclass);

                CREATE UNIQUE INDEX alunocomponenteetapa ON Modules.parecer_componente_curricular USING btree (parecer_aluno_id, componente_curricular_id, etapa);

                SELECT pg_catalog.setval(\'Modules.parecer_componente_curricular_id_seq\', 1, false);
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
        Schema::dropIfExists('Modules.parecer_componente_curricular');
    }
}

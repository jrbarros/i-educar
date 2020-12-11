<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesParecerGeralTable extends Migration
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

                CREATE SEQUENCE Modules.parecer_geral_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.parecer_geral (
                    id integer NOT NULL,
                    parecer_aluno_id integer NOT NULL,
                    parecer text,
                    etapa character varying(2) NOT NULL
                );

                ALTER SEQUENCE Modules.parecer_geral_id_seq OWNED BY Modules.parecer_geral.id;

                ALTER TABLE ONLY Modules.parecer_geral
                    ADD CONSTRAINT parecer_geral_pkey PRIMARY KEY (parecer_aluno_id, etapa);

                ALTER TABLE ONLY Modules.parecer_geral ALTER COLUMN id SET DEFAULT nextval(\'Modules.parecer_geral_id_seq\'::regclass);

                CREATE INDEX idx_parecer_geral_parecer_aluno_etp ON Modules.parecer_geral USING btree (parecer_aluno_id, etapa);

                SELECT pg_catalog.setval(\'Modules.parecer_geral_id_seq\', 1, false);
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
        Schema::dropIfExists('Modules.parecer_geral');
    }
}

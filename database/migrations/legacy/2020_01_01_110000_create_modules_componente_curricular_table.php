<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesComponenteCurricularTable extends Migration
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

                CREATE SEQUENCE Modules.componente_curricular_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.componente_curricular (
                    id integer NOT NULL,
                    instituicao_id integer NOT NULL,
                    area_conhecimento_id integer NOT NULL,
                    nome character varying(500) NOT NULL,
                    abreviatura character varying(25) NOT NULL,
                    tipo_base smallint NOT NULL,
                    codigo_educacenso smallint,
                    ordenamento integer DEFAULT 99999,
	                updated_at timestamp NULL DEFAULT now()
                );

                ALTER TABLE ONLY Modules.componente_curricular
                    ADD CONSTRAINT componente_curricular_pkey PRIMARY KEY (id, instituicao_id);

                ALTER SEQUENCE Modules.componente_curricular_id_seq OWNED BY Modules.componente_curricular.id;

                ALTER TABLE ONLY Modules.componente_curricular ALTER COLUMN id SET DEFAULT nextval(\'Modules.componente_curricular_id_seq\'::regclass);

                CREATE INDEX componente_curricular_area_conhecimento_key ON Modules.componente_curricular USING btree (area_conhecimento_id);

                CREATE UNIQUE INDEX componente_curricular_id_key ON Modules.componente_curricular USING btree (id);

                SELECT pg_catalog.setval(\'Modules.componente_curricular_id_seq\', 2, true);
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
        Schema::dropIfExists('Modules.componente_curricular');
    }
}

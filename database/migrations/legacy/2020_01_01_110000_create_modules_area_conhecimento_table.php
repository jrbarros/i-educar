<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesAreaConhecimentoTable extends Migration
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

                CREATE SEQUENCE Modules.area_conhecimento_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.area_conhecimento (
                    id integer NOT NULL,
                    instituicao_id integer NOT NULL,
                    nome character varying(200) NOT NULL,
                    secao character varying(50),
                    ordenamento_ac integer DEFAULT 99999,
	                updated_at timestamp NULL DEFAULT now()
                );

                ALTER SEQUENCE Modules.area_conhecimento_id_seq OWNED BY Modules.area_conhecimento.id;

                ALTER TABLE ONLY Modules.area_conhecimento
                    ADD CONSTRAINT area_conhecimento_pkey PRIMARY KEY (id, instituicao_id);

                ALTER TABLE ONLY Modules.area_conhecimento ALTER COLUMN id SET DEFAULT nextval(\'Modules.area_conhecimento_id_seq\'::regclass);

                CREATE INDEX area_conhecimento_nome_key ON Modules.area_conhecimento USING btree (nome);

                SELECT pg_catalog.setval(\'Modules.area_conhecimento_id_seq\', 2, true);
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
        Schema::dropIfExists('Modules.area_conhecimento');
    }
}

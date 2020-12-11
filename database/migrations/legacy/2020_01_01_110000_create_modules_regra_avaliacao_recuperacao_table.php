<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesRegraAvaliacaoRecuperacaoTable extends Migration
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

                CREATE SEQUENCE Modules.regra_avaliacao_recuperacao_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.regra_avaliacao_recuperacao (
                    id integer DEFAULT nextval(\'Modules.regra_avaliacao_recuperacao_id_seq\'::regclass) NOT NULL,
                    regra_avaliacao_id integer NOT NULL,
                    descricao character varying(25) NOT NULL,
                    etapas_recuperadas character varying(25) NOT NULL,
                    substitui_menor_nota boolean,
                    media numeric(8,4) NOT NULL,
                    nota_maxima numeric(8,4) NOT NULL,
	                updated_at timestamp NULL DEFAULT now()
                );

                ALTER TABLE ONLY Modules.regra_avaliacao_recuperacao
                    ADD CONSTRAINT regra_avaliacao_recuperacao_pkey PRIMARY KEY (id);

                SELECT pg_catalog.setval(\'Modules.regra_avaliacao_recuperacao_id_seq\', 1, false);
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
        Schema::dropIfExists('Modules.regra_avaliacao_recuperacao');

        DB::unprepared('DROP SEQUENCE Modules.regra_avaliacao_recuperacao_id_seq;');
    }
}

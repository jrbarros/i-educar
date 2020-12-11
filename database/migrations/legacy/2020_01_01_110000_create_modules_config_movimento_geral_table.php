<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesConfigMovimentoGeralTable extends Migration
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

                CREATE SEQUENCE Modules.config_movimento_geral_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.config_movimento_geral (
                    id integer NOT NULL,
                    ref_cod_serie integer NOT NULL,
                    coluna integer NOT NULL
                );

                ALTER SEQUENCE Modules.config_movimento_geral_id_seq OWNED BY Modules.config_movimento_geral.id;

                ALTER TABLE ONLY Modules.config_movimento_geral
                    ADD CONSTRAINT cod_config_movimento_geral_pkey PRIMARY KEY (id);

                ALTER TABLE ONLY Modules.config_movimento_geral ALTER COLUMN id SET DEFAULT nextval(\'Modules.config_movimento_geral_id_seq\'::regclass);

                SELECT pg_catalog.setval(\'Modules.config_movimento_geral_id_seq\', 1, false);
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
        Schema::dropIfExists('Modules.config_movimento_geral');
    }
}

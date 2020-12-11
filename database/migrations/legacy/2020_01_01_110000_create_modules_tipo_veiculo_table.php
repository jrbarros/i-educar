<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTipoVeiculoTable extends Migration
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
                SET default_with_oids = true;

                CREATE SEQUENCE Modules.tipo_veiculo_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.tipo_veiculo (
                    cod_tipo_veiculo integer DEFAULT nextval(\'Modules.tipo_veiculo_seq\'::regclass) NOT NULL,
                    descricao character varying(60)
                );

                ALTER TABLE ONLY Modules.tipo_veiculo
                    ADD CONSTRAINT tipo_veiculo_pkey PRIMARY KEY (cod_tipo_veiculo);

                SELECT pg_catalog.setval(\'Modules.tipo_veiculo_seq\', 1, false);
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
        Schema::dropIfExists('Modules.tipo_veiculo');

        DB::unprepared('DROP SEQUENCE Modules.tipo_veiculo_seq;');
    }
}

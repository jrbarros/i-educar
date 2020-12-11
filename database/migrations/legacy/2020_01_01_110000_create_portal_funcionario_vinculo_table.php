<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreatePortalFuncionarioVinculoTable extends Migration
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

                CREATE SEQUENCE Portal.funcionario_vinculo_cod_funcionario_vinculo_seq
                    START WITH 1
                    INCREMENT BY 1
                    MINVALUE 0
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Portal.funcionario_vinculo (
                    cod_funcionario_vinculo integer DEFAULT nextval(\'Portal.funcionario_vinculo_cod_funcionario_vinculo_seq\'::regclass) NOT NULL,
                    nm_vinculo character varying(255) DEFAULT \'\'::character varying NOT NULL,
                    abreviatura character varying(16)
                );

                ALTER TABLE ONLY Portal.funcionario_vinculo
                    ADD CONSTRAINT funcionario_vinculo_pk PRIMARY KEY (cod_funcionario_vinculo);

                SELECT pg_catalog.setval(\'Portal.funcionario_vinculo_cod_funcionario_vinculo_seq\', 7, true);
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
        Schema::dropIfExists('Portal.funcionario_vinculo');

        DB::unprepared('DROP SEQUENCE Portal.funcionario_vinculo_cod_funcionario_vinculo_seq;');
    }
}

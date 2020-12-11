<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreatePortalAcessoTable extends Migration
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

                CREATE SEQUENCE Portal.acesso_cod_acesso_seq
                    START WITH 0
                    INCREMENT BY 1
                    MINVALUE 0
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Portal.acesso (
                    cod_acesso integer DEFAULT nextval(\'Portal.acesso_cod_acesso_seq\'::regclass) NOT NULL,
                    data_hora timestamp without time zone NOT NULL,
                    ip_externo character varying(50) DEFAULT \'\'::character varying NOT NULL,
                    ip_interno character varying(255) DEFAULT \'\'::character varying NOT NULL,
                    cod_pessoa integer DEFAULT 0 NOT NULL,
                    obs text,
                    sucesso boolean DEFAULT true NOT NULL
                );

                ALTER TABLE ONLY Portal.acesso
                    ADD CONSTRAINT acesso_pk PRIMARY KEY (cod_acesso);

                SELECT pg_catalog.setval(\'Portal.acesso_cod_acesso_seq\', 19, true);
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
        Schema::dropIfExists('Portal.acesso');

        DB::unprepared('DROP SEQUENCE Portal.acesso_cod_acesso_seq;');
    }
}

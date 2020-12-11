<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesMotoristaTable extends Migration
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

                CREATE SEQUENCE Modules.motorista_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.motorista (
                    cod_motorista integer DEFAULT nextval(\'Modules.motorista_seq\'::regclass) NOT NULL,
                    ref_idpes integer NOT NULL,
                    cnh character varying(15),
                    tipo_cnh character varying(2),
                    dt_habilitacao date,
                    vencimento_cnh date,
                    ref_cod_empresa_transporte_escolar integer NOT NULL,
                    observacao character varying(255)
                );

                ALTER TABLE ONLY Modules.motorista
                    ADD CONSTRAINT motorista_pkey PRIMARY KEY (cod_motorista);

                SELECT pg_catalog.setval(\'Modules.motorista_seq\', 1, false);
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
        Schema::dropIfExists('Modules.motorista');

        DB::unprepared('DROP SEQUENCE Modules.motorista_seq;');
    }
}

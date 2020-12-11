<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesEmpresaTransporteEscolarTable extends Migration
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

                CREATE SEQUENCE Modules.empresa_transporte_escolar_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.empresa_transporte_escolar (
                    cod_empresa_transporte_escolar integer DEFAULT nextval(\'Modules.empresa_transporte_escolar_seq\'::regclass) NOT NULL,
                    ref_idpes integer NOT NULL,
                    ref_resp_idpes integer NOT NULL,
                    observacao character varying(255)
                );

                ALTER TABLE ONLY Modules.empresa_transporte_escolar
                    ADD CONSTRAINT empresa_transporte_escolar_cod_empresa_transporte_escolar_pkey PRIMARY KEY (cod_empresa_transporte_escolar);

                SELECT pg_catalog.setval(\'Modules.empresa_transporte_escolar_seq\', 1, false);
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
        Schema::dropIfExists('Modules.empresa_transporte_escolar');

        DB::unprepared('DROP SEQUENCE Modules.empresa_transporte_escolar_seq;');
    }
}

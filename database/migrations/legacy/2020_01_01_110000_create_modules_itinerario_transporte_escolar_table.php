<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesItinerarioTransporteEscolarTable extends Migration
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

                CREATE SEQUENCE Modules.itinerario_transporte_escolar_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.itinerario_transporte_escolar (
                    cod_itinerario_transporte_escolar integer DEFAULT nextval(\'Modules.itinerario_transporte_escolar_seq\'::regclass) NOT NULL,
                    ref_cod_rota_transporte_escolar integer NOT NULL,
                    seq integer NOT NULL,
                    ref_cod_ponto_transporte_escolar integer NOT NULL,
                    ref_cod_veiculo integer,
                    hora time without time zone,
                    tipo character(1) NOT NULL
                );

                ALTER TABLE ONLY Modules.itinerario_transporte_escolar
                    ADD CONSTRAINT itinerario_transporte_escolar_cod_itinerario_transporte_escolar PRIMARY KEY (cod_itinerario_transporte_escolar);

                SELECT pg_catalog.setval(\'Modules.itinerario_transporte_escolar_seq\', 1, false);
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
        Schema::dropIfExists('Modules.itinerario_transporte_escolar');

        DB::unprepared('DROP SEQUENCE Modules.itinerario_transporte_escolar_seq;');
    }
}

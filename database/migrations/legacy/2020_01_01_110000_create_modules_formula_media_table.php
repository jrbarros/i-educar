<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesFormulaMediaTable extends Migration
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

                CREATE SEQUENCE Modules.formula_media_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.formula_media (
                    id integer NOT NULL,
                    instituicao_id integer NOT NULL,
                    nome character varying(50) NOT NULL,
                    formula_media character varying(200) NOT NULL,
                    tipo_formula smallint DEFAULT 1,
                    substitui_menor_nota_rc smallint DEFAULT 0 NOT NULL
                );

                ALTER SEQUENCE Modules.formula_media_id_seq OWNED BY Modules.formula_media.id;

                ALTER TABLE ONLY Modules.formula_media
                    ADD CONSTRAINT formula_media_pkey PRIMARY KEY (id, instituicao_id);

                ALTER TABLE ONLY Modules.formula_media ALTER COLUMN id SET DEFAULT nextval(\'Modules.formula_media_id_seq\'::regclass);

                SELECT pg_catalog.setval(\'Modules.formula_media_id_seq\', 2, true);
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
        Schema::dropIfExists('Modules.formula_media');
    }
}

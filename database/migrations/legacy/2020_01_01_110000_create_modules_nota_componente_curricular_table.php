<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesNotaComponenteCurricularTable extends Migration
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

                CREATE SEQUENCE Modules.nota_componente_curricular_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.nota_componente_curricular (
                    id integer NOT NULL,
                    nota_aluno_id integer NOT NULL,
                    componente_curricular_id integer NOT NULL,
                    nota numeric(8,4) DEFAULT 0,
                    nota_arredondada character varying(10) DEFAULT 0,
                    etapa character varying(2) NOT NULL,
                    nota_recuperacao character varying(10),
                    nota_original character varying(10),
                    nota_recuperacao_especifica character varying(10)
                );

                ALTER SEQUENCE Modules.nota_componente_curricular_id_seq OWNED BY Modules.nota_componente_curricular.id;

                ALTER TABLE ONLY Modules.nota_componente_curricular
                    ADD CONSTRAINT nota_componente_curricular_pkey PRIMARY KEY (nota_aluno_id, componente_curricular_id, etapa);

                ALTER TABLE ONLY Modules.nota_componente_curricular ALTER COLUMN id SET DEFAULT nextval(\'Modules.nota_componente_curricular_id_seq\'::regclass);

                CREATE INDEX idx_nota_componente_curricular_etapa ON Modules.nota_componente_curricular USING btree (nota_aluno_id, componente_curricular_id, etapa);

                CREATE INDEX idx_nota_componente_curricular_etp ON Modules.nota_componente_curricular USING btree (componente_curricular_id, etapa);

                CREATE INDEX idx_nota_componente_curricular_id ON Modules.nota_componente_curricular USING btree (componente_curricular_id);

                SELECT pg_catalog.setval(\'Modules.nota_componente_curricular_id_seq\', 1, true);
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
        Schema::dropIfExists('Modules.nota_componente_curricular');
    }
}

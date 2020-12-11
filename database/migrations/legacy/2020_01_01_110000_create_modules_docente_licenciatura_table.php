<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesDocenteLicenciaturaTable extends Migration
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

                CREATE SEQUENCE Modules.docente_licenciatura_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.docente_licenciatura (
                    id integer NOT NULL,
                    servidor_id integer NOT NULL,
                    licenciatura integer NOT NULL,
                    curso_id integer,
                    ano_conclusao integer NOT NULL,
                    ies_id integer,
                    user_id integer NOT NULL,
                    created_at timestamp without time zone NOT NULL,
                    updated_at timestamp without time zone
                );

                ALTER SEQUENCE Modules.docente_licenciatura_id_seq OWNED BY Modules.docente_licenciatura.id;

                ALTER TABLE ONLY Modules.docente_licenciatura
                    ADD CONSTRAINT docente_licenciatura_curso_unique UNIQUE (servidor_id, curso_id, ies_id);

                ALTER TABLE ONLY Modules.docente_licenciatura
                    ADD CONSTRAINT docente_licenciatura_pk PRIMARY KEY (id);

                ALTER TABLE ONLY Modules.docente_licenciatura ALTER COLUMN id SET DEFAULT nextval(\'Modules.docente_licenciatura_id_seq\'::regclass);

                CREATE INDEX docente_licenciatura_ies_idx ON Modules.docente_licenciatura USING btree (ies_id);

                SELECT pg_catalog.setval(\'Modules.docente_licenciatura_id_seq\', 1, false);
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
        Schema::dropIfExists('Modules.docente_licenciatura');
    }
}

<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesEducacensoCursoSuperiorTable extends Migration
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

                CREATE SEQUENCE Modules.educacenso_curso_superior_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.educacenso_curso_superior (
                    id integer NOT NULL,
                    curso_id character varying(100) NOT NULL,
                    nome character varying(255) NOT NULL,
                    classe_id integer NOT NULL,
                    user_id integer NOT NULL,
                    created_at timestamp without time zone NOT NULL,
                    updated_at timestamp without time zone,
                    grau_academico smallint
                );

                ALTER SEQUENCE Modules.educacenso_curso_superior_id_seq OWNED BY Modules.educacenso_curso_superior.id;

                ALTER TABLE ONLY Modules.educacenso_curso_superior
                    ADD CONSTRAINT educacenso_curso_superior_pk PRIMARY KEY (id);

                ALTER TABLE ONLY Modules.educacenso_curso_superior ALTER COLUMN id SET DEFAULT nextval(\'Modules.educacenso_curso_superior_id_seq\'::regclass);

                SELECT pg_catalog.setval(\'Modules.educacenso_curso_superior_id_seq\', 338, true);
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
        Schema::dropIfExists('Modules.educacenso_curso_superior');
    }
}

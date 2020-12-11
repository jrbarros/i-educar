<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateModulesEducacensoIesTable extends Migration
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

                CREATE SEQUENCE Modules.educacenso_ies_id_seq
                    START WITH 1
                    INCREMENT BY 1
                    NO MINVALUE
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE Modules.educacenso_ies (
                    id integer NOT NULL,
                    ies_id integer NOT NULL,
                    nome character varying(255) NOT NULL,
                    dependencia_administrativa_id integer NOT NULL,
                    tipo_instituicao_id integer NOT NULL,
                    uf character(2),
                    user_id integer NOT NULL,
                    created_at timestamp without time zone NOT NULL,
                    updated_at timestamp without time zone
                );

                ALTER SEQUENCE Modules.educacenso_ies_id_seq OWNED BY Modules.educacenso_ies.id;

                ALTER TABLE ONLY Modules.educacenso_ies
                    ADD CONSTRAINT educacenso_ies_pk PRIMARY KEY (id);

                ALTER TABLE ONLY Modules.educacenso_ies ALTER COLUMN id SET DEFAULT nextval(\'Modules.educacenso_ies_id_seq\'::regclass);

                CREATE INDEX idx_educacenso_ies_ies_id ON Modules.educacenso_ies USING btree (ies_id);

                SELECT pg_catalog.setval(\'Modules.educacenso_ies_id_seq\', 6179, true);
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
        Schema::dropIfExists('Modules.educacenso_ies');
    }
}

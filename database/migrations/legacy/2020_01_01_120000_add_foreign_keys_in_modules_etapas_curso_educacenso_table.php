<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysInModulesEtapasCursoEducacensoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Modules.etapas_curso_educacenso', function (Blueprint $table) {
            $table->foreign('etapa_id')
               ->references('id')
               ->on('Modules.etapas_educacenso');

            $table->foreign('curso_id')
               ->references('cod_curso')
               ->on('pmieducar.curso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Modules.etapas_curso_educacenso', function (Blueprint $table) {
            $table->dropForeign(['etapa_id']);
            $table->dropForeign(['curso_id']);
        });
    }
}

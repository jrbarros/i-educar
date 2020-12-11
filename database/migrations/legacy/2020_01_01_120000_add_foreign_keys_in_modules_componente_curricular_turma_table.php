<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysInModulesComponenteCurricularTurmaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Modules.componente_curricular_turma', function (Blueprint $table) {
            $table->foreign('turma_id')
               ->references('cod_turma')
               ->on('pmieducar.turma')
               ->onDelete('cascade');

            $table->foreign('componente_curricular_id')
               ->references('id')
               ->on('Modules.componente_curricular')
               ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Modules.componente_curricular_turma', function (Blueprint $table) {
            $table->dropForeign(['turma_id']);
            $table->dropForeign(['componente_curricular_id']);
        });
    }
}

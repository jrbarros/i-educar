<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysInModulesProfessorTurmaDisciplinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Modules.professor_turma_disciplina', function (Blueprint $table) {
            $table->foreign('professor_turma_id')
               ->references('id')
               ->on('Modules.professor_turma')
               ->onUpdate('restrict')
               ->onDelete('restrict');

            $table->foreign('componente_curricular_id')
               ->references('id')
               ->on('Modules.componente_curricular')
               ->onUpdate('restrict')
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
        Schema::table('Modules.professor_turma_disciplina', function (Blueprint $table) {
            $table->dropForeign(['professor_turma_id']);
            $table->dropForeign(['componente_curricular_id']);
        });
    }
}

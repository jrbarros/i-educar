<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysInModulesUniformeAlunoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Modules.uniforme_aluno', function (Blueprint $table) {
            $table->foreign('ref_cod_aluno')
               ->references('cod_aluno')
               ->on('pmieducar.aluno')
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
        Schema::table('Modules.uniforme_aluno', function (Blueprint $table) {
            $table->dropForeign(['ref_cod_aluno']);
        });
    }
}

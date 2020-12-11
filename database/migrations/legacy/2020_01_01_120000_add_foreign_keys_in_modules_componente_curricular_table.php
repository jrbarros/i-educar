<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysInModulesComponenteCurricularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Modules.componente_curricular', function (Blueprint $table) {
            $table->foreign(['area_conhecimento_id', 'instituicao_id'])
               ->references(['id', 'instituicao_id'])
               ->on('Modules.area_conhecimento')
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
        Schema::table('Modules.componente_curricular', function (Blueprint $table) {
            $table->dropForeign(['area_conhecimento_id', 'instituicao_id']);
        });
    }
}

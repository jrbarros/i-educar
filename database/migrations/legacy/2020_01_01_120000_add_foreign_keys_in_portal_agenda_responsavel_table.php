<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysInPortalAgendaResponsavelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Portal.agenda_responsavel', function (Blueprint $table) {
            $table->foreign('ref_ref_cod_pessoa_fj')
               ->references('ref_cod_pessoa_fj')
               ->on('Portal.funcionario')
               ->onUpdate('restrict')
               ->onDelete('restrict');

            $table->foreign('ref_cod_agenda')
               ->references('cod_agenda')
               ->on('Portal.agenda')
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
        Schema::table('Portal.agenda_responsavel', function (Blueprint $table) {
            $table->dropForeign(['ref_ref_cod_pessoa_fj']);
            $table->dropForeign(['ref_cod_agenda']);
        });
    }
}

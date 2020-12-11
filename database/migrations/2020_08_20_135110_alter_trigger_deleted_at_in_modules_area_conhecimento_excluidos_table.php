<?php

use App\Support\Database\WhenDeleted;
use Illuminate\Database\Migrations\Migration;

class AlterTriggerDeletedAtInModulesAreaConhecimentoExcluidosTable extends Migration
{
    use WhenDeleted;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dropTriggerWhenDeleted('Modules.area_conhecimento');
        $this->whenDeletedMoveTo('Modules.area_conhecimento', 'Modules.area_conhecimento_excluidos', [
            'id',
            'instituicao_id',
            'nome',
            'secao',
            'ordenamento_ac',
            'agrupar_descritores'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTriggerWhenDeleted('Modules.area_conhecimento');
        $this->whenDeletedMoveTo('Modules.area_conhecimento', 'Modules.area_conhecimento_excluidos', [
            'id',
            'instituicao_id',
            'nome',
            'secao',
            'ordenamento_ac'
        ]);
    }
}

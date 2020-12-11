<?php

use App\Support\Database\WhenDeleted;
use Illuminate\Database\Migrations\Migration;

class AddTriggerDeletedAtInModulesRegraAvaliacaoSerieAnoExcluidosTable extends Migration
{
    use WhenDeleted;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->whenDeletedMoveTo('Modules.regra_avaliacao_serie_ano', 'Modules.regra_avaliacao_serie_ano_excluidos', [
            'serie_id',
            'regra_avaliacao_id',
            'regra_avaliacao_diferenciada_id',
            'ano_letivo',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTriggerWhenDeleted('Modules.regra_avaliacao_serie_ano');
    }
}

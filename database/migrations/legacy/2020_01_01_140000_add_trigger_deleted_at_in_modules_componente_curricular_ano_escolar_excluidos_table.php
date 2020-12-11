<?php

use App\Support\Database\WhenDeleted;
use Illuminate\Database\Migrations\Migration;

class AddTriggerDeletedAtInModulesComponenteCurricularAnoEscolarExcluidosTable extends Migration
{
    use WhenDeleted;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->whenDeletedMoveTo('Modules.componente_curricular_ano_escolar', 'Modules.componente_curricular_ano_escolar_excluidos', [
            'componente_curricular_id',
            'ano_escolar_id',
            'carga_horaria',
            'tipo_nota',
            'anos_letivos',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTriggerWhenDeleted('Modules.componente_curricular_ano_escolar');
    }
}

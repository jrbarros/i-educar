<?php

use App\Support\Database\WhenDeleted;
use Illuminate\Database\Migrations\Migration;

class AddTriggerDeletedAtInModulesComponenteCurricularTurmaExcluidosTable extends Migration
{
    use WhenDeleted;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->whenDeletedMoveTo('Modules.componente_curricular_turma', 'Modules.componente_curricular_turma_excluidos', [
            'componente_curricular_id',
            'ano_escolar_id',
            'escola_id',
            'turma_id',
            'carga_horaria',
            'docente_vinculado',
            'etapas_especificas',
            'etapas_utilizadas',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTriggerWhenDeleted('Modules.componente_curricular_turma');
    }
}

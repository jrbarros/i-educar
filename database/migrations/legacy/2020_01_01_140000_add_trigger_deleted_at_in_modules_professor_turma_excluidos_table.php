<?php

use App\Support\Database\WhenDeleted;
use Illuminate\Database\Migrations\Migration;

class AddTriggerDeletedAtInModulesProfessorTurmaExcluidosTable extends Migration
{
    use WhenDeleted;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->whenDeletedMoveTo('Modules.professor_turma', 'Modules.professor_turma_excluidos', [
            'id',
            'ano',
            'instituicao_id',
            'turma_id',
            'servidor_id',
            'funcao_exercida',
            'tipo_vinculo',
            'permite_lancar_faltas_componente',
            'turno_id',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTriggerWhenDeleted('Modules.professor_turma');
    }
}

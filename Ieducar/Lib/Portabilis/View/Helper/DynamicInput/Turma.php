<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Intranet\Source\PmiEducar\EscolaAnoLetivo;
use iEducarLegacy\Lib\App\Model\Finder;
use iEducarLegacy\Lib\Portabilis\Business\Professor;

/**
 * Class Turma
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class Turma extends CoreSelect
{
    protected function inputName()
    {
        return 'ref_cod_turma';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];
        $instituicaoId = $this->getInstituicaoId($options['instituicaoId'] ?? null);
        $escolaId = $this->getEscolaId($options['escolaId'] ?? null);
        $serieId = $this->getSerieId($options['serieId'] ?? null);
        $ano = $this->viewInstance->ano;
        $naoFiltrarAno = $this->viewInstance->nao_filtrar_ano ?? null;

        $userId = $this->getCurrentUserId();
        $isOnlyProfessor = Professor::isOnlyProfessor($instituicaoId, $userId);

        if ($escolaId and $serieId and empty($resources) and $isOnlyProfessor) {
            $resources = collect(Professor::turmasAlocado($instituicaoId, $escolaId, $serieId, $userId))
                ->keyBy('id')
                ->map(function ($turma) {
                    return $turma['nome'] . ' - ' . $turma['ano'];
                })->toArray();
        } elseif ($escolaId && $serieId && empty($resources)) {
            $resources = Finder::getTurmas($escolaId, $serieId);
        }

        // caso no letivo esteja definido para filtrar turmas por ano,
        // somente exibe as turmas do ano letivo.

        if ($escolaId && $ano && !$naoFiltrarAno && $this->turmasPorAno($escolaId, $ano)) {
            foreach ($resources as $id => $nome) {
                $turma = new Turma();
                $turma->cod_turma = $id;
                $turma = $turma->detalhe();

                if ($turma['ano'] != $ano) {
                    unset($resources[$id]);
                }
            }
        }

        return $this->insertOption(null, 'Selecione uma turma', $resources);
    }

    protected function turmasPorAno($escolaId, $ano)
    {
        $anoLetivo = new EscolaAnoLetivo();
        $anoLetivo->ref_cod_escola = $escolaId;
        $anoLetivo->ano = $ano;
        $anoLetivo = $anoLetivo->detalhe();

        return $anoLetivo['turmas_por_ano'] === 1;
    }

    public function turma($options = [])
    {
        parent::select($options);
    }
}

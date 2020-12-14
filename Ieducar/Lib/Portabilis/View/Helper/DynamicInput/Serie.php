<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\App\Model\Finder;
use iEducarLegacy\Lib\Portabilis\Business\Professor;

require_once 'lib/Portabilis/View/Helper/DynamicInput/CoreSelect.php';
require_once 'Portabilis/Business/Professor.php';

class Serie extends CoreSelect
{
    protected function inputName()
    {
        return 'ref_cod_serie';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];
        $instituicaoId = $this->getInstituicaoId($options['instituicaoId'] ?? null);
        $escolaId = $this->getEscolaId($options['escolaId'] ?? null);
        $cursoId = $this->getCursoId($options['cursoId'] ?? null);
        $userId = $this->getCurrentUserId();
        $isOnlyProfessor = Professor::isOnlyProfessor($instituicaoId, $userId);

        if ($isOnlyProfessor && Professor::canLoadSeriesAlocado($instituicaoId)) {
            $resources = Professor::seriesAlocado($instituicaoId, $escolaId, $cursoId, $userId);
        } elseif ($escolaId && $cursoId && empty($resources)) {
            $resources = Finder::getSeries($instituicaoId = null, $escolaId, $cursoId);
        }

        return $this->insertOption(null, 'Selecione uma s&eacute;rie', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'S&eacute;rie']];
    }

    public function serie($options = [])
    {
        parent::select($options);
    }
}

<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\App\Model\Finder;

/**
 * Class EtapaEscola
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class EtapaEscola extends CoreSelect
{
    protected function inputName()
    {
        return 'etapa';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];
        $instituicaoId = $this->getInstituicaoId($options['instituicaoId'] ?? null);
        $escolaId = $this->getEscolaId($options['escolaId'] ?? null);
        $ano = $this->viewInstance->ano;
        $userId = $this->getCurrentUserId();

        if ($escolaId && empty($resources)) {
            $resources = Finder::getEtapasEscola($ano, $escolaId);
        }

        return $this->insertOption(null, 'Selecione uma etapa', $resources);
    }

    public function etapaEscola($options = [])
    {
        parent::select($options);
    }
}

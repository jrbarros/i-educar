<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\App\Model\Finder;

/**
 * Class BibliotecaSituacao
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class BibliotecaSituacao extends CoreSelect
{
    protected function inputName()
    {
        return 'ref_cod_situacao';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];
        $bibliotecaId = $this->getBibliotecaId();

        if ($bibliotecaId and empty($resources)) {
            $resources = Finder::getBibliotecaSituacoes($bibliotecaId);
        }

        return $this->insertOption(null, 'Selecione uma situa&ccedil;&atilde;o', $resources);
    }

    public function bibliotecaSituacao($options = [])
    {
        parent::select($options);
    }
}

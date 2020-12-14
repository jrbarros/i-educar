<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\App\Model\Finder;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\CoreSelect;

/**
 * Class BibliotecaFonte
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class BibliotecaFonte extends CoreSelect
{
    protected function inputName()
    {
        return 'ref_cod_fonte';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];
        $bibliotecaId = $this->getBibliotecaId();

        if ($bibliotecaId and empty($resources)) {
            $resources = Finder::getBibliotecaFontes($bibliotecaId);
        }

        return self::insertOption(null, 'Selecione uma fonte', $resources);
    }

    public function bibliotecaFonte($options = []): void
    {
        parent::select($options);
    }
}

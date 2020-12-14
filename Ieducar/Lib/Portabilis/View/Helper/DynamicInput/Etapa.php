<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

/**
 * Class Etapa
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class Etapa extends CoreSelect
{
    protected function inputName()
    {
        return 'etapa';
    }

    protected function inputOptions($options)
    {
        return $this->insertOption(null, 'Selecione uma etapa', $resources);
    }

    public function etapa($options = [])
    {
        parent::select($options);
    }
}

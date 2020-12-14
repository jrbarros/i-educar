<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

/**
 * Class Rotas
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class Rotas extends CoreSelect
{
    protected function inputName()
    {
        return 'ref_cod_rota_transporte_escolar';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        return $this->insertOption(null, 'Selecione uma rota', $resources);
    }

    public function rotas($options = [])
    {
        parent::select($options);
    }
}

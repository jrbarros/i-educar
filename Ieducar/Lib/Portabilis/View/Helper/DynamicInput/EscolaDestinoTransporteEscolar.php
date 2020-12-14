<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

/**
 * Class EscolaDestinoTransporteEscolar
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class EscolaDestinoTransporteEscolar extends CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        return $this->insertOption(null, 'Todos', $resources);
    }

    public function escolaDestinoTransporteEscolar($options = [])
    {
        parent::select($options);
    }
}

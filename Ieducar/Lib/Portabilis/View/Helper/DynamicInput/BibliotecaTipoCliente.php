<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

require_once 'lib/Portabilis/View/Helper/DynamicInput/CoreSelect.php';

class BibliotecaTipoCliente extends CoreSelect
{
    protected function inputName()
    {
        return 'ref_cod_cliente_tipo';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];
        $bibliotecaId = $this->getBibliotecaId();

        if ($bibliotecaId and empty($resources)) {
            $resources = Finder::getBibliotecaTiposCliente($bibliotecaId);
        }

        return $this->insertOption(null, 'Selecione um tipo de cliente', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Tipo cliente']];
    }

    public function bibliotecaTipoCliente($options = [])
    {
        parent::select($options);
    }
}

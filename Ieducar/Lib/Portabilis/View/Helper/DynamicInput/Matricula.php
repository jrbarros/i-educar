<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

/**
 * Class Matricula
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class Matricula extends CoreSelect
{
    protected function inputName()
    {
        return 'ref_cod_matricula';
    }

    protected function inputOptions($options)
    {
        return $this->insertOption(null, 'Selecione uma matr&iacute;cula', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Matr&iacute;cula']];
    }

    public function matricula($options = [])
    {
        parent::select($options);
    }
}

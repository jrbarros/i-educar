<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

/**
 * Class Setor
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class Setor extends CoreSelect
{
    protected function inputName()
    {
        return 'id_setor';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        return $this->insertOption(null, 'Selecione um setor', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Setor']];
    }

    public function setor($options = [])
    {
        parent::select($options);
    }
}

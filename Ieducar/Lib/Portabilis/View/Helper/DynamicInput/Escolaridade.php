<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use App\Models\LegacySchoolingDegree;

/**
 * Class Escolaridade
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class Escolaridade extends CoreSelect
{
    protected function inputValue($value = null)
    {
        return $this->getEscolaridadesId($value);
    }

    protected function inputName()
    {
        return 'idesco';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        if (empty($resources)) {
            $resources = LegacySchoolingDegree::all()->getKeyValueArray('descricao');
        }

        return $this->insertOption(null, 'Selecione uma Escolaridade', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Escolaridade']];
    }

    public function selectInput($options = [])
    {
        parent::select($options);
    }

    public function escolaridade($options = [])
    {
        $this->selectInput($options);
    }
}

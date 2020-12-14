<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\App\Model\Finder;

/**
 * Class Instituicao
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class Instituicao extends CoreSelect
{
    protected function inputValue($value = null)
    {
        return $this->getInstituicaoId($value);
    }

    protected function inputName()
    {
        return 'ref_cod_instituicao';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        if (empty($resources)) {
            $resources = Finder::getInstituicoes();
        }

        return $this->insertOption(null, 'Selecione uma institui&ccedil;&atilde;o', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Institui&ccedil;&atilde;o']];
    }

    public function selectInput($options = [])
    {
        parent::select($options);
    }

    public function instituicao($options = [])
    {
        $this->selectInput($options);
    }
}

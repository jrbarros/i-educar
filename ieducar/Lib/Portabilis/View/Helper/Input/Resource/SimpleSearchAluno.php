<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchAluno
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchAluno extends SimpleSearch
{
    public function simpleSearchAluno($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'aluno',
            'apiController' => 'Aluno',
            'apiResource' => 'aluno-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        $this->simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o código ou nome do aluno';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchAluno.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}

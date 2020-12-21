<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchMatricula
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchMatricula extends SimpleSearch
{
    public function simpleSearchMatricula($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'Matricula',
            'apiController' => 'Matricula',
            'apiResource' => 'Matricula-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        $this->simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o nome do aluno, código ou código da matrícula';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchMatricula.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}

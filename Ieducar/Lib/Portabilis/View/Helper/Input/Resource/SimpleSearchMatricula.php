<?php

require_once 'lib/Portabilis/View/Helper/Input/SimpleSearch.php';

class Portabilis_View_Helper_Input_Resource_SimpleSearchMatricula extends Portabilis_View_Helper_Input_SimpleSearch
{
    public function simpleSearchMatricula($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'Matricula',
            'apiController' => 'Matricula',
            'apiResource' => 'Matricula-search'
        ];

        $options = $this->mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o nome do aluno, código ou código da matrícula';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchMatricula.js';
        Portabilis_View_Helper_Application::loadJavascript($this->viewInstance, $jsFile);
    }
}

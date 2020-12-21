<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

/**
 * Class MultipleSearchCursoAluno
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class MultipleSearchCursoAluno extends MultipleSearch
{
    protected function getOptions($resources)
    {
        return $this->insertOption(null, '', $resources);
    }

    public function multipleSearchCursoAluno($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'cursoaluno',
            'apiController' => 'CursoAluno',
            'apiResource' => 'cursoaluno-search'
        ];

        $options = $this->mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        parent::multipleSearch($options['objectName'], $attrName, $options);
    }

    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Portabilis_String_Utils::camelize($options['objectName']) . 'Options';

        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione os cursos do aluno');
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }

    protected function loadAssets()
    {
        Application::loadChosenLib($this->viewInstance);
        $jsFile = '/modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearch.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
        $jsFile = '/modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/MultipleSearchCursoaluno.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}

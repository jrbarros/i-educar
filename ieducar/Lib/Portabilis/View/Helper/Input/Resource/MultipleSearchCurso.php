<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

/**
 * Class MultipleSearchCurso
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class MultipleSearchCurso extends MultipleSearch
{
    protected function getOptions($resources)
    {
        return $this->insertOption(null, '', $resources);
    }

    public function multipleSearchCurso($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'curso',
            'max_selected_options' => 3
        ];

        $options = $this->mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $options['options']['resources'] ?? [];
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        parent::multipleSearch($options['objectName'], $attrName, $options);
    }

    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Utils::camelize($options['objectName']) . 'Options';

        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione os cursos');
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }

    protected function loadAssets()
    {
        Application::loadChosenLib($this->viewInstance);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearch.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/MultipleSearchCurso.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}

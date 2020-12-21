<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

/**
 * Class MultipleSearchEscola
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class MultipleSearchEscola extends MultipleSearch
{
    /**
     * @param $resources
     * @return array
     */
    protected function getOptions($resources)
    {
        return self::insertOption(null, '', $resources);
    }

    /**
     * @param $attrName
     * @param array $options
     */
    public function multipleSearchEscola($attrName, $options = [])
    {
        $defaultOptions = ['objectName' => 'escola', 'max_selected_options' => 3];

        $options = self::mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        $this->multipleSearch($options['objectName'], $attrName, $options);
    }

    /**
     * @param $options
     */
    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Utils::camelize($options['objectName']) . 'Options';
        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione as escolas');
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }

    protected function loadAssets(): void
    {
        Application::loadChosenLib($this->viewInstance);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearch.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/MultipleSearchEscola.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}

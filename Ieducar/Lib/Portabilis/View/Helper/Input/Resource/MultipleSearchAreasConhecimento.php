<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

require_once 'lib/Portabilis/View/Helper/Input/MultipleSearch.php';
require_once 'lib/Portabilis/Utils/Database.php';
require_once 'lib/Portabilis/Text/AppDateUtils.php';

class MultipleSearchAreasConhecimento extends MultipleSearch
{
    protected function getOptions($resources)
    {
        return $this->insertOption(null, '', $resources);
    }

    public function multipleSearchAreasConhecimento($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'areaconhecimento',
            'apiController' => 'AreaConhecimento',
            'apiResource' => 'areaconhecimento-search'
        ];

        $options = $this->mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        parent::multipleSearch($options['objectName'], $attrName, $options);
    }

    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Utils::camelize($options['objectName']) . 'Options';

        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione as áreas de conhecimento');
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }

    protected function loadAssets()
    {
        Application::loadChosenLib($this->viewInstance);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearch.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/MultipleSearchAreaconhecimento.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}

<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;
use iEducarLegacy\Lib\Utils\SafeJson;

require_once 'lib/Portabilis/View/Helper/Input/MultipleSearch.php';
require_once 'lib/Portabilis/Utils/Database.php';
require_once 'lib/Portabilis/Text/AppDateUtils.php';
require_once 'lib/Utils/SafeJson.php';

class MultipleSearchCustom extends MultipleSearch
{
    public function multipleSearchCustom($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'custom',
            'apiController' => 'custom',
            'apiResource' => 'custom-search',
            'type' => 'multiple'
        ];

        $options = self::mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->insertOption(null, '', $options['options']['options']['all_values']);

        $this->placeholderJs($options);

        parent::multipleSearch($options['objectName'], $attrName, $options);
    }

    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Utils::camelize($options['objectName']) . 'Options';
        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione');
        ";

        $json = SafeJson::encode($options['options']['options']['values']);

        $js .= 'arrayOptions.push({element : $j("#' . $options['objectName'] . '"),values : ' . $json . '})';

        Application::embedJavascript($this->viewInstance, $js, $afterReady = false);
    }

    protected function loadAssets()
    {
        Application::loadChosenLib($this->viewInstance);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearch.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/MultipleSearchCustom.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}

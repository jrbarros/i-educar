<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

require_once 'lib/Portabilis/View/Helper/Input/CoreSelect.php';

class CoreSelect extends Portabilis_View_Helper_Input_CoreSelect
{
    protected function loadCoreAssets()
    {
        parent::loadCoreAssets();

        $dependencies = ['/Modules/DynamicInput/Assets/Javascripts/DynamicInput.js'];

        Application::loadJavascript($this->viewInstance, $dependencies);
    }
}

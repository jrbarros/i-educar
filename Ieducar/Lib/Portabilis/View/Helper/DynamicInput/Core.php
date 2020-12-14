<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

require_once 'lib/Portabilis/View/Helper/Input/Core.php';

class Core extends Portabilis_View_Helper_Input_Core
{
    protected function loadCoreAssets()
    {
        parent::loadCoreAssets();

        $dependencies = ['/Modules/DynamicInput/Assets/Javascripts/DynamicInput.js'];

        Portabilis_View_Helper_Application::loadJavascript($this->viewInstance, $dependencies);
    }
}

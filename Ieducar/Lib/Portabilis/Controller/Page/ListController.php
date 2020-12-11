<?php

require_once 'Core/Controller/Page/CoreControllerPageListController.php';
require_once 'lib/Portabilis/View/Helper/Application.php';
require_once 'lib/Portabilis/View/Helper/Inputs.php';

class Portabilis_Controller_Page_ListController extends Core_Controller_Page_ListController
{
    protected $backwardCompatibility = false;

    public function __construct()
    {
        $this->rodape = '';
        $this->largura = '100%';

        $this->loadAssets();
        parent::__construct();
    }

    protected function loadResourceAssets($dispatcher)
    {
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $controllerName = ucwords($dispatcher->getControllerName());
        $actionName = ucwords($dispatcher->getActionName());

        $style = "/Modules/$controllerName/Assets/Stylesheets/$actionName.css";
        $script = "/Modules/$controllerName/Assets/Javascripts/$actionName.js";

        if (file_exists($rootPath . $style)) {
            Portabilis_View_Helper_Application::loadStylesheet($this, $style);
        }

        if (file_exists($rootPath . $script)) {
            Portabilis_View_Helper_Application::loadJavascript($this, $script);
        }
    }

    protected function loadAssets()
    {
        Portabilis_View_Helper_Application::loadJQueryFormLib($this);

        $styles = [
            '/Modules/Portabilis/Assets/Stylesheets/Frontend.css',
            '/Modules/Portabilis/Assets/Stylesheets/Frontend/Process.css'
        ];

        Portabilis_View_Helper_Application::loadStylesheet($this, $styles);

        $scripts = [
            '/Modules/Portabilis/Assets/Javascripts/ClientApi.js',
            '/Modules/Portabilis/Assets/Javascripts/Validator.js',
            '/Modules/Portabilis/Assets/Javascripts/Utils.js'
        ];

        if (!$this->backwardCompatibility) {
            $scripts[] = '/Modules/Portabilis/Assets/Javascripts/Frontend/Process.js';
        }

        Portabilis_View_Helper_Application::loadJavascript($this, $scripts);
    }
}

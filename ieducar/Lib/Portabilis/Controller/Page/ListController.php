<?php

namespace iEducarLegacy\Lib\Portabilis\Controller\Page;

use iEducarLegacy\Lib\Core\Controller\Page\CoreControllerPageListController;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;

/**
 * Class ListController
 * @package iEducarLegacy\Lib\Portabilis\Controller\Page
 */
class ListController extends CoreControllerPageListController
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
            Application::loadStylesheet($this, $style);
        }

        if (file_exists($rootPath . $script)) {
            Application::loadJavascript($this, $script);
        }
    }

    protected function loadAssets()
    {
        Application::loadJQueryFormLib($this);

        $styles = [
            '/Modules/Portabilis/Assets/Stylesheets/Frontend.css',
            '/Modules/Portabilis/Assets/Stylesheets/Frontend/Process.css'
        ];

        Application::loadStylesheet($this, $styles);

        $scripts = [
            '/Modules/Portabilis/Assets/Javascripts/ClientApi.js',
            '/Modules/Portabilis/Assets/Javascripts/Validator.js',
            '/Modules/Portabilis/Assets/Javascripts/Utils.js'
        ];

        if (!$this->backwardCompatibility) {
            $scripts[] = '/Modules/Portabilis/Assets/Javascripts/Frontend/Process.js';
        }

        Application::loadJavascript($this, $scripts);
    }
}

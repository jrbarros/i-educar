<?php

require_once 'CoreExt/Controller/Dispatcher/CoreControllerPageAbstract.php';
require_once 'CoreExt/Controller/Dispatcher/Strategy/CoreExtControllerInterface.php';

class CoreExt_Controller_Dispatcher_Strategy_PageStrategy extends CoreExt_Controller_Dispatcher_Abstract implements CoreExt_Controller_Dispatcher_Strategy_Interface
{
    /**
     * Instância de CoreExtControllerInterface.
     *
     * @var CoreExtControllerInterface
     */
    protected $_controller = null;

    /**
     * Construtor.
     *
     * @see CoreExt_Controller_Strategy_Interface#__construct($controller)
     */
    public function __construct(CoreExtControllerInterface $controller)
    {
        $this->setController($controller);
    }

    /**
     * @see CoreExt_Controller_Strategy_Interface#setController($controller)
     */
    public function setController(CoreExtControllerInterface $controller)
    {
        $this->_controller = $controller;

        return $this;
    }

    /**
     * @see CoreExt_Controller_Strategy_Interface#getController()
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * Determina qual page controller irá assumir a requisição.
     *
     * Determina basicamente o caminho no sistema de arquivos baseado nas
     * informações recebidas pela requisição e pelas opções de configuração.
     *
     * É importante ressaltar que uma ação no contexto do Page Controller
     * significa uma instância de CoreExt_Controller_Page_Interface em si.
     *
     * Um controller nesse contexto pode ser pensado como um módulo auto-contido.
     *
     * Exemplo:
     * <code>
     * Um requisição HTTP para a URL:
     * http://www.example.com/notas/listar
     *
     * Com CoreExtControllerInterface configurado da seguinte forma:
     * basepath = /var/www/Ieducar/Modules
     * controller_dir = controllers
     *
     * Iria mapear para o arquivo:
     * /var/www/Ieducar/Modules/notas/controllers/ListarController.php
     * </code>
     *
     * @return bool
     *@throws CoreExtension_Exception_FileNotFoundException
     *
     * @todo   Funções de controle de buffer não funcionam por conta de chamadas
     *         a die() e exit() nas classes clsDetalhe, Cadastro e clsListagem.
     *
     * @global DS Constante para DIRECTORY_SEPARATOR
     *
     * @see    CoreExt_Controller_Strategy_Interface#dispatch()
     *
     */
    public function dispatch()
    {
        if (extension_loaded('newrelic')) {
            newrelic_name_transaction($_SERVER['REDIRECT_URL']);
        }

        $this->setRequest($this->getController()->getRequest());

        $controller = $this->getControllerName();
        $pageController = ucwords($this->getActionName()) . 'Controller';
        $basepath = $this->getController()->getOption('basepath');
        $controllerDir = $this->getController()->getOption('controller_dir');
        $controllerType = $this->getController()->getOption('controller_type');

        $controllerFile = [$basepath, $controller, $controllerDir, $pageController];
        $controllerFile = sprintf('%s.php', implode(DIRECTORY_SEPARATOR, $controllerFile));

        if (!file_exists($controllerFile)) {
            require_once 'CoreExt/Exception/FileNotFoundException.php';
            throw new CoreExtension_Exception_FileNotFoundException('Nenhuma classe CoreExt_Controller_Page_Interface para o controller informado no caminho: "' . $controllerFile . '"');
        }

        require_once $controllerFile;
        $pageController = new $pageController();

        // Injeta as instâncias CoreExt_Dispatcher_Interface, CoreExt_Request_Interface
        // CoreExt_Session no page controller
        $pageController->setDispatcher($this);
        $pageController->setRequest($this->getController()->getRequest());
        $pageController->setSession($this->getController()->getSession());

        ob_start();
        $pageController->generate($pageController);
        $this->getController()->getView()->setContents(ob_get_contents());
        ob_end_clean();

        return true;
    }
}

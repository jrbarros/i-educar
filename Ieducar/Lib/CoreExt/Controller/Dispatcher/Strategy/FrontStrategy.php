<?php

require_once 'CoreExt/Controller/Dispatcher/CoreControllerPageAbstract.php';
require_once 'CoreExt/Controller/Dispatcher/Strategy/CoreExtControllerInterface.php';

class CoreExt_Controller_Dispatcher_Strategy_FrontStrategy extends CoreExt_Controller_Dispatcher_Abstract implements CoreExt_Controller_Dispatcher_Strategy_Interface
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
     * Não implementado.
     *
     * @see CoreExt_Controller_Strategy_Interface#dispatch()
     */
    public function dispatch()
    {
        require_once 'CoreExt/Controller/Dispatcher/CoreExtensionException.php';

        throw new CoreExtension_Controller_Dispatcher_Exception('Método CoreExt_Controller_Strategy_FrontStrategy::dispatch() não implementado.');
    }
}

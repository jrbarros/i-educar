<?php

namespace iEducarLegacy\Lib\CoreExt\Controller;

use iEducarLegacy\Lib\Core\Controller\Page\CoreControllerPageAbstract;

/**
 * Class Front
 * @package iEducarLegacy\Lib\CoreExt\Controller
 */
class Front extends CoreControllerPageAbstract
{
    /**
     * Opções para definição de qual tipo de controller utilizar durante a
     * execução de dispatch().
     *
     * @var int
     */
    const CONTROLLER_FRONT = 1;
    const CONTROLLER_PAGE = 2;

    /**
     * A instância singleton de CoreExtControllerInterface.
     *
     * @var CoreExtControllerInterface|NULL
     */
    protected static $_instance = null;

    /**
     * Opções de configuração geral da classe.
     *
     * @var array
     */
    protected $_options = [
        'basepath' => null,
        'controller_type' => self::CONTROLLER_PAGE,
        'controller_dir' => 'Views'
    ];

    /**
     * Contém os valores padrão da configuração.
     *
     * @var array
     */
    protected $_defaultOptions = [];

    /**
     * Uma instância de View
     *
     * @var View
     */
    protected $_view = null;

    /**
     * Construtor singleton.
     */
    protected function __construct()
    {
        $this->_defaultOptions = $this->getOptions();
    }

    /**
     * Retorna a instância singleton.
     *
     * @return Front
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Recupera os valores de configuração original da instância.
     *
     * @return CoreExtConfigurable Provê interface fluída
     */
    public function resetOptions()
    {
        $this->setOptions($this->_defaultOptions);

        return $this;
    }

    /**
     * Encaminha a execução para o objeto CoreExt_Dispatcher_Interface apropriado.
     *
     * @return CoreExtControllerInterface Provê interface fluída
     *
     * @see CoreExtControllerInterface#dispatch()
     */
    public function dispatch()
    {
        $this->_getControllerStrategy()->dispatch();

        return $this;
    }

    /**
     * Retorna o conteúdo gerado pelo controller.
     *
     * @return string
     */
    public function getViewContents()
    {
        return $this->getView()->getContents();
    }

    /**
     * Setter.
     *
     * @param CoreExt_View_Abstract $view
     *
     * @return CoreExtControllerInterface Provê interface fluída
     */
    public function setView(CoreExt_View_Abstract $view)
    {
        $this->_view = $view;

        return $this;
    }

    /**
     * Getter para uma instância de View.
     *
     * Instância via lazy initialization uma instância de View caso
     * nenhuma seja explicitamente atribuída a instância atual.
     *
     * @return View
     */
    public function getView()
    {
        if (is_null($this->_view)) {
            require_once 'CoreExt/CoreView.php';
            $this->setView(new CoreExt_View());
        }

        return $this->_view;
    }

    /**
     * Getter para uma instância de DispatcherInterface.
     *
     * Instância via lazy initialization uma instância de
     * CoreExt_Controller_Dispatcher caso nenhuma seja explicitamente
     * atribuída a instância atual.
     *
     * @return DispatcherInterface
     */
    public function getDispatcher()
    {
        if (is_null($this->_dispatcher)) {
            $this->setDispatcher($this->_getControllerStrategy());
        }

        return $this->_dispatcher;
    }

    /**
     * Getter para a estratégia de controller, definida em runtime.
     *
     * @return CoreExt_Controller_Strategy
     */
    protected function _getControllerStrategy()
    {
        switch ($this->getOption('controller_type')) {
            case 1:
                require_once 'CoreExt/Controller/Dispatcher/Strategy/FrontStrategy.php';
                $strategy = 'FrontStrategy';
                break;

            case 2:
                require_once 'CoreExt/Controller/Dispatcher/Strategy/PageStrategy.php';
                $strategy = 'PageStrategy';
                break;
        }

        return new $strategy($this);
    }
}

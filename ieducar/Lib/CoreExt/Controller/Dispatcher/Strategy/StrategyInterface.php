<?php

namespace iEducarLegacy\Lib\CoreExt\Controller\Dispatcher\Strategy;

use iEducarLegacy\Lib\CoreExt\Controller\CoreExtControllerInterface;

/**
 * Interface StrategyInterface
 * @package iEducarLegacy\Lib\CoreExt\Controller\Dispatcher\Strategy
 */
interface StrategyInterface
{
    /**
     * Construtor.
     *
     * @param CoreExtControllerInterface $controller
     */
    public function __construct(CoreExtControllerInterface $controller);

    /**
     * Setter para a instância de CoreExtControllerInterface.
     *
     * @param CoreExtControllerInterface $controller
     *
     * @return CoreExt_Controller_Strategy_Interface Provê interface fluída
     */
    public function setController(CoreExtControllerInterface $controller);

    /**
     * Getter.
     *
     * @return CoreExtControllerInterface
     */
    public function getController();

    /**
     * Realiza o dispatch da requisição, encaminhando o controle da execução ao
     * controller adequado.
     *
     * @return bool
     *
     * @throws Exception
     */
    public function dispatch();
}

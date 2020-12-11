<?php

interface CoreExt_Controller_Dispatcher_Strategy_Interface
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

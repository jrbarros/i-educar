<?php

namespace iEducarLegacy\Lib\CoreExt\Controller\Dispatcher;

/**
 * Interface DispatcherInterface
 * @package iEducarLegacy\Lib\CoreExt\Controller\Dispatcher
 */
interface DispatcherInterface
{
    /**
     * Setter.
     *
     * @param CoreExt_Controller_Request_Interface $request
     *
     * @return DispatcherInterface Provê interface fluída
     */
    public function setRequest(CoreExt_Controller_Request_Interface $request);

    /**
     * Getter.
     *
     * @return CoreExt_Controller_Request_Interface
     */
    public function getRequest();

    /**
     * Retorna uma string correspondendo a parte de controller de uma URL.
     *
     * @return string|NULL
     */
    public function getControllerName();

    /**
     * Retorna uma string correspondendo a parte de action de uma URL.
     *
     * @return string|NULL
     */
    public function getActionName();
}

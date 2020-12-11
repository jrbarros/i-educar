<?php

namespace iEducarLegacy\Lib\CoreExt\Controller;


/**
 * Interface CoreExtControllerInterface
 * @package iEducarLegacy\Lib\CoreExt\Controller
 */
interface CoreExtControllerInterface extends CoreExt_Configurable
{
    /**
     * Despacha o controle da execução para uma instância de
     * CoreExtControllerInterface.
     */
    public function dispatch();
}

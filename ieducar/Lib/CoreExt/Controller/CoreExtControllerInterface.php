<?php

namespace iEducarLegacy\Lib\CoreExt\Controller;

use iEducarLegacy\Lib\CoreExt\CoreExtConfigurable;

/**
 * Interface CoreExtControllerInterface
 * @package iEducarLegacy\Lib\CoreExt\Controller
 */
interface CoreExtControllerInterface extends CoreExtConfigurable
{
    /**
     * Despacha o controle da execução para uma instância de
     * CoreExtControllerInterface.
     */
    public function dispatch();
}

<?php

namespace iEducarLegacy\Lib\Core\Controller\Page;

/**
 * Interface CoreControllerPageValidatableInterface
 * @package iEducarLegacy\Lib\Core\Controller\Page
 */
interface CoreControllerPageValidatableInterface
{
    /**
     * Retorna um array com objetos CoreExt_Validate.
     *
     * @return array
     */
    public function getValidators();
}

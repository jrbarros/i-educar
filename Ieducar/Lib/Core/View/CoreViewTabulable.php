<?php

namespace iEducarLegacy\Lib\Core\View;

/**
 * Interface CoreViewTabulable
 * @package iEducarLegacy\Lib\Core\View
 */
interface CoreViewTabulable
{
    /**
     * Retorna um array associativo no formato nome do campo => label.
     *
     * @return array
     */
    public function getTableMap();
}

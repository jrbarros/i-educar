<?php

namespace iEducarLegacy\Lib\CoreExt;

/**
 * Interface CoreExtConfigurable
 * @package iEducarLegacy\Lib\CoreExt
 */
interface CoreExtConfigurable
{
    /**
     * Setter.
     *
     * @param array $options
     *
     * @return CoreExtConfigurable Provê interface fluída
     */
    public function setOptions(array $options = []);

    /**
     * Getter.
     *
     * @return array
     */
    public function getOptions();
}

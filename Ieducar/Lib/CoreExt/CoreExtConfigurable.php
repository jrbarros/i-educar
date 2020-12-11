<?php

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

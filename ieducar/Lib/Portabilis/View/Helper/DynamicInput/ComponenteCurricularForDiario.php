<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

/**
 * Class ComponenteCurricularForDiario
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class ComponenteCurricularForDiario extends ComponenteCurricular
{
    /**
     * @param array $options
     */
    public function componenteCurricularForDiario($options = []): void
    {
        $this->componenteCurricular($options);
    }
}

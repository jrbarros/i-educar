<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input;

/**
 * Class Hidden
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input
 */
class Hidden extends Core
{
    public function hidden($attrName, $options = [])
    {
        $defaultOptions = ['options' => [], 'objectName' => ''];
        $options = $this->mergeOptions($options, $defaultOptions);
        $spacer = !empty($options['objectName']) && !empty($attrName) ? '_' : '';

        $defaultInputOptions = [
            'id' => $options['objectName'] . $spacer . $attrName,
            'value' => ''
        ];

        $inputOptions = $this->mergeOptions($options['options'], $defaultInputOptions);

        call_user_func_array([$this->viewInstance, 'campoOculto'], $inputOptions);
    }
}

<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input;

use iEducarLegacy\Lib\Portabilis\View\Helper\Application;

require_once 'lib/Portabilis/View/Helper/Input/Core.php';

class Checkbox extends Core
{
    public function checkbox($attrName, $options = [])
    {
        $defaultOptions = ['options' => [], 'objectName' => ''];
        $options = self::mergeOptions($options, $defaultOptions);

        $spacer = !empty($options['objectName']) && !empty($attrName) ? '_' : '';

        $defaultInputOptions = [
            'id' => $options['objectName'] . $spacer . $attrName,
            'label' => ucwords($attrName),
            'value' => '',
            'label_hint' => '',
            'inline' => false,
            'script' => 'fixupCheckboxValue(this)',
            'disabled' => false
        ];

        $inputOptions = self::mergeOptions($options['options'], $defaultInputOptions);

        // fixup para enviar um valor, junto ao param do checkbox.
        $js = '
            var fixupCheckboxValue = function(input) {
                var $this = $j(input);
                $this.val($this.is(\':checked\') ? \'on\' : \'\');
            }
        ';

        Application::embedJavascript($this->viewInstance, $js, $afterReady = false);
        call_user_func_array([$this->viewInstance, 'campoCheck'], $inputOptions);
    }
}

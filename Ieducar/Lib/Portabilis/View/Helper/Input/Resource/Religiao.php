<?php

require_once 'lib/Portabilis/View/Helper/Input/CoreSelect.php';

class Portabilis_View_Helper_Input_Resource_Religiao extends Portabilis_View_Helper_Input_CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        if (empty($options['resources'])) {
            $resources = new Religiao();
            $resources = $resources->lista(null, null, null, null, null, null, null, null, 1);
            $resources = Utils::setAsIdValue($resources, 'cod_religiao', 'nm_religiao');
        }

        return $this->insertOption(null, Utils::toLatin1('Religião'), $resources);
    }

    public function religiao($options = [])
    {
        parent::select($options);
    }
}

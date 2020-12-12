<?php

require_once 'lib/Portabilis/View/Helper/DynamicInput/CoreSelect.php';
require_once 'lib/Portabilis/Utils/Database.php';

class Portabilis_View_Helper_DynamicInput_Vinculo extends Portabilis_View_Helper_DynamicInput_CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        $sql = 'select cod_funcionario_vinculo, nm_vinculo from Portal.funcionario_vinculo';

        $resources = Database::fetchPreparedQuery($sql);
        $resources = Utils::setAsIdValue($resources, 'cod_funcionario_vinculo', 'nm_vinculo');

        return $this->insertOption(null, 'Selecione', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Vínculo']];
    }

    public function vinculo($options = [])
    {
        parent::select($options);
    }
}

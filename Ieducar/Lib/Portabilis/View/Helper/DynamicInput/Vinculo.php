<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;

/**
 * Class Vinculo
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class Vinculo extends CoreSelect
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

<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Intranet\Source\PmiEducar\AlunoBeneficio;
use iEducarLegacy\Lib\Portabilis\Collection\Utils as Collection;
use iEducarLegacy\Lib\Portabilis\String\Utils as Text;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\CoreSelect;

/**
 * Class Beneficio
 *
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class Beneficio extends CoreSelect
{
    /**
     * @param $options
     * @return array
     */
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        if (empty($resources)) {
            $resources = new AlunoBeneficio();
            $resources = $resources->lista(null, null, null, null, null, null, null, null, null, 1);
            $resources = Collection::setAsIdValue($resources, 'cod_aluno_beneficio', 'nm_beneficio');
        }

        return self::insertOption(null, Text::toLatin1('Benefício'), $resources);
    }

    /**
     * @param array $options
     */
    public function beneficio($options = [])
    {
        $this->select($options);
    }
}

<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\Portabilis\Object\Utils;

/**
 * Class BibliotecaTipoExemplar
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class BibliotecaTipoExemplar extends CoreSelect
{
    protected function inputName()
    {
        return 'ref_cod_exemplar_tipo';
    }

    protected function inputOptions($options)
    {
        $bibliotecaId = $this->getBibliotecaId($bibliotecaId);
        $resources = $options['resources'];

        if (empty($resources) && $bibliotecaId) {
            $columns = ['cod_exemplar_tipo', 'nm_tipo'];
            $where = ['ref_cod_biblioteca' => $bibliotecaId, 'ativo' => '1'];
            $orderBy = ['nm_tipo' => 'ASC'];

            $resources = $this->getDataMapperFor('biblioteca', 'tipoExemplar')->findAll(
                $columns,
                $where,
                $orderBy,
                $addColumnIdIfNotSet = false
            );

            $resources = Utils::asIdValue($resources, 'cod_exemplar_tipo', 'nm_tipo');
        }

        return $this->insertOption(null, 'Selecione um tipo de exemplar', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Tipo exemplar']];
    }

    public function bibliotecaTipoExemplar($options = [])
    {
        parent::select($options);
    }
}

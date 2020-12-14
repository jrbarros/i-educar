<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchCursoSuperior
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchCursoSuperior extends SimpleSearch
{
    protected function resourceValue($id)
    {
        if ($id) {
            $sql = '
                select curso_id || \' - \' || nome || \' / \' || coalesce(
                (
                    case grau_academico
                        when 1 then \'Tecnológico\'
                        when 2 then \'Licenciatura\'
                        when 3 then \'Bacharelado\'
                        when 4 then \'Sequencial\'
                    end
                ), \'\') as nome
                from Modules.educacenso_curso_superior
                where id = $1
            ';

            $options = ['params' => $id, 'return_only' => 'first-row'];
            $curso_superior = Database::fetchPreparedQuery($sql, $options);
            $nome = $curso_superior['nome'];

            return Utils::toLatin1($nome, ['transform' => true, 'escape' => false]);
        }
    }

    public function simpleSearchCursoSuperior($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'cursosuperior',
            'apiController' => 'CursoSuperior',
            'apiResource' => 'cursosuperior-search',
            'showIdOnValue' => false
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o código ou nome do curso';
    }
}

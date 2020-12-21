<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchPessoa
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchPessoa extends SimpleSearch
{
    protected function resourceValue($id)
    {
        if ($id) {
            $sql = '
                select
                (
                    case
                        when fisica.nome_social not like \'\' then fisica.nome_social || \' - Nome de registro: \' || Pessoa.nome
                        else Pessoa.nome
                    end
                ) as nome
                from
                    cadastro.Pessoa,
                    cadastro.fisica
                where true
                and Pessoa.idpes = $1
                and fisica.idpes = Pessoa.idpes
            ';

            $options = ['params' => $id, 'return_only' => 'first-field'];
            $nome = Database::fetchPreparedQuery($sql, $options);

            return Utils::toLatin1($nome, ['transform' => true, 'escape' => false]);
        }
    }

    public function simpleSearchPessoa($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'Pessoa',
            'apiController' => 'Pessoa',
            'apiResource' => 'Pessoa-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o nome, código, CPF ou RG da Pessoa';
    }
}

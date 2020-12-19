<?php

namespace iEducarLegacy\Modules\Configuracao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class ConfiguracaoMovimentoGeralDataMapper
 * @package iEducarLegacy\Modules\Configuracao\Model
 */
class ConfiguracaoMovimentoGeralDataMapper extends DataMapper
{
    protected $_entityClass = 'ConfiguracaoMovimentoGeral';
    protected $_tableName   = 'config_movimento_geral';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'serie'       => 'ref_cod_serie',
        'coluna'      => 'coluna'
    ];
}

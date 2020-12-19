<?php

namespace iEducarLegacy\Modules\TabelaArredondamento\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class TabelaValorDataMapper
 * @package iEducarLegacy\Modules\TabelaArredondamento\Model
 */
class TabelaValorDataMapper extends DataMapper
{
    protected $_entityClass = 'TabelaValor';
    protected $_tableName = 'tabela_arredondamento_valor';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'tabelaArredondamento' => 'tabela_arredondamento_id',
        'valorMinimo' => 'valor_minimo',
        'valorMaximo' => 'valor_maximo',
        'acao' => 'acao',
        'casaDecimalExata' => 'casa_decimal_exata'
    ];
}

<?php

namespace iEducarLegacy\Modules\FormulaMedia\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class FormulaDataMapper
 */
class FormulaDataMapper extends DataMapper
{
    protected $_entityClass = 'Formula';

    protected $_tableName = 'formula_media';

    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'id' => 'id',
        'instituicao' => 'instituicao_id',
        'nome' => 'nome',
        'formulaMedia' => 'formula_media',
        'tipoFormula' => 'tipo_formula',
        'substituiMenorNotaRc' => 'substitui_menor_nota_rc'
    ];

    protected $_primaryKey = [
        'id' => 'id',
        'instituicao' => 'instituicao_id'
    ];
}

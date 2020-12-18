<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class NotaComponenteDataMapper
 * @package iEducarLegacy\Modules\Avaliacao\Model
 */
class NotaComponenteDataMapper extends DataMapper
{
    protected $_entityClass = 'NotaComponente';
    protected $_tableName = 'nota_componente_curricular';
    protected $_tableSchema = 'Modules';

    protected $_primaryKey = [
        'id' => 'id',
    ];

    protected $_attributeMap = [
        'id' => 'id',
        'notaAluno' => 'nota_aluno_id',
        'componenteCurricular' => 'componente_curricular_id',
        'nota' => 'nota',
        'notaArredondada' => 'nota_arredondada',
        'etapa' => 'etapa',
        'notaRecuperacaoParalela' => 'nota_recuperacao',
        'notaOriginal' => 'nota_original',
        'notaRecuperacaoEspecifica' => 'nota_recuperacao_especifica'

    ];
}

<?php

namespace iEducarLegacy\Modules\Usuario\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class UsuarioDataMapper
 * @package iEducarLegacy\Modules\Usuario\Model
 */
class UsuarioDataMapper extends DataMapper
{
    protected $_entityClass = 'Usuario';

    protected $_tableName   = 'usuario';

    protected $_tableSchema = 'pmieducar';

    protected $_attributeMap = [
        'id' => 'cod_usuario',
        'escolaId' => 'ref_cod_escola',
        'instituicaoId' => 'ref_cod_instituicao',
        'funcionarioCadId' => 'ref_funcionario_cad',
        'funcionarioExcId' => 'ref_funcionario_exc',
        'tipoUsuarioId' => 'ref_cod_tipo_usuario',
        'dataCadastro' => 'data_cadastro',
        'dataExclusao' => 'data_exclusao',
        'ativo' => 'ativo'
    ];

    protected $_primaryKey = [
        'id' => 'cod_usuario'
    ];
}

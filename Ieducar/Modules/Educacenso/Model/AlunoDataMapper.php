<?php

namespace iEducarLegacy\Modules\Educacenso\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * AlunoDataMapper class.
 *
 * @author      Eriksen Costa Paixão <eriksen.paixao_bs@cobra.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     Educacenso
 * @subpackage  Modules
 *
 * @since       Classe disponível desde a versão 1.2.0
 *
 * @version     @@package_version@@
 */
class AlunoDataMapper extends DataMapper
{
    protected $_entityClass = 'Aluno';
    protected $_tableName   = 'educacenso_cod_aluno';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'aluno'      => 'cod_aluno',
        'alunoInep'  => 'cod_aluno_inep',
        'nomeInep'   => 'nome_inep',
        'fonte'      => 'fonte',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at'
    ];

    protected $_primaryKey = [
        'aluno'      => 'cod_aluno',
        'alunoInep'  => 'cod_aluno_inep'
    ];
}

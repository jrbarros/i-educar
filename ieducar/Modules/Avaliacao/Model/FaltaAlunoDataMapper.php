<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * FaltaAlunoDataMapper class.
 *
 * @author      Eriksen Costa Paixão <eriksen.paixao_bs@cobra.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     Avaliacao
 * @subpackage  Modules
 *
 * @since       Classe disponível desde a versão 1.1.0
 *
 * @version     @@package_version@@
 */
class FaltaAlunoDataMapper extends DataMapper
{
    protected $_entityClass = 'FaltaAluno';
    protected $_tableName   = 'falta_aluno';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'id'        => 'id',
        'Matricula' => 'matricula_id',
        'tipoFalta' => 'tipo_falta'
    ];
}

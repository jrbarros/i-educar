<?php

namespace iEducarLegacy\Modules\Educacenso\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * CursoSuperiorDataMapper class.
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
class CursoSuperiorDataMapper extends DataMapper
{
    protected $_entityClass = 'CursoSuperior';
    protected $_tableName   = 'educacenso_curso_superior';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'id'         => 'id',
        'curso'      => 'curso_id',
        'nome'       => 'nome',
        'classe'     => 'classe_id',
        'user'       => 'user_id',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at'
    ];
}

<?php

namespace iEducarLegacy\Modules\Educacenso\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * IesDataMapper class.
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
class IesDataMapper extends DataMapper
{
    protected $_entityClass = 'Ies';
    protected $_tableName   = 'educacenso_ies';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'id'                        => 'id',
        'ies'                       => 'ies_id',
        'nome'                      => 'nome',
        'dependenciaAdministrativa' => 'dependencia_administrativa_id',
        'tipoInstituicao'           => 'tipo_instituicao_id',
        'uf'                        => 'uf',
        'user'                      => 'user_id',
        'created_at'                => 'created_at',
        'updated_at'                => 'updated_at'
    ];
}

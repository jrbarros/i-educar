<?php

namespace iEducarLegacy\Modules\Educacenso\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * EscolaDataMapper class.
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
class EscolaDataMapper extends DataMapper
{
    protected $_entityClass = 'Escola';
    protected $_tableName   = 'educacenso_cod_escola';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'escola'        => 'cod_escola',
        'escolaInep'    => 'cod_escola_inep',
        'nomeInep'      => 'nome_inep',
        'fonte'         => 'fonte',
        'created_at'    => 'created_at',
        'updated_at'    => 'updated_at'
    ];

    protected $_primaryKey = [
        'escola'        => 'cod_escola',
        'escolaInep'    => 'cod_escola_inep'
    ];
}

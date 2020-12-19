<?php

namespace iEducarLegacy\Modules\Educacenso\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * DocenteDataMapper class.
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
class DocenteDataMapper extends DataMapper
{
    protected $_entityClass = 'Docente';
    protected $_tableName   = 'educacenso_cod_docente';
    protected $_tableSchema = 'Modules';

    protected $_primaryKey = [
        'docente'       => 'cod_servidor',
        'docenteInep'   => 'cod_docente_inep'
    ];

    protected $_attributeMap = [
        'docente'       => 'cod_servidor',
        'docenteInep'   => 'cod_docente_inep',
        'nomeInep'      => 'nome_inep',
        'fonte'         => 'fonte',
        'created_at'    => 'created_at',
        'updated_at'    => 'updated_at'
    ];
}

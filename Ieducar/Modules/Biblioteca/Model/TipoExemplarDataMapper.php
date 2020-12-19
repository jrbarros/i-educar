<?php

namespace iEducarLegacy\Modules\Biblioteca\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Usuario_Model_TipoExemplarDataMapper class.
 *
 * @author      Lucas D'Avila <lucasdavila@portabilis.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     Usuario
 * @subpackage  Modules
 *
 * @since       Classe disponível desde a versão 1.1.0
 *
 * @version     @@package_version@@
 */
class TipoExemplarDataMapper extends DataMapper
{
    protected $_entityClass = 'TipoExemplar';
    protected $_tableName   = 'exemplar_tipo';
    protected $_tableSchema = 'pmieducar';

    protected $_attributeMap = [
        'cod_exemplar_tipo'  => 'cod_exemplar_tipo',
        'ref_cod_biblioteca' => 'ref_cod_biblioteca',
        'ref_usuario_exc'    => 'ref_usuario_exc',
        'ref_usuario_cad'    => 'ref_usuario_cad',
        'nm_tipo'            => 'nm_tipo',
        'descricao'          => 'descricao',
        'data_cadastro'      => 'data_cadastro',
        'data_exclusao'      => 'data_exclusao',
        'ativo'              => 'ativo'
    ];

    protected $_notPersistable = [];

    protected $_primaryKey = [
        'cod_exemplar_tipo' => 'cod_exemplar_tipo'
    ];
}

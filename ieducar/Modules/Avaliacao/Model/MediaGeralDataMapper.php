<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * MediaGeralDataMapper class.
 *
 * @author      Gabriel Matos de Souza <gabriel@portabilis.com.br>
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
class MediaGeralDataMapper extends DataMapper
{
    protected $_entityClass = 'MediaGeral';
    protected $_tableName   = 'media_geral';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'notaAluno'         => 'nota_aluno_id',
        'media'             => 'media',
        'mediaArredondada'  => 'media_arredondada',
        'etapa'             => 'etapa'
    ];

    protected $_primaryKey = [
        'notaAluno'   => 'nota_aluno_id',
        'etapa'       => 'etapa'
    ];
}

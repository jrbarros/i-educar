<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

/**
 * ParecerDescritivoComponenteDataMapper class.
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
class ParecerDescritivoComponenteDataMapper extends ParecerDescritivoAbstractDataMapper
{
    protected $_entityClass = 'ParecerDescritivoComponente';
    protected $_tableName   = 'parecer_componente_curricular';

    protected $_attributeMap = [
        'id'                    => 'id',
        'componenteCurricular'  => 'componente_curricular_id',
        'parecer'               => 'parecer',
        'etapa'                 => 'etapa'
    ];

    protected $_primaryKey = [
        'parecerDescritivoAluno' => 'parecer_aluno_id',
        'componenteCurricular'  => 'componente_curricular_id',
        'etapa' => 'etapa'
    ];
}

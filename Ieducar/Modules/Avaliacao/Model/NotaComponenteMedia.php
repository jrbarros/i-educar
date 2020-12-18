<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

use iEducarLegacy\Lib\CoreExt\Entity;
use iEducarLegacy\Lib\CoreExt\Validate\Numeric;
use iEducarLegacy\Lib\CoreExt\Validate\Text;

/**
 * Class NotaComponenteMedia
 * @package iEducarLegacy\Modules\Avaliacao\Model
 */
class NotaComponenteMedia extends Entity
{
    protected $_data = [
        'notaAluno' => null,
        'componenteCurricular' => null,
        'media' => null,
        'mediaArredondada' => null,
        'etapa' => null,
        'situacao' => null,
        'bloqueada' => false,
    ];

    protected $_dataTypes = [
        'media' => 'numeric',
        'bloqueada' => 'boolean',
    ];

    protected $_references = [
        'notaAluno' => [
            'value' => null,
            'class' => 'NotaAlunoDataMapper',
            'file'  => 'Avaliacao/Model/NotaAlunoDataMapper.php'
        ],
        'componenteCurricular' => [
            'value' => null,
            'class' => 'ComponenteCurricular_Model_ComponenteDataMapper',
            'file'  => 'ComponenteCurricular/Model/ComponenteDataMapper.php'
        ]
    ];

    public function __construct($options = [])
    {
        parent::__construct($options);
        unset($this->_data['id']);
    }

    public function getDefaultValidatorCollection()
    {
        return [
            'media' => new Numeric(['min' => 0, 'max' => 10]),
            'mediaArredondada' => new Text(['max' => 5]),
            'etapa' => new Text(['max' => 2])
        ];
    }
}

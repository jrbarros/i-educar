<?php

namespace iEducarLegacy\Modules\Docente\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class Licenciatura
 * @package iEducarLegacy\Modules\Docente\Model
 */
class Licenciatura extends Entity
{
    protected $_data = [
      'servidor'     => null,
      'licenciatura' => null,
      'curso'        => null,
      'anoConclusao' => null,
      'ies'          => null,
      'user'         => null,
      'created_at'   => null,
      'updated_at'   => null
  ];

    protected $_references = [
        'licenciatura' => [
            'value' => null,
            'class' => 'SimNao',
            'file'  => 'App/Model/SimNao.php'
        ],
        'ies' => [
            'value' => null,
            'class' => 'Educacenso_Model_IesDataMapper',
            'file'  => 'Educacenso/Model/IesDataMapper.php'
        ],
        'curso' => [
            'value' => null,
            'class' => 'Educacenso_Model_CursoSuperiorDataMapper',
            'file'  => 'Educacenso/Model/CursoSuperiorDataMapper.php'
        ]
  ];

    public function getDefaultValidatorCollection()
    {
        return [];
    }
}

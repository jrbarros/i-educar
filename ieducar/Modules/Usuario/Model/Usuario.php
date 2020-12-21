<?php

namespace iEducarLegacy\Modules\Usuario\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class Usuario
 * @package iEducarLegacy\Modules\Usuario\Model
 */
class Usuario extends Entity
{
    protected $_data = [
        'id' => null,
        'escolaId' => null,
        'instituicaoId' => null,
        'funcionarioCadId' => null,
        'funcionarioExcId' => null,
        'tipoUsuarioId' => null,
        'dataCadastro' => null,
        'dataExclusao' => null,
        'ativo' => null
    ];

    public function getDataMapper()
    {
        if (is_null($this->_dataMapper)) {

            $this->setDataMapper(new UsuarioDataMapper());
        }

        return parent::getDataMapper();
    }

    public function getDefaultValidatorCollection()
    {
        return [];
    }

    // TODO remover metodo? já que foi usado $_attributeMap id
    protected function _createIdentityField()
    {
        $id = ['id' => null];
        $this->_data = array_merge($id, $this->_data);

        return $this;
    }
}

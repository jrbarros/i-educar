<?php

namespace iEducarLegacy\Modules\Usuario\Model;

use iEducarLegacy\Lib\CoreExt\Entity;
use iEducarLegacy\Lib\CoreExt\Validate\Email;

/**
 * Class Funcionario
 * @package iEducarLegacy\Modules\TransporteEscolar\Views
 */
class Funcionario extends Entity
{
    protected $_data = [
        'Matricula' => null,
        'email' => null,
        'senha' => null,
        'data_troca_senha' => null,
        'status_token' => null
    ];

    protected $_dataTypes = [
        'Matricula' => 'string'
    ];

    protected $_references = [];

    public function getDataMapper()
    {
        if (is_null($this->_dataMapper)) {
            $this->setDataMapper(new FuncionarioDataMapper());
        }

        return parent::getDataMapper();
    }

    public function getDefaultValidatorCollection()
    {
        return [
            'email' => new Email()
        ];
    }

    protected function _createIdentityField()
    {
        $id = ['ref_cod_pessoa_fj' => null];
        $this->_data = array_merge($id, $this->_data);

        return $this;
    }
}

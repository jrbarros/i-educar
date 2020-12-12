<?php

require_once 'CoreExt/Entity.php';
require_once 'App/Model/Finder.php';
require_once 'CoreExt/Validate/Email.php';

class Usuario_Model_Funcionario extends CoreExt_Entity
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
            require_once 'Usuario/Model/FuncionarioDataMapper.php';
            $this->setDataMapper(new Usuario_Model_FuncionarioDataMapper());
        }

        return parent::getDataMapper();
    }

    public function getDefaultValidatorCollection()
    {
        return [
            'email' => new _Validate_Email()
        ];
    }

    protected function _createIdentityField()
    {
        $id = ['ref_cod_pessoa_fj' => null];
        $this->_data = array_merge($id, $this->_data);

        return $this;
    }
}

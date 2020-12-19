<?php

namespace iEducarLegacy\Modules\TransporteEscolar\Views;

use iEducarLegacy\Lib\App\Model\NivelAcesso;
use iEducarLegacy\Lib\Portabilis\Controller\Page\EditController;
use iEducarLegacy\Lib\Portabilis\String\Utils;

/**
 * Class EmpresaController
 * @package iEducarLegacy\Modules\TransporteEscolar\Views
 */
class EmpresaController extends EditController
{
    protected $_dataMapper = 'Usuario_Model_FuncionarioDataMapper';
    protected $_titulo = 'i-Educar - Empresas';

    protected $_nivelAcessoOption = NivelAcesso::SOMENTE_ESCOLA;
    protected $_processoAp = 21235;
    protected $_deleteOption = true;

    protected $_formMap = [
        'Pessoa' => [
            'label' => 'Pessoa responsável',
            'help' => '',
        ],

        'observacao' => [
            'label' => 'Observações',
            'help' => '',
        ],

        'id' => [
            'label' => 'Código da empresa',
            'help' => '',
        ],

        'pessoaj' => [
            'label' => 'Pessoa jurídica',
            'help' => '',
        ]
    ];

    protected function _preConstruct()
    {
        $this->_options = $this->mergeOptions([
            'edit_success' => '/Intranet/transporte_empresa_lst.php',
            'delete_success' => '/Intranet/transporte_empresa_lst.php'
        ], $this->_options);
        $nomeMenu = $this->getRequest()->id == null ? 'Cadastrar' : 'Editar';

        $this->breadcrumb("$nomeMenu empresa", [
            url('Intranet/educar_transporte_escolar_index.php') => 'Transporte escolar',
        ]);
    }

    protected function _initNovo()
    {
        return false;
    }

    protected function _initEditar()
    {
        return false;
    }

    public function Gerar()
    {
        $this->url_cancelar = '/Intranet/transporte_empresa_lst.php';

        // Código da empresa
        $options = [
            'label' => $this->_getLabel('id'),
            'disabled' => true,
            'required' => false,
            'size' => 25
        ];
        $this->inputsHelper()->integer('id', $options);

        $options = ['label' => $this->_getLabel('pessoaj'), 'required' => true];
//    $this->inputsHelper()->integer('pessoaj', $options);
        $this->inputsHelper()->simpleSearchPessoaj('pessoaj', $options);

        // nome
        $options = ['label' => $this->_getLabel('Pessoa'), 'size' => 68];
        $this->inputsHelper()->simpleSearchPessoa('nome', $options);

        // observações
        $options = [
            'label' => Utils::toLatin1($this->_getLabel('observacao')),
            'required' => false,
            'size' => 50,
            'max_length' => 253
        ];
        $this->inputsHelper()->textArea('observacao', $options);

        $this->loadResourceAssets($this->getDispatcher());
    }
}

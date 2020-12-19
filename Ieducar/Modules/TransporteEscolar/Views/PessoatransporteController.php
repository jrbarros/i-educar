<?php

namespace iEducarLegacy\Modules\TransporteEscolar\Views;

use iEducar\Support\View\SelectOptions;
use iEducarLegacy\Intranet\Source\Modules\RotaTransporteEscolar;
use iEducarLegacy\Lib\App\Model\NivelAcesso;
use iEducarLegacy\Lib\Portabilis\Controller\Page\EditController;

/**
 * Class PessoatransporteController
 * @package iEducarLegacy\Modules\TransporteEscolar\Views
 */
class PessoatransporteController extends EditController
{
    protected $_dataMapper = 'FuncionarioDataMapper';
    protected $_titulo = 'i-Educar - Usu&aacute;rios de transporte';

    protected $_nivelAcessoOption = NivelAcesso::SOMENTE_ESCOLA;
    protected $_processoAp = 21240;
    protected $_deleteOption = true;

    protected $_formMap = [

        'id' => [
            'label' => 'Código',
            'help' => '',
        ],
        'Pessoa' => [
            'label' => 'Pessoa',
            'help' => '',
        ],
        'rota' => [
            'label' => 'Rota',
            'help' => '',
        ],
        'ponto' => [
            'label' => 'Ponto de embarque',
            'help' => '',
        ],
        'destino' => [
            'label' => 'Destino (Caso for diferente da rota)',
            'help' => '',
        ],
        'observacao' => [
            'label' => 'Observações',
            'help' => '',
        ],
        'turno' => [
            'label' => 'Turno',
            'help' => '',
        ],
    ];

    protected function _preConstruct()
    {
        $this->_options = $this->mergeOptions([
            'edit_success' => '/Intranet/transporte_pessoa_lst.php',
            'delete_success' => '/Intranet/transporte_pessoa_lst.php'
        ], $this->_options);
        $nomeMenu = $this->getRequest()->id == null ? 'Cadastrar' : 'Editar';
        $this->breadcrumb("$nomeMenu usu&aacute;rio de transporte", [
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
        $this->url_cancelar = '/Intranet/transporte_pessoa_lst.php';

        // Código do vinculo
        $options = [
            'label' => $this->_getLabel('id'),
            'disabled' => true,
            'required' => false,
            'size' => 25
        ];
        $this->inputsHelper()->integer('id', $options);

        // Pessoa
        $options = ['label' => $this->_getLabel('Pessoa'), 'required' => true];
        $this->inputsHelper()->simpleSearchPessoa('nome', $options);

        // Montar o inputsHelper->select \/
        // Cria lista de rotas
        $obj_rota = new RotaTransporteEscolar();
        $obj_rota->setOrderBy(' descricao asc ');
        $lista_rota = $obj_rota->lista();
        $rota_resources = ['' => 'Selecione uma rota'];
        foreach ($lista_rota as $reg) {
            $rota_resources["{$reg['cod_rota_transporte_escolar']}"] = "{$reg['descricao']} - {$reg['ano']}";
        }

        // Rota
        $options = [
            'label' => $this->_getLabel('rota'),
            'required' => true,
            'resources' => $rota_resources
        ];
        $this->inputsHelper()->select('rota', $options);

        // Ponto de Embarque
        $options = [
            'label' => $this->_getLabel('ponto'),
            'required' => false,
            'resources' => ['' => 'Selecione uma rota acima']
        ];
        $this->inputsHelper()->select('ponto', $options);

        // Destino
        $options = ['label' => $this->_getLabel('destino'), 'required' => false];
        $this->inputsHelper()->simpleSearchPessoaj('destino', $options);

        // observacoes
        $options = [
            'label' => $this->_getLabel('observacao'),
            'required' => false,
            'size' => 50,
            'max_length' => 255
        ];
        $this->inputsHelper()->textArea('observacao', $options);

        $this->inputsHelper()->select('turno', [
            'required' => false,
            'resources' => SelectOptions::transportPeriods(),
        ]);

        $this->loadResourceAssets($this->getDispatcher());
    }
}

<?php

namespace iEducarLegacy\Modules\TransporteEscolar\Views;

use iEducarLegacy\Intranet\Source\Modules\TipoVeiculo;
use iEducarLegacy\Lib\App\Model\NivelAcesso;
use iEducarLegacy\Lib\Portabilis\Controller\Page\EditController;
use iEducarLegacy\Lib\Portabilis\String\Utils;

class VeiculoController extends EditController
{
    protected $_dataMapper = 'FuncionarioDataMapper';
    protected $_titulo = 'i-Educar - Motoristas';

    protected $_nivelAcessoOption = NivelAcesso::SOMENTE_ESCOLA;
    protected $_processoAp = 21237;
    protected $_deleteOption = true;

    protected $_formMap = [
        'id' => [
            'label' => 'Código do veículo',
            'help' => '',
        ],

        'descricao' => [
            'label' => 'Descrição',
            'help' => '',
        ],

        'placa' => [
            'label' => 'Placa',
            'help' => '',
        ],

        'renavam' => [
            'label' => 'Renavam',
            'help' => '',
        ],

        'chassi' => [
            'label' => 'Chassi',
            'help' => '',
        ],

        'marca' => [
            'label' => 'Marca',
            'help' => '',
        ],

        'ano_fabricacao' => [
            'label' => 'Ano fabricação',
            'help' => '',
        ],

        'ano_modelo' => [
            'label' => 'Ano modelo',
            'help' => '',
        ],

        'passageiros' => [
            'label' => 'Limite de passageiros',
            'help' => '',
        ],

        'tipo' => [
            'label' => 'Categoria',
            'help' => '',
        ],

        'malha' => [
            'label' => 'Malha',
            'help' => '',
        ],

        'abrangencia' => [
            'label' => 'Abrangência',
            'help' => '',
        ],

        'exclusivo_transporte_escolar' => [
            'label' => 'Exclusivo para transporte escolar',
            'help' => '',
        ],

        'adaptado_necessidades_especiais' => [
            'label' => 'Adaptado para pessoas com necessidades especiais',
            'help' => '',
        ],

        'tercerizado' => [
            'label' => 'Tercerizado',
            'help' => '',
        ],

        'ativo' => [
            'label' => 'Ativo',
            'help' => '',
        ],

        'descricao_inativo' => [
            'label' => 'Descrição de inatividade',
            'help' => '',
        ],

        'empresa' => [
            'label' => 'Empresa',
            'help' => '',
        ],

        'motorista' => [
            'label' => 'Motorista responsável',
            'help' => '',
        ],

        'observacao' => [
            'label' => 'Observações',
            'help' => '',
        ]

    ];

    protected function _preConstruct()
    {
        $this->_options = $this->mergeOptions([
            'edit_success' => '/Intranet/transporte_veiculo_lst.php',
            'delete_success' => '/Intranet/transporte_veiculo_lst.php'
        ], $this->_options);
        $nomeMenu = $this->getRequest()->id == null ? 'Cadastrar' : 'Editar';
        $this->breadcrumb("$nomeMenu ve&iacute;culo", [
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
        $this->url_cancelar = '/Intranet/transporte_veiculo_lst.php';

        // Código do Motorista
        $options = [
            'label' => $this->_getLabel('id'),
            'disabled' => true,
            'required' => false,
            'size' => 25
        ];
        $this->inputsHelper()->integer('id', $options);

        // descrição
        $options = [
            'label' => Utils::toLatin1($this->_getLabel('descricao')),
            'required' => true,
            'size' => 50,
            'max_length' => 255
        ];
        $this->inputsHelper()->text('descricao', $options);

        //placa
        $options = [
            'label' => Utils::toLatin1($this->_getLabel('placa')),
            'required' => false,
            'size' => 10,
            'max_length' => 10
        ];
        $this->inputsHelper()->text('placa', $options);

        //renavam
        $options = [
            'label' => Utils::toLatin1($this->_getLabel('renavam')),
            'required' => false,
            'size' => 15,
            'max_length' => 15
        ];
        $this->inputsHelper()->integer('renavam', $options);

        //chassi
        $options = [
            'label' => Utils::toLatin1($this->_getLabel('chassi')),
            'required' => false,
            'size' => 30,
            'max_length' => 30
        ];
        $this->inputsHelper()->text('chassi', $options);

        //marca
        $options = [
            'label' => Utils::toLatin1($this->_getLabel('marca')),
            'required' => false,
            'size' => 50,
            'max_length' => 50
        ];
        $this->inputsHelper()->text('marca', $options);

        //Ano de fabricacao
        $options = [
            'label' => $this->_getLabel('ano_fabricacao'),
            'max_length' => 4,
            'size' => 5,
            'required' => false,
            'placeholder' => ''
        ];
        $this->inputsHelper()->integer('ano_fabricacao', $options);

        // Ano do modelo
        $options = [
            'label' => $this->_getLabel('ano_modelo'),
            'max_length' => 4,
            'size' => 5,
            'required' => false,
            'placeholder' => ''
        ];
        $this->inputsHelper()->integer('ano_modelo', $options);

        // Passageiros
        $options = [
            'label' => $this->_getLabel('passageiros'),
            'max_length' => 3,
            'size' => 5,
            'required' => true,
            'placeholder' => ''
        ];
        $this->inputsHelper()->integer('passageiros', $options);

        // Malha
        $malhas = [
            null => 'Selecione uma Malha',
            'A' => Utils::toLatin1('Aquaviária/Embarcação'),
            'F' => Utils::toLatin1('Ferroviária'),
            'R' => Utils::toLatin1('Rodoviária')
        ];

        $options = [
            'label' => $this->_getLabel('malha'),
            'resources' => $malhas,
            'required' => true
        ];

        $this->inputsHelper()->select('malha', $options);

        // Tipo de veículo
        $tiposVeiculo = [null => 'Selecione um Tipo'];

        $objTipo = new TipoVeiculo();
        $lista = $objTipo->lista();
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $tiposVeiculo["{$registro['cod_tipo_veiculo']}"] = "{$registro['descricao']}";
            }
        }

        $options = [
            'label' => $this->_getLabel('tipo'),
            'resources' => $tiposVeiculo,
            'required' => true
        ];

        $this->inputsHelper()->select('tipo', $options);

        // Exclusivo transporte escolar
        $options = ['label' => Utils::toLatin1($this->_getLabel('exclusivo_transporte_escolar'))];
        $this->inputsHelper()->checkbox('exclusivo_transporte_escolar', $options);

        // Adaptado a necessidades especiais
        $options = ['label' => Utils::toLatin1($this->_getLabel('adaptado_necessidades_especiais'))];
        $this->inputsHelper()->checkbox('adaptado_necessidades_especiais', $options);

        // Ativo
        $options = ['label' => Utils::toLatin1($this->_getLabel('ativo')), 'value' => 'on'];
        $this->inputsHelper()->checkbox('ativo', $options);

        // descricao_inativo
        $options = [
            'label' => Utils::toLatin1($this->_getLabel('descricao_inativo')),
            'required' => false,
            'size' => 50,
            'max_length' => 155
        ];
        $this->inputsHelper()->textArea('descricao_inativo', $options);

        // Codigo da empresa
        $options = ['label' => Utils::toLatin1($this->_getLabel('empresa')), 'required' => true];
        $this->inputsHelper()->simpleSearchEmpresa('empresa', $options);

        // Codigo do motorista
        $options = ['label' => Utils::toLatin1($this->_getLabel('motorista')), 'required' => false];
        $this->inputsHelper()->simpleSearchMotorista('motorista', $options);

        // observações
        $options = [
            'label' => Utils::toLatin1($this->_getLabel('observacao')),
            'required' => false,
            'size' => 50,
            'max_length' => 255
        ];
        $this->inputsHelper()->textArea('observacao', $options);

        $this->loadResourceAssets($this->getDispatcher());
    }
}

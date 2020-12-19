<?php

namespace iEducarLegacy\Modules\TransporteEscolar\Views;

use iEducar\Modules\Addressing\LegacyAddressingFields;
use iEducarLegacy\Lib\App\Model\NivelAcesso;
use iEducarLegacy\Lib\Portabilis\Controller\Page\EditController;
use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;

/**
 * Class PontoController
 * @package iEducarLegacy\Modules\TransporteEscolar\Views
 */
class PontoController extends EditController
{
    use LegacyAddressingFields;

    protected $_dataMapper = 'FuncionarioDataMapper';
    protected $_titulo = 'i-Educar - Pontos';

    protected $_nivelAcessoOption = NivelAcesso::SOMENTE_ESCOLA;
    protected $_processoAp = 21239;
    protected $_deleteOption = true;

    protected $_formMap = [

        'id' => [
            'label' => 'Código do ponto',
            'help' => '',
        ],
        'desc' => [
            'label' => 'Descrição',
            'help' => '',
        ]
    ];

    protected function _preConstruct()
    {
        $this->_options = $this->mergeOptions([
            'edit_success' => '/Intranet/transporte_ponto_lst.php',
            'delete_success' => '/Intranet/transporte_ponto_lst.php'
        ], $this->_options);
        $nomeMenu = $this->getRequest()->id == null ? 'Cadastrar' : 'Editar';
        $this->breadcrumb("$nomeMenu ponto", [
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
        $this->url_cancelar = '/Intranet/transporte_ponto_lst.php';

        // Código do ponto
        $options = [
            'label' => $this->_getLabel('id'),
            'disabled' => true,
            'required' => false,
            'size' => 25
        ];
        $this->inputsHelper()->integer('id', $options);

        // descricao
        $options = [
            'label' => Utils::toLatin1($this->_getLabel('desc')),
            'required' => true,
            'size' => 50,
            'max_length' => 70
        ];
        $this->inputsHelper()->text('desc', $options);

        $this->viewAddress();

        $this->inputsHelper()->text('latitude', ['required' => false]);

        $this->inputsHelper()->text('longitude', ['required' => false]);

        $script = [
            '/Modules/Cadastro/Assets/Javascripts/Addresses.js',
            '/Lib/Utils/gmaps.js',
            '/Modules/Portabilis/Assets/Javascripts/Frontend/Ieducar.singleton_gmap.js'
        ];

        Application::loadJavascript($this, $script);

        $this->loadResourceAssets($this->getDispatcher());
    }
}

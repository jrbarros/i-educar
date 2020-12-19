<?php

require_once 'Core/Controller/Page/CoreControllerPageViewController.php';
require_once 'ComponenteCurricular/Model/ComponenteDataMapper.php';
require_once 'Source/pmieducar/geral.inc.php';

class ViewController extends Core_Controller_Page_ViewController
{
    protected $_dataMapper = 'ComponenteDataMapper';

    protected $_titulo = 'Detalhes de área de conhecimento';

    protected $_processoAp = 946;

    protected $_tableMap = [
        'Nome' => 'nome',
        'Abreviatura' => 'abreviatura',
        'Base curricular' => 'tipo_base',
        'Área conhecimento' => 'area_conhecimento'
    ];

    /**
     * Construtor.
     */
    public function __construct()
    {
        // Apenas faz o override do construtor
    }

    protected function _preRender()
    {
        parent::_preRender();

        $this->breadcrumb('Detalhe do componente curricular', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);
    }

    public function setUrlCancelar(CoreExt_Entity $entry)
    {
        $this->url_cancelar = 'Intranet/educar_componente_curricular_lst.php';
    }
}

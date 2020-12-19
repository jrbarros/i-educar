<?php

namespace iEducarLegacy\Modules\ComponenteCurricular\Views;

use iEducarLegacy\Lib\Core\Controller\Page\CoreControllerPageViewController;

/**
 * Class ViewController
 * @package iEducarLegacy\Modules\ComponenteCurricular\Views
 */
class ViewController extends CoreControllerPageViewController
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

<?php

namespace iEducarLegacy\Modules\Avaliacao\Views;

use iEducarLegacy\Lib\Portabilis\Controller\Page\ListController;

/**
 * Class DiarioController
 */
class DiarioController extends ListController
{
    protected $_titulo = 'Lançamento por turma';
    protected $_processoAp = 642;

    public function Gerar()
    {
        $userId = Portabilis_Utils_User::currentUserId();
        $componenteRequired = Portabilis_Business_Professor::isOnlyProfessor(false, $userId);

        $this->inputsHelper()->input('ano', 'ano');
        $this->inputsHelper()->dynamic(['instituicao', 'escola', 'curso', 'serie', 'turma', 'etapa']);
        $this->inputsHelper()->dynamic(['componenteCurricularForDiario'], ['required' => $componenteRequired]);
        $this->inputsHelper()->dynamic(['Matricula'], ['required' => false ]);

        $navegacaoTab = [
            '1' => 'Horizontal(padrão)',
            '2' => 'Vertical',
        ];

        $options = [
            'label' =>'Navegação do cursor(tab)',
            'resources' => $navegacaoTab,
            'required' => false,
            'inline' => true,
            'value' => $navegacaoTab[1],
        ];

        $this->inputsHelper()->select('navegacao_tab', $options);

        $this->inputsHelper()->hidden('mostrar_botao_replicar_todos', ['value' => $teste = config('legacy.app.faltas_notas.mostrar_botao_replicar')]);

        $this->loadResourceAssets($this->getDispatcher());
    }

    protected function _preRender()
    {
        parent::_preRender();

        $this->breadcrumb('Lançamento de faltas e notas', ['/Intranet/educar_index.php' => 'Escola']);
    }
}
?>


<?php

namespace iEducarLegacy\Modules\Avaliacao\Views;

use iEducarLegacy\Lib\Portabilis\Controller\Page\ListController;

/**
 * i-Educar - Sistema de gestão escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de Itajaí
 *           <ctima@itajai.sc.gov.br>
 *
 * Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU conforme publicada pela Free
 * Software Foundation; tanto a versão 2 da Licença, como (a seu critério)
 * qualquer versão posterior.
 *
 * Este programa é distribuí­do na expectativa de que seja útil, porém, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia implí­cita de COMERCIABILIDADE OU
 * ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA. Consulte a Licença Pública Geral
 * do GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto
 * com este programa; se não, escreva para a Free Software Foundation, Inc., no
 * endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 *
 * @author    Lucas D'Avila <lucasdavila@portabilis.com.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   Avaliacao
 * @subpackage  Modules
 *
 * @since     Arquivo disponível desde a versão ?
 *
 * @version   $Id$
 */

// TODO migrar para novo padrao

/**
 * Class PromocaoController
 * @package iEducarLegacy\Modules\Avaliacao\Views
 */
class PromocaoController extends ListController
{
    protected $_dataMapper = 'NotaAlunoDataMapper';
    protected $_titulo     = 'Lan&ccedil;amento por turma';
    protected $_processoAp = 644;
    protected $_formMap    = [];

    public function Gerar()
    {
        $this->inputsHelper()->dynamic('ano', ['id' => 'ano']);
        $this->inputsHelper()->dynamic('instituicao', ['id' => 'instituicao_id']);
        $this->inputsHelper()->dynamic('escola', ['id' => 'escola', 'required' => false]);
        $this->inputsHelper()->dynamic('curso', ['id' => 'curso', 'required' => false]);
        $this->inputsHelper()->dynamic('serie', ['id' => 'serie', 'required' => false]);
        $this->inputsHelper()->dynamic('turma', ['id' => 'turma', 'required' => false]);
        $this->inputsHelper()->dynamic('situacaoMatricula', ['id' => 'Matricula', 'value' => 10, 'required' => false]);

        $this->loadResourceAssets($this->getDispatcher());

        $this->breadcrumb('Atualização de matrículas', [
        url('Intranet/educar_configuracoes_index.php') => 'Configurações',
    ]);
    }
}

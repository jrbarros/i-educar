<?php

/**
 * i-Educar - Sistema de gestão escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de Itajaí
 *                     <ctima@itajai.sc.gov.br>
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
 * @author      Adriano Erik Weiguert Nagasava <ctima@itajai.sc.gov.br>
 * @license     http://creativecommons.org/licenses/GPL/2.0/legalcode.pt  CC GNU GPL
 *
 * @package     Core
 * @subpackage  Escolaridade
 *
 * @since       Arquivo disponível desde a versão 1.0.0
 *
 * @version     $Id$
 */

require_once 'Source/Base.php';
require_once 'Source/Listagem.php';
require_once 'Source/Banco.php';
require_once '';
require_once 'Source/pmieducar/geral.inc.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' i-Educar - Escolaridade do servidor');
        $this->processoAp = '632';
    }
}

class indice extends clsListagem
{
    /**
     * Referência a usuário da sessão
     *
     * @var int
     */
    public $pessoa_logada = null;

    /**
     * Título no topo da página
     *
     * @var string
     */
    public $titulo = '';

    /**
     * Limite de registros por página
     *
     * @var int
     */
    public $limite = 0;

    /**
     * Início dos registros a serem exibidos (limit)
     *
     * @var int
     */
    public $offset = 0;

    public $idesco;
    public $descricao;

    public function Gerar()
    {
        $this->titulo = 'Escolaridade - Listagem';

        // Passa todos os valores obtidos no GET para atributos do objeto
        foreach ($_GET as $var => $val) {
            $this->$var = ($val === '') ? null : $val;
        }

        $this->addCabecalhos([
      'Descri&ccedil;&atilde;o'
    ]);

        // Outros Filtros
        $this->campoTexto('descricao', 'Descrição', $this->descricao, 30, 255, false);

        // Paginador
        $this->limite = 20;
        $this->offset = ($_GET['pagina_' . $this->nome]) ?
      $_GET['pagina_' . $this->nome] * $this->limite-$this->limite : 0;

        $obj_escolaridade = new CadastroEscolaridade();
        $obj_escolaridade->setOrderby('descricao ASC');
        $obj_escolaridade->setLimite($this->limite, $this->offset);
        $lista = $obj_escolaridade->lista(
        null,
        $this->descricao
    );

        $total = $obj_escolaridade->_total;

        // Monta a lista
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $this->addLinhas([
          "<a href=\"educar_escolaridade_det.php?idesco={$registro['idesco']}\">{$registro['descricao']}</a>"
        ]);
            }
        }

        $this->addPaginador2('educar_escolaridade_lst.php', $total, $_GET, $this->nome, $this->limite);
        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(632, $this->pessoa_logada, 3)) {
            $this->acao = 'go("educar_escolaridade_cad.php")';
            $this->nome_acao = 'Novo';
        }

        $this->breadcrumb('Escolaridade do servidor', [
        url('Intranet/educar_servidores_index.php') => 'Servidores',
    ]);

        $this->largura = '100%';
    }
}

// Instancia objeto de página
$pagina = new clsIndexBase();

// Instancia objeto de conteúdo
$miolo = new indice();

// Atribui o conteúdo à página
$pagina->addForm($miolo);

// Gera o código HTML
$pagina->MakeAll();

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
 * @author      Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 * @license     http://creativecommons.org/licenses/GPL/2.0/legalcode.pt  CC GNU GPL
 *
 * @package     Core
 * @subpackage  ReservaVaga
 *
 * @since       Arquivo disponível desde a versão 1.0.0
 *
 * @version     $Id$
 */

require_once 'Source/Base.php';
require_once 'Source/Listagem.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' i-Educar - Reserva Vaga');
        $this->processoAp = '639';
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

    public $ref_cod_escola;
    public $ref_cod_serie;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_ref_cod_serie;
    public $ref_cod_curso;
    public $ref_cod_instituicao;

    public function Gerar()
    {
        $this->titulo = 'Reserva Vaga - Listagem';

        foreach ($_GET as $var => $val) { // passa todos os valores obtidos no GET para atributos do objeto
            $this->$var = ($val === '') ? null : $val;
        }

        $lista_busca = [
      'S&eacute;rie',
      'Curso'
    ];

        $obj_permissao = new Permissoes();
        $nivel_usuario = $obj_permissao->nivel_acesso($this->pessoa_logada);
        if ($nivel_usuario == 1) {
            $lista_busca[] = 'Escola';
            $lista_busca[] = 'Institui&ccedil;&atilde;o';
        } elseif ($nivel_usuario == 2) {
            $lista_busca[] = 'Escola';
        }
        $this->addCabecalhos($lista_busca);

        $get_escola = true;
        $get_curso  = true;
        $get_escola_curso_serie = true;
        include 'Source/pmieducar/educar_campo_lista.php';

        // Paginador
        $this->limite = 20;
        $this->offset = $_GET['pagina_' . $this->nome] ?
      $_GET['pagina_' . $this->nome] * $this->limite - $this->limite :
      0;

        $obj_escola_serie = new EscolaSerie();
        $obj_escola_serie->setLimite($this->limite, $this->offset);

        $lista = $obj_escola_serie->lista(
        $this->ref_cod_escola,
        $this->ref_ref_cod_serie,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        1,
        null,
        null,
        null,
        null,
        $this->ref_cod_instituicao,
        $this->ref_cod_curso
    );

        $total = $obj_escola_serie->_total;

        // monta a lista
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $obj_ref_cod_serie = new Serie($registro['ref_cod_serie']);
                $det_ref_cod_serie = $obj_ref_cod_serie->detalhe();
                $nm_serie = $det_ref_cod_serie['nm_serie'];

                $obj_curso = new Curso($registro['ref_cod_curso']);
                $det_curso = $obj_curso->detalhe();
                $registro['ref_cod_curso'] = $det_curso['nm_curso'];

                $obj_ref_cod_escola = new Escola($registro['ref_cod_escola']);
                $det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
                $nm_escola = $det_ref_cod_escola['nome'];

                $obj_ref_cod_instituicao = new Instituicao($registro['ref_cod_instituicao']);
                $det_ref_cod_instituicao = $obj_ref_cod_instituicao->detalhe();
                $registro['ref_cod_instituicao'] = $det_ref_cod_instituicao['nm_instituicao'];

                $lista_busca = [
          "<a href=\"educar_reserva_vaga_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$nm_serie}</a>",
          "<a href=\"educar_reserva_vaga_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$registro['ref_cod_curso']}</a>"
        ];

                if ($nivel_usuario == 1) {
                    $lista_busca[] = "<a href=\"educar_reserva_vaga_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$nm_escola}</a>";
                    $lista_busca[] = "<a href=\"educar_reserva_vaga_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$registro['ref_cod_instituicao']}</a>";
                } elseif ($nivel_usuario == 2) {
                    $lista_busca[] = "<a href=\"educar_reserva_vaga_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$nm_escola}</a>";
                }
                $this->addLinhas($lista_busca);
            }
        }

        $this->addPaginador2('educar_reserva_vaga_lst.php', $total, $_GET, $this->nome, $this->limite);
        $this->largura = '100%';

        $this->breadcrumb('Listagem de reservas de vaga', [
        url('Intranet/educar_index.php') => 'Escola',
    ]);
    }
}

// Instancia objeto de página
$pagina = new clsIndexBase();

// Instancia objeto de conteúdo
$miolo = new indice();

// Atribui o conteúdo à  página
$pagina->addForm($miolo);

// Gera o código HTML
$pagina->MakeAll();
?>

<script type='text/javascript'>
document.getElementById('ref_cod_escola').onchange = function() {
  getEscolaCurso();
}

document.getElementById('ref_cod_curso').onchange = function() {
  getEscolaCursoSerie();
}
</script>

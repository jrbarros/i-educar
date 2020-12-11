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
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Arquivo disponível desde a versão 1.0.0
 *
 * @version   $Id$
 */

use Illuminate\Support\Facades\Session;

require_once 'Source/Base.php';
require_once 'Source/Cadastro.inc.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';

/**
 * clsIndexBase class.
 *
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Classe disponível desde a versão 1.0.0
 *
 * @version   @@package_version@@
 */
class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' i-Educar - Servidor Curso');
        $this->processoAp         = 0;
        $this->renderBanner       = false;
        $this->renderMenu         = false;
        $this->renderMenuSuspenso = false;
    }
}

/**
 * indice class.
 *
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Classe disponível desde a versão 1.0.0
 *
 * @version   @@package_version@@
 */
class indice extends clsCadastro
{
    public $pessoa_logada;

    public $cod_servidor;
    public $ref_cod_instituicao;
    public $ref_idesco;
    public $ref_cod_funcao;
    public $carga_horaria;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_curso;
    public $ref_cod_disciplina;
    public $cursos_servidor;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_servidor        = $_GET['ref_cod_servidor'];
        $this->ref_cod_instituicao = $_GET['ref_cod_instituicao'];

        $obj_permissoes = new Permissoes();

        $obj_permissoes->permissao_cadastra(635, $this->pessoa_logada, 7, 'educar_servidor_lst.php');

        if (is_numeric($this->cod_servidor) && is_numeric($this->ref_cod_instituicao)) {
            $obj = new Servidor(
          $this->cod_servidor,
          null,
          null,
          null,
          null,
          null,
          null,
          $this->ref_cod_instituicao
      );

            $registro  = $obj->detalhe();

            if ($registro) {
                $retorno = 'Editar';
            }
        }

        $this->cursos_servidor = Session::get('cursos_servidor');

        if (!$this->cursos_servidor) {
            $obj_servidor_curso = new ServidorCursoMinistra();

            $lst_servidor_curso = $obj_servidor_curso->lista(
          null,
          $this->ref_cod_instituicao,
          $this->cod_servidor
      );

            if ($lst_servidor_curso) {
                foreach ($lst_servidor_curso as $curso) {
                    $this->cursos_servidor[$curso['ref_cod_curso']] = $curso['ref_cod_curso'];
                }
            }
        }

        return $retorno;
    }

    public function Gerar()
    {
        $this->campoOculto('ref_cod_instituicao', $this->ref_cod_instituicao);
        $opcoes = $opcoes_curso = ['' => 'Selecione'];

        $obj_cursos = new Curso();
        $obj_cursos->setOrderby('nm_curso');

        $lst_cursos = $obj_cursos->lista(
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
        null,
        null,
        1,
        null,
        $this->ref_cod_instituicao
    );

        if ($lst_cursos) {
            foreach ($lst_cursos as $curso) {
                $opcoes_curso[$curso['cod_curso']] = $curso['nm_curso'];
            }
        }

        $arr_valores = [];

        if ($this->cursos_servidor) {
            foreach ($this->cursos_servidor as $curso) {
                $arr_valores[] = [$curso];
            }
        }

        $this->campoTabelaInicio(
        'cursos_ministra',
        'Cursos Ministrados',
        ['Curso'],
        $arr_valores,
        ''
    );

        $this->campoLista(
        'ref_cod_curso',
        'Curso',
        $opcoes_curso,
        $this->ref_cod_curso,
        '',
        '',
        '',
        ''
    );

        $this->campoTabelaFim();
    }

    public function Novo()
    {
        $curso_servidor = [];
        if ($this->ref_cod_curso) {
            foreach ($this->ref_cod_curso as $curso) {
                $curso_servidor[$curso] = $curso;
            }
        }

        Session::put([
        'cursos_servidor' => $curso_servidor,
        'cod_servidor' => $this->cod_servidor,
    ]);
        Session::save();
        Session::start();

        echo "<script>parent.fechaExpansivel( '{$_GET['div']}');</script>";
        die();
    }

    public function Editar()
    {
        return $this->Novo();
    }

    public function Excluir()
    {
        return false;
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

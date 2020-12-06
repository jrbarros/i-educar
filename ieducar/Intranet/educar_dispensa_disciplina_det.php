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

require_once 'include/Base.php';
require_once 'include/clsDetalhe.inc.php';
require_once 'include/Banco.inc.php';
require_once 'include/pmieducar/geral.inc.php';
require_once 'ComponenteCurricular/Model/ComponenteDataMapper.php';

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
        $this->SetTitulo($this->_instituicao . ' i-Educar - Dispensa Componente Curricular');
        $this->processoAp = 578;
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
class indice extends clsDetalhe
{
    public $titulo;

    public $ref_cod_matricula;
    public $ref_cod_turma;
    public $ref_cod_serie;
    public $ref_cod_escola;
    public $ref_cod_disciplina;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $ref_cod_tipo_dispensa;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $observacao;
    public $ref_sequencial;

    public function Gerar()
    {
        $this->titulo = 'Dispensa Componente Curricular - Detalhe';
        $this->addBanner(
        'imagens/nvp_top_intranet.jpg',
        'imagens/nvp_vert_intranet.jpg',
        'Intranet'
    );

        $this->ref_cod_disciplina = $_GET['ref_cod_disciplina'];
        $this->ref_cod_matricula  = $_GET['ref_cod_matricula'];
        $this->ref_cod_serie      = $_GET['ref_cod_serie'];
        $this->ref_cod_disciplina = $_GET['ref_cod_disciplina'];
        $this->ref_cod_escola     = $_GET['ref_cod_escola'];

        $tmp_obj = new DispensaDisciplina(
        $this->ref_cod_matricula,
        $this->ref_cod_serie,
        $this->ref_cod_escola,
        $this->ref_cod_disciplina
    );

        $registro = $tmp_obj->detalhe();

        if (!$registro) {
            $this->simpleRedirect('educar_dispensa_disciplina_lst.php?ref_cod_matricula=' . $this->ref_cod_matricula);
        }

        $obj_serie = new Serie($this->ref_cod_serie);
        $det_serie = $obj_serie->detalhe();
        $registro['ref_ref_cod_serie'] = $det_serie['nm_serie'];

        // Dados da matrícula
        $obj_ref_cod_matricula = new Matricula();
        $detalhe_aluno = array_shift($obj_ref_cod_matricula->lista($this->ref_cod_matricula));

        $obj_aluno = new Aluno();
        $det_aluno = array_shift($obj_aluno->lista(
        $detalhe_aluno['ref_cod_aluno'],
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        1
    ));

        $obj_escola = new Escola(
        $this->ref_cod_escola,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        1
    );
        $det_escola = $obj_escola->detalhe();

        $nm_aluno = $det_aluno['nome_aluno'];

        // Dados do curso
        $obj_ref_cod_curso = new Curso($detalhe_aluno['ref_cod_curso']);
        $det_ref_cod_curso = $obj_ref_cod_curso->detalhe();
        $registro['ref_cod_curso'] = $det_ref_cod_curso['nm_curso'];

        // Tipo de dispensa
        $obj_ref_cod_tipo_dispensa = new TipoDispensa($registro['ref_cod_tipo_dispensa']);
        $det_ref_cod_tipo_dispensa = $obj_ref_cod_tipo_dispensa->detalhe();
        $registro['ref_cod_tipo_dispensa'] = $det_ref_cod_tipo_dispensa['nm_tipo'];

        if ($registro['ref_cod_matricula']) {
            $this->addDetalhe(['Matricula', $registro['ref_cod_matricula']]);
        }

        if ($nm_aluno) {
            $this->addDetalhe(['Aluno', $nm_aluno]);
        }

        if ($registro['ref_cod_curso']) {
            $this->addDetalhe(['Curso', $registro['ref_cod_curso']]);
        }

        if ($registro['ref_ref_cod_serie']) {
            $this->addDetalhe(['Série', $registro['ref_ref_cod_serie']]);
        }

        if ($registro['ref_cod_disciplina']) {
            $componenteMapper = new ComponenteCurricular_Model_ComponenteDataMapper();
            $componente = $componenteMapper->find($registro['ref_cod_disciplina']);
            $this->addDetalhe(['Componente Curricular', $componente->nome]);
        }

        if ($registro['ref_cod_tipo_dispensa']) {
            $this->addDetalhe(['Tipo Dispensa', $registro['ref_cod_tipo_dispensa']]);
        }

        if ($registro['observacao']) {
            $this->addDetalhe(['Observação', $registro['observacao']]);
        }

        $obj_permissoes = new Permissoes();

        if ($obj_permissoes->permissao_cadastra(578, $this->pessoa_logada, 7) && $detalhe_aluno['aprovado'] == App_Model_MatriculaSituacao::EM_ANDAMENTO) {
            $this->url_novo   = sprintf(
          'educar_dispensa_disciplina_cad.php?ref_cod_matricula=%d',
          $this->ref_cod_matricula
      );
            $this->url_editar = sprintf(
          'educar_dispensa_disciplina_cad.php?ref_cod_matricula=%d&ref_cod_disciplina=%d',
          $registro['ref_cod_matricula'],
          $registro['ref_cod_disciplina']
      );
        }

        $this->url_cancelar = 'educar_dispensa_disciplina_lst.php?ref_cod_matricula=' . $this->ref_cod_matricula;
        $this->largura      = '100%';

        $this->breadcrumb('Dispensa de componentes curriculares', [
        url('Intranet/educar_index.php') => 'Escola',
    ]);
    }
}

// Instancia objeto de página
$pagina = new clsIndexBase();

// Instancia objeto de conteúdo
$miolo = new indice();

// Atribui o conteúdo à  página
$pagina->addForm($miolo);

// Gera o código HTML
$pagina->MakeAll();

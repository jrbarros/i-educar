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

require_once 'Source/Base.php';
require_once 'Source/Cadastro.inc.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';
require_once 'Source/modules/ComponenteCurricularAnoEscolar.php';
require_once 'ComponenteCurricular/Model/TurmaDataMapper.php';

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
        $this->SetTitulo($this->_instituicao . ' i-Educar - S&eacute;rie');
        $this->processoAp = '9998859';
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

    public $instituicao_id;
    public $curso_id;
    public $serie_id;
    public $componente_id;
    public $carga_horaria;
    public $retorno;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->serie_id=$_GET['serie_id'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(
        9998859,
        $this->pessoa_logada,
        3,
        'educar_componentes_serie_lst.php'
    );

        if (is_numeric($this->serie_id)) {
            $retorno = 'Editar';
            $obj = new Serie($this->serie_id);
            $registro  = $obj->detalhe();

            if ($registro) {
                foreach ($registro as $campo => $val) {
                    $this->$campo = $val;
                }

                $this->curso_id = $registro['ref_cod_curso'];

                $obj_curso = new Curso($registro['ref_cod_curso']);
                $obj_curso_det = $obj_curso->detalhe();
                $this->instituicao_id = $obj_curso_det['ref_cod_instituicao'];
                $this->fexcluir = $obj_permissoes->permissao_excluir(
            9998859,
            $this->pessoa_logada,
            3
        );
            }
        }

        $this->url_cancelar = 'educar_componentes_serie_lst.php';

        $this->breadcrumb('Componentes da série', [
        url('Intranet/educar_index.php') => 'Escola',
    ]);

        $this->nome_url_cancelar = 'Cancelar';

        $this->alerta_faixa_etaria  = dbBool($this->alerta_faixa_etaria);
        $this->bloquear_matricula_faixa_etaria  = dbBool($this->bloquear_matricula_faixa_etaria);
        $this->exigir_inep  = dbBool($this->exigir_inep);

        $this->retorno = $retorno;

        return $retorno;
    }

    public function Gerar()
    {
        if ($_POST) {
            foreach ($_POST as $campo => $val) {
                $this->$campo = ($this->$campo) ? $this->$campo : $val;
            }
        }

        $opcoesCurso = ['' => 'Selecione um curso'];
        $opcoesSerie = ['' => 'Selecione uma série'];

        $this->campoOculto('curso_id', $this->curso_id);
        $this->campoOculto('serie_id', $this->serie_id);
        $this->campoOculto('serie_id', $this->serie_id);
        $this->campoOculto('retorno', $this->retorno);
        $this->campoOculto('sugestao_anos_letivos', json_encode(array_values($this->sugestaoAnosLetivos())));

        $this->inputsHelper()->dynamic('instituicao', ['value' => $this->instituicao_id]);

        $this->campoLista('ref_cod_curso', 'Curso', $opcoesCurso, $this->curso_id);
        $this->campoLista('ref_cod_serie', 'Série', $opcoesSerie, $this->serie_id);

        $helperOptions = ['objectName'  => 'ref_cod_area_conhecimento'];
        $options       = ['label' => 'Áreas de conhecimento',
                           'size' => 50,
                           'required' => false];

        $this->inputsHelper()->multipleSearchCustom('', $options, $helperOptions);

        $this->campoRotulo('componentes_', 'Componentes da série', '<table id=\'componentes\'></table>');

        $scripts = ['/Modules/Cadastro/Assets/Javascripts/ComponentesSerie.js',
                     '/Modules/Cadastro/Assets/Javascripts/ComponentesSerieAcao.js'];
        Portabilis_View_Helper_Application::loadJavascript($this, $scripts);
    }

    public function Novo()
    {
        // Todas as ações estão sendo realizadas em ComponentesSerieAcao.js
        $this->simpleRedirect('educar_componentes_serie_lst.php');
    }

    public function Editar()
    {
        // Todas as ações estão sendo realizadas em ComponentesSerieAcao.js
        $this->simpleRedirect('educar_componentes_serie_lst.php');
    }

    public function Excluir()
    {
        // Todas as ações estão sendo realizadas em ComponentesSerieAcao.js
        $this->mensagem .= 'Exclusão efetuada com sucesso.<br>';
        $this->simpleRedirect('educar_componentes_serie_lst.php');
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

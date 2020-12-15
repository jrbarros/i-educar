<?php

// error_reporting(E_ERROR);
// ini_set("display_errors", 1);
/**
 * i-Educar - Sistema de gestÃ£o escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de ItajaÃ­
 *                     <ctima@itajai.sc.gov.br>
 *
 * Este programa Ã© software livre; vocÃª pode redistribuÃ­-lo e/ou modificÃ¡-lo
 * sob os termos da LicenÃ§a PÃºblica Geral GNU conforme publicada pela Free
 * Software Foundation; tanto a versÃ£o 2 da LicenÃ§a, como (a seu critÃ©rio)
 * qualquer versÃ£o posterior.
 *
 * Este programa Ã© distribuÃ­Â­do na expectativa de que seja Ãºtil, porÃ©m, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia implÃ­Â­cita de COMERCIABILIDADE OU
 * ADEQUAÃÃO A UMA FINALIDADE ESPECÃFICA. Consulte a LicenÃ§a PÃºblica Geral
 * do GNU para mais detalhes.
 *
 * VocÃª deve ter recebido uma cÃ³pia da LicenÃ§a PÃºblica Geral do GNU junto
 * com este programa; se nÃ£o, escreva para a Free Software Foundation, Inc., no
 * endereÃ§o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 *
 * @author    Prefeitura Municipal de ItajaÃ­ <ctima@itajai.sc.gov.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Arquivo disponÃ­vel desde a versÃ£o 1.0.0
 *
 * @version   $Id$
 */

require_once 'Source/Base.php';
require_once 'Source/Cadastro.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';

/**
 * clsIndexBase class.
 *
 * @author    Prefeitura Municipal de ItajaÃ­ <ctima@itajai.sc.gov.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Classe disponÃ­vel desde a versÃ£o 1.0.0
 *
 * @version   @@package_version@@
 */
class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' i-Educar - S&eacute;rie');
        $this->processoAp = '586';
    }
}

/**
 * indice class.
 *
 * @author    Prefeitura Municipal de ItajaÃ­ <ctima@itajai.sc.gov.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Classe disponÃ­vel desde a versÃ£o 1.0.0
 *
 * @version   @@package_version@@
 */
class indice extends clsCadastro
{
    public $pessoa_logada;
    public $cod_turma;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_turma=$_GET['cod_turma'];

        if (is_numeric($this->cod_turma)) {
            $matriculasTurma = new MatriculaTurma();
            $matriculasTurma = $matriculasTurma->listaPorSequencial($this->cod_turma);

            if ($matriculasTurma) {
                foreach ($matriculasTurma as $campo => $val) {
                    $this->campoTexto('nome_aluno_' . $val['ref_cod_matricula'], '', $val['nome'], 60, false, false, false, true, '', '', '', '', true);
                    $matricula = $val['ref_cod_matricula'];
                    $this->campoTexto("sequencia[$matricula]", '', ($val['sequencial_fechamento']), 5, null, false, false, false);
                }
                $retorno = 'Editar';
            }
        }

        $this->url_cancelar = "educar_turma_det.php?cod_turma={$this->cod_turma}";

        $this->breadcrumb('Sequência manual dos alunos na turma', [
        url('Intranet/educar_index.php') => 'Escola',
    ]);

        $this->nome_url_cancelar = 'Cancelar';

        return $retorno;
    }

    public function Gerar()
    {
        return true;
    }

    public function Novo()
    {
        return false;
    }

    public function Editar()
    {
        $cod_turma = $_GET['cod_turma'];
        foreach ($this->sequencia as $matricula => $sequencial) {
            $retorno = Database::fetchPreparedQuery(
          'UPDATE pmieducar.matricula_turma
                                                                   SET sequencial_fechamento = $1
                                                                 WHERE ref_cod_matricula = $2
                                                                   AND ref_cod_turma = $3',
          ['params' => [$sequencial, $matricula, $cod_turma]]
      );
        }
        $this->simpleRedirect("educar_turma_det.php?cod_turma={$cod_turma}");
    }

    public function Excluir()
    {
        return false;
    }
}

// Instancia objeto de pÃ¡gina
$pagina = new clsIndexBase();

// Instancia objeto de conteÃºdo
$miolo = new indice();

// Atribui o conteÃºdo Ã Â  pÃ¡gina
$pagina->addForm($miolo);

// Gera o cÃ³digo HTML
$pagina->MakeAll();

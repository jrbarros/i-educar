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
 * @author    Lucas Schmoeller da Silva <lucas@portabilis.com.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   Module
 *
 * @since     07/2013
 *
 * @version   $Id$
 */

require_once 'Source/Base.php';
require_once 'Source/Detalhe.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';
require_once 'Source/modules/Motorista.php';

require_once 'Portabilis/Date/AppDateUtils.php';
require_once 'Portabilis/View/Helper/Application.php';

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
        $this->SetTitulo($this->_instituicao . ' i-Educar - Motoristas');
        $this->processoAp = 21236;
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

    public function Gerar()
    {
        // Verificação de permissão para cadastro.
        $this->obj_permissao = new Permissoes();

        $this->nivel_usuario = $this->obj_permissao->nivel_acesso($this->pessoa_logada);

        $this->titulo = 'Motorista - Detalhe';

        $cod_motorista = $_GET['cod_motorista'];

        $tmp_obj = new clsModulesMotorista($cod_motorista);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('transporte_motorista_lst.php');
        }

        $this->addDetalhe(['Código do motorista', $cod_motorista]);
        $this->addDetalhe(['Nome', $registro['nome_motorista'].'<br/> <a target=\'_blank\' style=\' text-decoration: underline;\' href=\'atendidos_det.php?cod_pessoa='.$registro['ref_idpes'].'\'>Visualizar Pessoa</a>']);
        $this->addDetalhe(['CNH', $registro['cnh']]);
        $this->addDetalhe(['Categoria', $registro['tipo_cnh']]);
        if (trim($registro['dt_habilitacao'])!='') {
            $this->addDetalhe(['Data da habilitação', Utils::pgSQLToBr($registro['dt_habilitacao']) ]);
        }
        if (trim($registro['vencimento_cnh'])!='') {
            $this->addDetalhe(['Vencimento da habilitação', Utils::pgSQLToBr($registro['vencimento_cnh']) ]);
        }

        $this->addDetalhe(['Observa&ccedil;&atilde;o', $registro['observacao']]);
        $this->url_cancelar = 'transporte_motorista_lst.php';

        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(21236, $this->pessoa_logada, 7, null, true)) {
            $this->url_novo = '../Module/TransporteEscolar/Motorista';
            $this->url_editar = "../Module/TransporteEscolar/motorista?id={$cod_motorista}";
        }

        $this->largura = '100%';

        $this->breadcrumb('Detalhe do motorista', [
        url('Intranet/educar_transporte_escolar_index.php') => 'Transporte escolar',
    ]);
    }
}

// Instancia o objeto da página
$pagina = new clsIndexBase();

// Instancia o objeto de conteúdo
$miolo = new indice();

// Passa o conteúdo para a página
$pagina->addForm($miolo);

// Gera o HTML
$pagina->MakeAll();

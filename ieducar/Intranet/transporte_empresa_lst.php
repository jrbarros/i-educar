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
require_once 'include/Base.php';
require_once 'include/clsListagem.inc.php';
require_once 'include/Banco.inc.php';
require_once 'include/pmieducar/geral.inc.php';
require_once 'include/modules/EmpresaTransporteEscolar.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Empresas");
        $this->processoAp = '21235';
    }
}

class indice extends clsListagem
{

    /**
     * Referencia pega da session para o idpes do usuario atual
     *
     * @var int
     */
    public $pessoa_logada;

    /**
     * Titulo no topo da pagina
     *
     * @var int
     */
    public $titulo;

    /**
     * Quantidade de registros a ser apresentada em cada pagina
     *
     * @var int
     */
    public $limite;

    /**
     * Inicio dos registros a serem exibidos (limit)
     *
     * @var int
     */
    public $offset;

    public $cod_empresa;
    public $nome_empresa;
    public $nome_responsavel;

    public function Gerar()
    {
        $this->titulo = 'Empresas de transporte escolar - Listagem';

        foreach ($_GET as $var => $val) { // passa todos os valores obtidos no GET para atributos do objeto
            $this->$var = ($val === '') ? null: $val;
        }

        $this->campoNumero('cod_empresa', 'C&oacute;digo da empresa', $this->cod_empresa, 20, 255, false);
        $this->campoTexto('nome_empresa', 'Nome fantasia', $this->nome_empresa, 50, 255, false);
        $this->campoTexto('nome_responsavel', 'Nome do responsável', $this->nome_responsavel, 50, 255, false);

        $obj_permissoes = new Permissoes();

        $nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);

        $this->addCabecalhos([
            'C&oacute;digo da empresa',
            'Nome fantasia',
            'Nome do respons&aacute;vel',
            'Telefone'
        ]);

        // Paginador
        $this->limite = 20;
        $this->offset = ($_GET["pagina_{$this->nome}"]) ? $_GET["pagina_{$this->nome}"]*$this->limite-$this->limite: 0;

        $obj_empresa = new clsModulesEmpresaTransporteEscolar();
        $obj_empresa->setLimite($this->limite, $this->offset);

        $empresas = $obj_empresa->lista($this->cod_empresa, null, null, $this->nome_empresa, $this->nome_responsavel);
        $total = $empresas->_total;

        foreach ($empresas as $registro) {
            $this->addLinhas([
                "<a href=\"transporte_empresa_det.php?cod_empresa={$registro['cod_empresa_transporte_escolar']}\">{$registro['cod_empresa_transporte_escolar']}</a>",
                "<a href=\"transporte_empresa_det.php?cod_empresa={$registro['cod_empresa_transporte_escolar']}\">{$registro['nome_empresa']}</a>",
                "<a href=\"transporte_empresa_det.php?cod_empresa={$registro['cod_empresa_transporte_escolar']}\">{$registro['nome_responsavel']}</a>",
                "<a href=\"transporte_empresa_det.php?cod_empresa={$registro['cod_empresa_transporte_escolar']}\">{$registro['telefone']}</a>"
            ]);
        }

        $this->addPaginador2('transporte_empresa_lst.php', $total, $_GET, $this->nome, $this->limite);

        //**
        $this->largura = '100%';

        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(21235, $this->pessoa_logada, 7, null, true)) {
            $this->acao = 'go("../module/TransporteEscolar/Empresa")';
            $this->nome_acao = 'Novo';
        }

        $this->breadcrumb('Listagem de empresas de transporte', [
        url('Intranet/educar_transporte_escolar_index.php') => 'Transporte escolar',
    ]);
    }
}
// cria uma extensao da classe base
$pagina = new clsIndexBase();
// cria o conteudo
$miolo = new indice();
// adiciona o conteudo na Base
$pagina->addForm($miolo);
// gera o html
$pagina->MakeAll();

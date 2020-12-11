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
require_once 'Source/modules/Veiculo.php';

require_once 'Portabilis/Date/Utils.php';
require_once 'Portabilis/View/Helper/Application.php';

/**
 * clsIndexBase class.21239
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
        $this->SetTitulo($this->_instituicao . ' i-Educar - Veiculos');
        $this->processoAp = 21237;
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

        $this->titulo = 'Veiculo - Detalhe';

        $cod_veiculo = $_GET['cod_veiculo'];

        $tmp_obj = new clsModulesVeiculo($cod_veiculo);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('transporte_veiculo_lst.php');
        }

        $this->addDetalhe(['Código do veículo', $cod_veiculo]);
        $this->addDetalhe(['Descrição', $registro['descricao']]);
        $this->addDetalhe(['Placa', $registro['placa']]);
        $this->addDetalhe(['Renavam', $registro['renavam']]);
        $this->addDetalhe(['Chassi', $registro['chassi']]);
        $this->addDetalhe(['Marca', $registro['marca']]);
        $this->addDetalhe(['Ano fabricação', $registro['ano_fabricacao']]);
        $this->addDetalhe(['Ano modelo', $registro['ano_modelo']]);
        $this->addDetalhe(['Limite de passageiros', $registro['passageiros']]);
        $malha ='';
        switch ($registro['malha']) {
      case 'A':
        $malha = 'Aquática/Embarcação';
        break;
      case 'F':
        $malha = 'Ferroviária';
        break;
      case 'R':
        $malha = 'Rodoviária';
        break;
    }
        $this->addDetalhe(['Malha', $malha]);
        $this->addDetalhe(['Categoria', $registro['descricao_tipo']]);
        $this->addDetalhe(['Exclusivo para transporte escolar', ($registro['exclusivo_transporte_escolar'] == 'S' ? 'Sim' : 'Não')]);
        $this->addDetalhe(['Adaptado para pessoas com necessidades especiais', ($registro['adaptado_necessidades_especiais'] == 'S' ? 'Sim' : 'Não')]);
        $this->addDetalhe(['Ativo', ($registro['ativo'] == 'S' ? 'Sim' : 'Não')]);
        if ($registro['ativo']=='N') {
            $this->addDetalhe(['Descrição inativo', $registro['descricao_inativo']]);
        }
        $this->addDetalhe(['Empresa', $registro['nome_empresa']]);
        $this->addDetalhe(['Motorista responsável', $registro['nome_motorista']]);
        $this->addDetalhe(['Observa&ccedil;&atilde;o', $registro['observacao']]);
        $this->url_cancelar = 'transporte_veiculo_lst.php';

        $this->largura = '100%';

        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(21237, $this->pessoa_logada, 7, null, true)) {
            $this->url_novo = '../Module/TransporteEscolar/Veiculo';
            $this->url_editar = "../Module/TransporteEscolar/Veiculo?id={$cod_veiculo}";
        }

        $this->breadcrumb('Detalhe do ve&iacute;culo', [
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

<?php

/*
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
 */

/**
 * Listagem de níveis de categoria.
 *
 * @author   Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 * @license  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt  CC GNU GPL
 *
 * @package  Core
 *
 * @since    Arquivo disponível desde a versão 1.0.0
 *
 * @version  $Id$
 */

require_once 'Source/Base.php';
require_once 'Source/Listagem.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . 'Categorias ou níveis do servidor');
        $this->processoAp = '829';
    }
}

class indice extends clsListagem
{
    /**
     * Referencia pega da session para o idpes do usuario atual
     *
     * @var int
     */
    public $__pessoa_logada;

    /**
     * Titulo no topo da pagina
     *
     * @var int
     */
    public $__titulo;

    /**
     * Quantidade de registros a ser apresentada em cada pagina
     *
     * @var int
     */
    public $__limite;

    /**
     * Inicio dos registros a serem exibidos (limit)
     *
     * @var int
     */
    public $__offset;

    public $cod_categoria_nivel;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_categoria_nivel;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->__pessoa_logada = $this->pessoa_logada;

        $this->__titulo = 'Categoria Nivel - Listagem';

        // passa todos os valores obtidos no GET para atributos do objeto
        foreach ($_GET as $var => $val) {
            $this->$var = ($val === '') ? null : $val;
        }

        $this->addBanner('imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg', 'Intranet');

        $this->addCabecalhos([
      'Nome Categoria Nivel'
    ]);

        // Filtros
        $this->campoTexto(
        'nm_categoria_nivel',
        'Nome Categoria Nivel',
        $this->nm_categoria_nivel,
        30,
        255,
        false
    );

        // Paginador
        $this->__limite = 20;
        $this->__offset = ($_GET['pagina_' . $this->nome]) ?
      $_GET['pagina_' . $this->nome] * $this->__limite-$this->__limite : 0;

        $obj_categoria_nivel = new CategoriaNivel();
        $obj_categoria_nivel->setOrderby('nm_categoria_nivel ASC');
        $obj_categoria_nivel->setLimite($this->__limite, $this->__offset);

        $lista = $obj_categoria_nivel->lista(
        null,
        null,
        $this->nm_categoria_nivel,
        null,
        null,
        null,
        null,
        null,
        1
    );

        $total = $obj_categoria_nivel->_total;

        // Monta a lista
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                // muda os campos data
                $registro['data_cadastro_time'] = strtotime(substr($registro['data_cadastro'], 0, 16));
                $registro['data_cadastro_br']   = date('d/m/Y H:i', $registro['data_cadastro_time']);

                $registro['data_exclusao_time'] = strtotime(substr($registro['data_exclusao'], 0, 16));
                $registro['data_exclusao_br']   = date('d/m/Y H:i', $registro['data_exclusao_time']);

                $obj_ref_usuario_cad = new Usuario($registro['ref_usuario_cad']);
                $det_ref_usuario_cad = $obj_ref_usuario_cad->detalhe();
                $registro['ref_usuario_cad'] = $det_ref_usuario_cad['data_cadastro'];

                $obj_ref_usuario_exc = new Usuario($registro['ref_usuario_exc']);
                $det_ref_usuario_exc = $obj_ref_usuario_exc->detalhe();
                $registro['ref_usuario_exc'] = $det_ref_usuario_exc['data_cadastro'];

                $this->addLinhas([
          sprintf(
              '<a href="educar_categoria_nivel_det.php?cod_categoria_nivel=%s">%s</a>',
              $registro['cod_categoria_nivel'],
              $registro['nm_categoria_nivel']
          )
        ]);
            }
        }

        $this->addPaginador2(
        'educar_categoria_nivel_lst.php',
        $total,
        $_GET,
        $this->nome,
        $this->__limite
    );

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(
        829,
        $this->__pessoa_logada,
        3,
        null,
        true
    )) {
            $this->acao = 'go("educar_categoria_nivel_cad.php")';
            $this->nome_acao = 'Novo';
        }

        $this->largura = '100%';

        $this->breadcrumb('Categorias ou níveis do servidor', [
        url('Intranet/educar_servidores_index.php') => 'Servidores',
    ]);
    }
}

// Instancia a classe da página
$pagina = new clsIndexBase();

// Instancia o conteúdo
$miolo = new indice();

// Passa o conteúdo para a classe da página
$pagina->addForm($miolo);

// Imprime o HTML
$pagina->MakeAll();

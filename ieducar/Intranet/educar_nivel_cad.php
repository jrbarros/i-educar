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
 * Cadastro de nível de categoria.
 *
 * @author   Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 * @license  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt  CC GNU GPL
 *
 * @package  Core
 *
 * @since    Arquivo disponível desde a versão 1.0.0
 *
 * @todo     Aparentemente este arquivo apenas redireciona o usuário (linha 80)
 *
 * @version  $Id$
 */

require_once 'Source/Base.php';
require_once 'Source/Cadastro.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . 'Servidores - Nível');
        $this->processoAp = '829';
    }
}

class indice extends clsCadastro
{
    /**
     * Referencia pega da session para o idpes do usuario atual
     *
     * @var int
     */
    public $pessoa_logada;

    public $cod_nivel;
    public $ref_cod_categoria_nivel;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $ref_cod_nivel_anterior;
    public $nm_nivel;
    public $salario_base;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $nm_categoria;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->ref_cod_categoria_nivel = $_GET['cod_categoria'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(829, $this->pessoa_logada, 3, "educar_categoria_nivel_det.php?cod_categoria_nivel={$this->cod_nivel}", true);

        if (is_numeric($this->ref_cod_categoria_nivel)) {
            $obj = new CategoriaNivel($this->ref_cod_categoria_nivel);
            $registro  = $obj->detalhe();
            if ($registro) {
                $this->nm_categoria = $registro['nm_categoria_nivel'];
                $obj_permissoes = new Permissoes();
                if ($obj_permissoes->permissao_excluir(829, $this->pessoa_logada, 3, null, true)) {
                    $this->fexcluir = true;
                }

                $obj_niveis = new Nivel();
                $obj_niveis->setOrderby('cod_nivel');
                $lst_niveis = $obj_niveis->lista(nul, $this->ref_cod_categoria_nivel, null, null, null, null, null, null, null, null, null, 1);

                if ($lst_niveis) {
                    foreach ($lst_niveis as $id => $nivel) {
                        $id++;
                        $nivel['salario_base'] = number_format($nivel['salario_base'], 2, ',', '.');
                        $this->cod_nivel[] = [$nivel['nm_nivel'],$nivel['salario_base'],$id,$nivel['cod_nivel']];
                    }
                } else {
                    $this->cod_nivel[] = ['','','1',''];
                }

                $retorno = 'Editar';
            }
        } else {
            $this->simpleRedirect('educar_categoria_nivel_lst.php');
        }

        $this->url_cancelar = "educar_categoria_nivel_det.php?cod_categoria_nivel={$this->cod_nivel}";
        $this->nome_url_cancelar = 'Cancelar';

        $this->breadcrumb('Adicionar níveis à categoria', [
            url('Intranet/educar_servidores_index.php') => 'Servidores',
        ]);

        return $retorno;
    }

    public function Gerar()
    {
        $this->campoOculto('ref_cod_categoria_nivel', $this->ref_cod_categoria_nivel);

        $this->campoRotulo('nm_categoria', 'Categoria', $this->nm_categoria);

        $this->campoTabelaInicio('tab01', 'N&iacute;veis', ['Nome N&iacute;vel','Sal&aacute;rio','Ordem'], $this->cod_nivel);

        $this->campoTexto('nm_nivel', 'Nome N&iacute;vel', '', 30, 100, true);
        $this->campoMonetario('salario_base', 'Salario Base', $this->salario_base, 10, 8, true);
        $this->campoNumero('nr_nivel', 'Ordem', '1', 5, 5, false, false, false, false, false, false, true);
        $this->campoOculto('cod_nivel', '');

        $this->campoTabelaFim();
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(829, $this->pessoa_logada, 3, "educar_categoria_nivel_det.php?cod_categoria_nivel={$this->cod_nivel}", true);

        $obj = new Nivel($this->cod_nivel, $this->ref_cod_categoria_nivel, $this->pessoa_logada, $this->pessoa_logada, $this->ref_cod_nivel_anterior, $this->nm_nivel, $this->salario_base, $this->data_cadastro, $this->data_exclusao, $this->ativo);

        $obj->desativaTodos();

        if ($this->nm_nivel) {
            $nivel_anterior = null;
            $niveis = [];
            foreach ($this->nm_nivel as $id => $nm_nivel) {
                $obj_nivel = new Nivel($this->cod_nivel[$id], $this->ref_cod_categoria_nivel, $this->pessoa_logada, $this->pessoa_logada, $nivel_anterior, $nm_nivel, str_replace(',', '.', str_replace('.', '', $this->salario_base[$id])), null, null, 1);
                if ($obj_nivel->existe()) {
                    $obj_nivel->edita();
                    $nivel_anterior = $this->cod_nivel[$id];
                } else {
                    $nivel_anterior = $obj_nivel->cadastra();
                }

                $niveis[] = $nivel_anterior;
            }

            /**
             * desativa todos os subniveis dos niveis que nao se
             * encontram ativos
             */

            if ($niveis) {
                $obj = new Subnivel(null, $this->pessoa_logada, $this->pessoa_logada, null, $this->ref_cod_nivel);

                $obj->desativaTodos($niveis);
            }

            $this->simpleRedirect("educar_categoria_nivel_det.php?cod_categoria_nivel={$this->ref_cod_categoria_nivel}");
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
        if (!$this->Novo()) {
            return false;
        }
    }

    public function Excluir()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_excluir(829, $this->pessoa_logada, 3, 'educar_nivel_lst.php', true);

        $obj = new Nivel($this->cod_nivel, $this->ref_cod_categoria_nivel, $this->pessoa_logada, $this->pessoa_logada, $this->ref_cod_nivel_anterior, $this->nm_nivel, $this->salario_base, $this->data_cadastro, $this->data_exclusao, 0);
        $excluiu = $obj->desativaTodos();
        if ($excluiu) {
            $this->mensagem .= 'Exclus&atilde;o efetuada com sucesso.<br>';
            $this->simpleRedirect("educar_categoria_nivel_det.php?cod_categoria_nivel={$this->ref_cod_categoria_nivel}");
        }

        $this->mensagem = 'Exclus&atilde;o n&atilde;o realizada.<br>';

        return false;
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
?>

<script type="text/javascript">

    function setOrdem(id)
    {
        document.getElementById('nr_nivel['+(id)+']').value = (id+1);
    }

    tab_add_1.afterAddRow = function() {
        setOrdem(this.id-1);
    }

    tab_add_1.afterRemoveRow = function() {
        reordena();
    }

    function reordena()
    {
        for(var ct=0;ct < tab_add_1.getId();ct++)
        {
            setOrdem(ct);
        }
    }
</script>

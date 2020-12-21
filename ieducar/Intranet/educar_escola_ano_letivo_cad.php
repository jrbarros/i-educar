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
require_once 'Source/Cadastro.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';

/**
 * clsIndexBase class.
 *
 * @author    Prefeitura Municipal de Itajaí
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
        $this->SetTitulo($this->_instituicao . ' i-Educar - Escola Ano Letivo');
        $this->processoAp = 561;
    }
}

/**
 * indice class.
 *
 * @author    Prefeitura Municipal de Itajaí
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

    public $ref_cod_escola;
    public $ano;
    public $ref_usuario_cad;
    public $ref_usuario_exc;
    public $andamento;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->ano            = $_GET['ano'];
        $this->ref_cod_escola = $_GET['cod_escola'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(
        561,
        $this->pessoa_logada,
        7,
        'educar_escola_lst.php'
    );

        $this->nome_url_sucesso  = 'Continuar';
        $this->url_cancelar      = 'educar_escola_det.php?cod_escola=' . $this->ref_cod_escola;

        $this->breadcrumb('Definição do ano letivo', [
        url('Intranet/educar_index.php') => 'Escola',
    ]);

        $this->nome_url_cancelar = 'Cancelar';

        return $retorno;
    }

    public function Gerar()
    {
        // Primary keys
        $this->campoOculto('ref_cod_escola', $this->ref_cod_escola);
        $this->campoOculto('ano', $this->ano);

        $obj_anos = new EscolaAnoLetivo();
        $lista_ano = $obj_anos->lista(
        $this->ref_cod_escola,
        null,
        null,
        null,
        2,
        null,
        null,
        null,
        null,
        1
    );

        $ano_array = [];

        if ($lista_ano) {
            foreach ($lista_ano as $ano) {
                $ano_array[$ano['ano']] = $ano['ano'];
            }
        }

        $ano_atual = date('Y') - 5;

        // Foreign keys
        $opcoes = ['' => 'Selecione'];
        $lim = 10;

        for ($i = 0; $i < $lim; $i++) {
            $ano = $ano_atual + $i;

            if (! key_exists($ano, $ano_array)) {
                $opcoes[$ano] = $ano;
            } else {
                $lim++;
            }
        }

        $this->campoLista('ano', 'Ano', $opcoes, $this->ano);
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(
        561,
        $this->pessoa_logada,
        7,
        'educar_escola_lst.php'
    );

        $url = sprintf(
        'educar_ano_letivo_modulo_cad.php?ref_cod_escola=%s&ano=%s',
        $this->ref_cod_escola,
        $this->ano
    );

        $this->simpleRedirect($url);
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

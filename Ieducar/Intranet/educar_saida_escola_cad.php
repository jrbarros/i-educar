<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  *                                       *
  *  @author Prefeitura Municipal de Itajaí                 *
  *  @updated 29/03/2007                           *
  *   Pacote: i-PLB Software Público Livre e Brasileiro           *
  *                                     *
  *  Copyright (C) 2006  PMI - Prefeitura Municipal de Itajaí       *
  *            ctima@itajai.sc.gov.br                 *
  *                                     *
  *  Este  programa  é  software livre, você pode redistribuí-lo e/ou   *
  *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme   *
  *  publicada pela Free  Software  Foundation,  tanto  a versão 2 da   *
  *  Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.   *
  *                                     *
  *  Este programa  é distribuído na expectativa de ser útil, mas SEM   *
  *  QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-   *
  *  ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-   *
  *  sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.   *
  *                                     *
  *  Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU   *
  *  junto  com  este  programa. Se não, escreva para a Free Software   *
  *  Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA   *
  *  02111-1307, USA.                           *
  *                                     *
  * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
require_once('Source/Base.php');
require_once('Source/Cadastro.inc.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');
require_once 'lib/Portabilis/Date/AppDateUtils.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Saída da escola");
        $this->processoAp = '578';
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

    public $ref_cod_matricula;
    public $ref_cod_aluno;
    public $escola;
    public $data_saida_escola;
    public $nm_aluno;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->ref_cod_matricula=$_GET['ref_cod_matricula'];
        $this->ref_cod_aluno=$_GET['ref_cod_aluno'];
        $this->escola=$_GET['escola'];

        $cancela=$_GET['cancela'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(578, $this->pessoa_logada, 7, "educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}");

        $obj_matricula = new Matricula($this->cod_matricula, null, null, null, $this->pessoa_logada, null, null);

        $det_matricula = $obj_matricula->detalhe();

        $this->url_cancelar = "educar_matricula_det.php?cod_matricula={$this->ref_cod_matricula}";

        $this->breadcrumb('Registro de saída da escola', [
        url('Intranet/educar_index.php') => 'Escola',
    ]);

        $this->nome_url_cancelar = 'Cancelar';

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('ref_cod_aluno', $this->ref_cod_aluno);
        $this->campoOculto('ref_cod_matricula', $this->ref_cod_matricula);

        $obj_aluno = new Aluno();
        $lst_aluno = $obj_aluno->lista($this->ref_cod_aluno, null, null, null, null, null, null, null, null, null, 1);
        if (is_array($lst_aluno)) {
            $det_aluno = array_shift($lst_aluno);
            $this->nm_aluno = $det_aluno['nome_aluno'];
            $this->campoTexto('nm_aluno', 'Aluno', $this->nm_aluno, 40, 255, false, false, false, '', '', '', '', true);
        }

        $this->campoTexto('nm_escola', 'Escola', " $this->escola", 40, 255, false, false, false, '', '', '', '', true);

        $this->inputsHelper()->date('data_saida_escola', ['label' => 'Data de saída da escola', 'placeholder' => 'dd/mm/yyyy', 'value' => date('d/m/Y')]);

        // text
        $this->campoMemo('observacao', 'Observa&ccedil;&atilde;o', $this->observacao, 60, 5, false);
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(578, $this->pessoa_logada, 7, "educar_matricula_det.php?cod_matricula={$this->ref_cod_matricula}");

        $tamanhoObs = strlen($this->observacao);
        if ($tamanhoObs > 300) {
            $this->mensagem = 'O campo observação deve conter no máximo 300 caracteres.<br>';

            return false;
        }

        $obj_matricula = new Matricula($this->ref_cod_matricula, null, null, null, $this->pessoa_logada);

        $det_matricula = $obj_matricula->detalhe();

        if ($obj_matricula->edita()) {
            if ($obj_matricula->setSaidaEscola($this->observacao, Utils::brToPgSQL($this->data_saida_escola))) {
                $this->mensagem .= 'Saída da escola realizada com sucesso.<br>';
                $this->simpleRedirect("educar_matricula_det.php?cod_matricula={$this->ref_cod_matricula}");
            }

            $this->mensagem = 'Observação não pode ser salva.<br>';

            return false;
        }
        $this->mensagem = 'Saída da escola não pode ser realizada.<br>';

        return false;
    }

    public function Excluir()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_excluir(578, $this->pessoa_logada, 7, "educar_matricula_det.php?cod_matricula={$this->ref_cod_matricula}");
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

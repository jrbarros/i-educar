<?php

use App\Models\State;

require_once('include/Base.php');
require_once('include/clsCadastro.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Editora");
        $this->processoAp = '595';
        $this->renderMenu = false;
        $this->renderMenuSuspenso = false;
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

    public $cod_acervo_editora;
    public $ref_usuario_cad;
    public $ref_usuario_exc;
    public $ref_idtlog;
    public $ref_sigla_uf;
    public $nm_editora;
    public $cep;
    public $cidade;
    public $bairro;
    public $logradouro;
    public $numero;
    public $telefone;
    public $ddd_telefone;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_biblioteca;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(595, $this->pessoa_logada, 11, 'educar_acervo_editora_lst.php');

        return $retorno;
    }

    public function Gerar()
    {
        echo '<script>window.onload=function(){parent.EscondeDiv(\'LoadImprimir\')}</script>';
        $this->campoOculto('ref_cod_biblioteca', $this->ref_cod_biblioteca);
        $this->campoTexto('nm_editora', 'Editora', $this->nm_editora, 30, 255, true);

        // foreign keys
        if ($this->cod_acervo_editora) {
            $this->cep = int2CEP($this->cep);
        }

        $this->campoCep('cep', 'CEP', $this->cep, false);

        $opcoes = [ '' => 'Selecione' ] + State::getListKeyAbbreviation()->toArray();

        $this->campoLista('ref_sigla_uf', 'Estado', $opcoes, $this->ref_sigla_uf, '', false, '', '', false, false);

        $this->campoTexto('cidade', 'Cidade', $this->cidade, 30, 60, false);
        $this->campoTexto('bairro', 'Bairro', $this->bairro, 30, 60, false);

        $opcoes = [ '' => 'Selecione' ];

        $this->campoLista('ref_idtlog', 'Tipo Logradouro', $opcoes, $this->ref_idtlog, '', false, '', '', false, false);

        $this->campoTexto('logradouro', 'Logradouro', $this->logradouro, 30, 255, false);

        $this->campoNumero('numero', 'N&uacute;mero', $this->numero, 6, 6);

        $this->campoNumero('ddd_telefone', 'DDD Telefone', $this->ddd_telefone, 2, 2, false);
        $this->campoNumero('telefone', 'Telefone', $this->telefone, 10, 15, false);

        // data
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(595, $this->pessoa_logada, 11, 'educar_acervo_editora_lst.php');

        $this->cep = idFederal2int($this->cep);

        $obj = new clsPmieducarAcervoEditora(null, $this->pessoa_logada, null, $this->ref_idtlog, $this->ref_sigla_uf, $this->nm_editora, $this->cep, $this->cidade, $this->bairro, $this->logradouro, $this->numero, $this->telefone, $this->ddd_telefone, null, null, 1, $this->ref_cod_biblioteca);
        $cadastrou = $obj->cadastra();
        if ($cadastrou) {
            $this->mensagem .= 'Cadastro efetuado com sucesso.<br>';
            echo "<script>
                    parent.document.getElementById('editora').value = '$cadastrou';
                    parent.document.getElementById('tipoacao').value = '';
                    parent.document.getElementById('ref_cod_acervo_editora').disabled = false;
                    parent.document.getElementById('formcadastro').submit();
                 </script>";
            die();

            return true;
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
    }

    public function Excluir()
    {
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
<script>
    document.getElementById('ref_cod_biblioteca').value = parent.document.getElementById('ref_cod_biblioteca').value;
</script>

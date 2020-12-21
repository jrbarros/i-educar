<?php

use App\Models\State;

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Editora");
        $this->processoAp = '595';
    }
}

class indice extends clsDetalhe
{
    /**
     * Titulo no topo da pagina
     *
     * @var int
     */
    public $titulo;

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

    public function Gerar()
    {
        $this->titulo = 'Editora - Detalhe';

        $this->cod_acervo_editora=$_GET['cod_acervo_editora'];

        $tmp_obj = new clsPmieducarAcervoEditora($this->cod_acervo_editora);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_acervo_editora_lst.php');
        }

        $registro['ref_sigla_uf'] = State::getNameByAbbreviation($registro['ref_sigla_uf']);

        if ($registro['nm_editora']) {
            $this->addDetalhe([ 'Editora', "{$registro['nm_editora']}"]);
        }
        if ($registro['cep']) {
            $registro['cep'] = int2CEP($registro['cep']);
            $this->addDetalhe([ 'CEP', "{$registro['cep']}"]);
        }
        if ($registro['ref_sigla_uf']) {
            $this->addDetalhe([ 'Estado', "{$registro['ref_sigla_uf']}"]);
        }
        if ($registro['cidade']) {
            $this->addDetalhe([ 'Cidade', "{$registro['cidade']}"]);
        }
        if ($registro['bairro']) {
            $this->addDetalhe([ 'Bairro', "{$registro['bairro']}"]);
        }
        if ($registro['ref_idtlog']) {
            $this->addDetalhe([ 'Tipo Logradouro', "{$registro['ref_idtlog']}"]);
        }
        if ($registro['logradouro']) {
            $this->addDetalhe([ 'Logradouro', "{$registro['logradouro']}"]);
        }
        if ($registro['numero']) {
            $this->addDetalhe([ 'N&uacute;mero', "{$registro['numero']}"]);
        }
        if ($registro['ddd_telefone']) {
            $this->addDetalhe([ 'DDD Telefone', "{$registro['ddd_telefone']}"]);
        }
        if ($registro['telefone']) {
            $this->addDetalhe([ 'Telefone', "{$registro['telefone']}"]);
        }

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(595, $this->pessoa_logada, 11)) {
            $this->url_novo = 'educar_acervo_editora_cad.php';
            $this->url_editar = "educar_acervo_editora_cad.php?cod_acervo_editora={$registro['cod_acervo_editora']}";
        }

        $this->url_cancelar = 'educar_acervo_editora_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe da editora', [
            url('Intranet/educar_biblioteca_index.php') => 'Biblioteca',
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

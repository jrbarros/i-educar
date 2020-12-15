<?php

use App\Models\State;
use iEducarLegacy\Intranet\Source\Cadastro;
use iEducarLegacy\Intranet\Source\Pessoa\Documento;
use iEducarLegacy\Intranet\Source\Pessoa\OrgaoEmissorRg;

$desvio_diretorio = '';
require_once 'Source/Base.php';
require_once 'Source/Cadastro.php';
require_once '';

class clsIndex extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} Adicionar Documentos");
        $this->processoAp = '0'; //nao alterar, paginas com ap diferentes chamam essa
        $this->renderMenu = false;
    }
}

class indice extends Cadastro
{
    public $idpes;
    public $rg;
    public $data_exp_rg;
    public $sigla_uf_exp_rg;
    public $tipo_cert_civil;
    public $num_termo;
    public $num_livro;
    public $num_folha;
    public $data_emissao_cert_civil;
    public $sigla_uf_cert_civil;
    public $cartorio_cert_civil;
    public $num_cart_trabalho;
    public $serie_cart_trabalho;
    public $data_emissao_cart_trabalho;
    public $sigla_uf_cart_trabalho;
    public $num_tit_eleitor;
    public $zona_tit_eleitor;
    public $secao_tit_eleitor;
    public $idorg_exp_rg;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->idpes = ($_GET['id_pessoa']) ? $_GET['id_pessoa'] : $this->pessoa_logada;
        $ObjDocumento = new Documento($this->idpes);
        $detalheDocumento = $ObjDocumento->detalhe();

        $this->rg = $detalheDocumento['rg'];
        if ($detalheDocumento['data_exp_rg']) {
            $this->data_exp_rg = date('d/m/Y', strtotime(substr($detalheDocumento['data_exp_rg'], 0, 19)));
        }
        $this->sigla_uf_exp_rg = $detalheDocumento['sigla_uf_exp_rg'];
        $this->tipo_cert_civil = $detalheDocumento['tipo_cert_civil'];
        $this->num_termo = $detalheDocumento['num_termo'];
        $this->num_livro = $detalheDocumento['num_livro'];
        $this->num_folha = $detalheDocumento['num_folha'];
        if ($detalheDocumento['data_emissao_cert_civil']) {
            $this->data_emissao_cert_civil = date('d/m/Y', strtotime(substr($detalheDocumento['data_emissao_cert_civil'], 0, 19)));
        }
        $this->sigla_uf_cert_civil = $detalheDocumento['sigla_uf_cert_civil'];

        $this->cartorio_cert_civil = $detalheDocumento['cartorio_cert_civil'];
        $this->num_cart_trabalho = $detalheDocumento['num_cart_trabalho'];
        $this->serie_cart_trabalho = $detalheDocumento['serie_cart_trabalho'];
        if ($detalheDocumento['data_emissao_cart_trabalho']) {
            $this->data_emissao_cart_trabalho = date('d/m/Y', strtotime(substr($detalheDocumento['data_emissao_cart_trabalho'], 0, 19)));
        }
        $this->sigla_uf_cart_trabalho = $detalheDocumento['sigla_uf_cart_trabalho'];
        $this->num_tit_eleitor = $detalheDocumento['num_tit_eleitor'];
        $this->zona_tit_eleitor = $detalheDocumento['zona_tit_eleitor'];
        $this->secao_tit_eleitor = $detalheDocumento['secao_tit_eleitor'];
        $this->idorg_exp_rg = $detalheDocumento['idorg_exp_rg'];
        $this->certidao_nascimento = $detalheDocumento['certidao_nascimento'];

        $ObjDocumento = new Documento($this->idpes);

        if ($ObjDocumento->detalhe()) {
            $retorno = 'Editar';
        } else {
            $retorno = 'Novo';
        }

        return $retorno;
    }

    public function Gerar()
    {
        $listaEstado = ['0' => 'Selecione'] + State::getListKeyAbbreviation()->toArray();

        $objOrgaoEmissorRg = new OrgaoEmissorRg();
        $listaOrgaoEmissorRg = $objOrgaoEmissorRg->lista();
        $listaOrgao = ['0'=>'Selecione'];
        if ($listaOrgaoEmissorRg) {
            foreach ($listaOrgaoEmissorRg as $orgaoemissor) {
                $listaOrgao[$orgaoemissor['idorg_rg']] = $orgaoemissor['sigla'];
            }
        }

        $this->campoOculto('idpes', $this->idpes);

        $this->campoTexto('rg', 'Rg', $this->rg, '10', '10', false);
        $this->campoData('data_exp_rg', 'Data Expedição RG', $this->data_exp_rg, false);
        $this->campoLista('sigla_uf_exp_rg', 'Órgão Expedidor', $listaEstado, $this->sigla_uf_exp_rg, false, false, false, false, false);

        $lista_tipo_cert_civil = [];
        $lista_tipo_cert_civil['0'] = 'Selecione';
        $lista_tipo_cert_civil[91] = 'Nascimento';
        $lista_tipo_cert_civil[92] = 'Casamento';
        $this->campoLista('tipo_cert_civil', 'Tipo Certificado Civil', $lista_tipo_cert_civil, $this->tipo_cert_civil);

        $this->campoTexto('num_termo', 'Termo', $this->num_termo, '8', '8', false);
        $this->campoTexto('num_livro', 'Livro', $this->num_livro, '8', '8', false);
        $this->campoTexto('num_folha', 'Folha', $this->num_folha, '4', '4', false);
        $this->campoTexto('certidao_nascimento', 'Certidão nascimento', $this->certidao_nascimento, '37', '40', false);
        $this->campoTexto('certidao_casamento', 'Certidão casamento', $this->certidao_casamento, '37', '40', false);

        $this->campoData('data_emissao_cert_civil', 'Emissão Certidão Civil', $this->data_emissao_cert_civil, false);
        $this->campoLista('sigla_uf_cert_civil', 'Sigla Certidão Civil', $listaEstado, $this->sigla_uf_cert_civil, false, false, false, false, false);
        $this->campoMemo('cartorio_cert_civil', 'Cartório', $this->cartorio_cert_civil, '35', '4', false);
        $this->campoTexto('num_cart_trabalho', 'Carteira de Trabalho', $this->num_cart_trabalho, '7', '7', false);
        $this->campoTexto('serie_cart_trabalho', 'Série', $this->serie_cart_trabalho, '5', '5', false);
        $this->campoData('data_emissao_cart_trabalho', 'Emissão Carteira', $this->data_emissao_cart_trabalho, false);
        $this->campoLista('sigla_uf_cart_trabalho', 'Sigla Carteira de Trabalho', $listaEstado, $this->sigla_uf_cart_trabalho, false, false, false, false, false);
        $this->campoTexto('num_tit_eleitor', 'Título de Eleitor', $this->num_tit_eleitor, '13', '13', false);
        $this->campoTexto('zona_tit_eleitor', 'Zona', $this->zona_tit_eleitor, '4', '4', false);
        $this->campoTexto('secao_tit_eleitor', 'Seção', $this->secao_tit_eleitor, '10', '10', false);
        $this->campoLista('idorg_exp_rg', 'Órgão Expedição RG', $listaOrgao, $this->idorg_exp_rg, false, false, false, false, false);
    }

    public function Novo()
    {
        if ($this->data_emissao_cart_trabalho) {
            $this->data_emissao_cart_trabalho = explode('/', $this->data_emissao_cart_trabalho);
            $this->data_emissao_cart_trabalho = "{$this->data_emissao_cart_trabalho[2]}/{$this->data_emissao_cart_trabalho[1]}/{$this->data_emissao_cart_trabalho[0]}";
        }

        if ($this->data_emissao_cert_civil) {
            $this->data_emissao_cert_civil = explode('/', $this->data_emissao_cert_civil);
            $this->data_emissao_cert_civil = "{$this->data_emissao_cert_civil[2]}/{$this->data_emissao_cert_civil[1]}/{$this->data_emissao_cert_civil[0]}";
        }

        if ($this->data_exp_rg) {
            $this->data_exp_rg = explode('/', $this->data_exp_rg);
            $this->data_exp_rg = "{$this->data_exp_rg[2]}/{$this->data_exp_rg[1]}/{$this->data_exp_rg[0]}";
        }

        // remove caracteres não numericos
        $this->rg = preg_replace('/[^0-9]/', '', $this->rg);

        $ObjDocumento = new Documento($this->idpes, $this->rg, $this->data_exp_rg, $this->sigla_uf_exp_rg, $this->tipo_cert_civil, $this->num_termo, $this->num_livro, $this->num_folha, $this->data_emissao_cert_civil, $this->sigla_uf_cert_civil, $this->cartorio_cert_civil, $this->num_cart_trabalho, $this->serie_cart_trabalho, $this->data_emissao_cart_trabalho, $this->sigla_uf_cart_trabalho, $this->num_tit_eleitor, $this->zona_tit_eleitor, $this->secao_tit_eleitor, $this->idorg_exp_rg, $this->certidao_nascimento, $this->certidao_casamento);

        if ($ObjDocumento->cadastra()) {
            echo '<script>window.close()</script>';

            return true;
        }

        return false;
    }

    public function Editar()
    {
        if ($this->data_emissao_cart_trabalho) {
            $this->data_emissao_cart_trabalho = explode('/', $this->data_emissao_cart_trabalho);
            $this->data_emissao_cart_trabalho = "{$this->data_emissao_cart_trabalho[2]}/{$this->data_emissao_cart_trabalho[1]}/{$this->data_emissao_cart_trabalho[0]}";
        }

        if ($this->data_emissao_cert_civil) {
            $this->data_emissao_cert_civil = explode('/', $this->data_emissao_cert_civil);
            $this->data_emissao_cert_civil = "{$this->data_emissao_cert_civil[2]}/{$this->data_emissao_cert_civil[1]}/{$this->data_emissao_cert_civil[0]}";
        }

        if ($this->data_exp_rg) {
            $this->data_exp_rg = explode('/', $this->data_exp_rg);
            $this->data_exp_rg = "{$this->data_exp_rg[2]}/{$this->data_exp_rg[1]}/{$this->data_exp_rg[0]}";
        }

        // remove caracteres não numericos
        $this->rg = preg_replace('/[^0-9]/', '', $this->rg);

        $ObjDocumento = new Documento($this->idpes, $this->rg, $this->data_exp_rg, $this->sigla_uf_exp_rg, $this->tipo_cert_civil, $this->num_termo, $this->num_livro, $this->num_folha, $this->data_emissao_cert_civil, $this->sigla_uf_cert_civil, $this->cartorio_cert_civil, $this->num_cart_trabalho, $this->serie_cart_trabalho, $this->data_emissao_cart_trabalho, $this->sigla_uf_cart_trabalho, $this->num_tit_eleitor, $this->zona_tit_eleitor, $this->secao_tit_eleitor, $this->idorg_exp_rg, $this->certidao_nascimento, $this->certidao_casamento);

        if ($ObjDocumento->edita()) {
            echo '<script>window.close()</script>';

            return true;
        }

        return false;
    }

    public function Excluir()
    {
        $ObjDocumento = new Documento($this->idpes);
        $ObjDocumento->exclui();
        echo '<script>document.location=\'meusdados.php\';</script>';

        return true;
    }
}

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm($miolo);

$pagina->MakeAll();

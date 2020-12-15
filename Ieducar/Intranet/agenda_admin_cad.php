<?php

$desvio_diretorio = '';
require_once('Source/Base.php');
require_once('Source/Cadastro.php');
require_once('Source/Banco.php');

class clsIndex extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} Agenda");
        $this->processoAp = '343';
    }
}

class indice extends clsCadastro
{
    public $cod_agenda;
    public $ref_ref_cod_pessoa_exc;
    public $ref_ref_cod_pessoa_cad;
    public $nm_agenda;
    public $publica;
    public $envia_alerta;
    public $data_cad;
    public $data_edicao;
    public $ref_ref_cod_pessoa_own;
    public $dono;
    public $editar;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->editar = false;
        if (isset($_GET['cod_agenda'])) {
            $this->cod_agenda = $_GET['cod_agenda'];
            $db = new Banco();
            $db->Consulta("SELECT cod_agenda, ref_ref_cod_pessoa_exc, ref_ref_cod_pessoa_cad,  nm_agenda, publica, envia_alerta, data_cad, data_edicao, ref_ref_cod_pessoa_own FROM Portal.agenda WHERE cod_agenda='{$this->cod_agenda}'");
            if ($db->ProximoRegistro()) {
                list($this->cod_agenda, $this->ref_ref_cod_pessoa_exc, $this->ref_ref_cod_pessoa_cad, $this->nm_agenda, $this->publica, $this->envia_alerta, $this->data_cad, $this->data_edicao, $this->ref_ref_cod_pessoa_own) = $db->Tupla();
                $this->fexcluir = true;
                $retorno = 'Editar';
                $this->editar = true;
            }
            if (isset($_GET['edit_rem']) && is_numeric($_GET['edit_rem'])) {
                $db->Consulta("DELETE FROM Portal.agenda_responsavel WHERE ref_ref_cod_pessoa_fj = '{$_GET['edit_rem']}' AND ref_cod_agenda = '{$this->cod_agenda}'");
                $this->mensagem = 'Editor removido';
            }
            if (isset($_POST['novo_editor']) && is_numeric($_POST['novo_editor'])) {
                $db->Consulta("SELECT 1 FROM Portal.agenda_responsavel WHERE ref_ref_cod_pessoa_fj = '{$_POST['novo_editor']}' AND ref_cod_agenda = '{$this->cod_agenda}'");
                if (! $db->ProximoRegistro()) {
                    $db->Consulta("SELECT 1 FROM agenda WHERE ref_ref_cod_pessoa_own = '{$_POST['novo_editor']}' AND cod_agenda = '{$this->cod_agenda}'");
                    if (! $db->ProximoRegistro()) {
                        $db->Consulta("INSERT INTO agenda_responsavel ( ref_ref_cod_pessoa_fj, ref_cod_agenda ) VALUES ( '{$_POST['novo_editor']}', '{$this->cod_agenda}' )");
                    } else {
                        $this->mensagem = 'O dono da agenda j&aacute; &eacute; considerado um editor da mesma.';
                    }
                } else {
                    $this->mensagem = 'Este editor j&aacute; est&aacute; cadastrado';
                }
            }
        }

        if ($retorno == 'Editar') {
            $this->url_cancelar = "agenda_admin_det.php?cod_agenda={$this->cod_agenda}";
        } else {
            $this->url_cancelar = 'agenda_admin_lst.php';
        }
        $this->nome_url_cancelar = 'Cancelar';

        $nomeMenu = $retorno == 'Editar' ? $retorno : 'Cadastrar';

        $this->breadcrumb("$nomeMenu agenda");

        return $retorno;
    }

    public function Gerar()
    {
        $db = new Banco();
        $objPessoa = new PessoaFisica();

        $this->campoOculto('pessoaFj', $this->pessoaFj);
        $this->campoOculto('cod_agenda', $this->cod_agenda);

        $this->campoTexto('nm_agenda', 'Nome da Agenda', $this->nm_agenda, 50, 50);

        $this->campoLista('publica', 'Pública', [ 'N&atilde;o', 'Sim' ], $this->publica);

        $this->campoLista('envia_alerta', 'Envia Alerta', [ 'N&atilde;o', 'Sim' ], $this->envia_alerta);

        $i = 0;
        if ($this->ref_ref_cod_pessoa_own) {
            list($nome) = $objPessoa->queryRapida($this->ref_ref_cod_pessoa_own, 'nome');
            $this->campoTextoInv("editor{$i}", 'Editores', $nome, 50, 255);
        }

        $lista = [ 'Pesquise a Pessoa clicando no botao ao lado' ];

        if ($this->cod_agenda) {
            $db->Consulta("SELECT ref_ref_cod_pessoa_fj FROM Portal.agenda_responsavel WHERE ref_cod_agenda = '{$this->cod_agenda}'");
            while ($db->ProximoRegistro()) {
                $i++;
                list($idpes) = $db->Tupla();
                list($nome) = $objPessoa->queryRapida($idpes, 'nome');
                $this->campoTextoInv("editor{$i}", 'Editores', $nome, 50, 255, false, false, false, false, "<a href=\"agenda_admin_cad.php?cod_agenda={$this->cod_agenda}&edit_rem=$idpes\">remover</a>");
            }
            //$this->campoListaPesq( "novo_editor", "Novo Editor", $lista, 0, "pesquisa_funcionario.php", false, false, false, "&nbsp; &nbsp; &nbsp; <a href=\"javascript:var idpes = document.getElementById('novo_editor').value; if( idpes != 0 ) { document.location.href='agenda_admin_cad.php?cod_agenda={$this->cod_agenda}&edit_add=' + idpes; } else { alert( 'Selecione a Pessoa clicando na imagem da Lupa' ); }\">Adicionar</a>" );
            $parametros = new clsParametrosPesquisas();
            $parametros->setSubmit(1);
            $parametros->adicionaCampoSelect('novo_editor', 'ref_cod_pessoa_fj', 'nome');
            $this->campoListaPesq('novo_editor', 'Novo Editor', $lista, 0, 'pesquisa_funcionario_lst.php', '', false, '', '', null, null, '', false, $parametros->serializaCampos());
            //$this->campoLista( "edit_add", "Editores", $lista, "", "", false, "", "<img id='lupa' src=\"imagens/lupa.png\" border=\"0\" onclick=\"showExpansivel( 500,500, '<iframe name=\'miolo\' id=\'miolo\' frameborder=\'0\' height=\'100%\' width=\'500\' marginheight=\'0\' marginwidth=\'0\' src=\'pesquisa_funcionario_lst.php?campos=$serializedcampos\'></iframe>' );\">", false, true );
            unset($campos);
        } else {
            //$this->campoListaPesq( "dono", "Dono da agenda", $lista, 0, "pesquisa_funcionario.php" );
            $parametros = new clsParametrosPesquisas();
            $parametros->setSubmit(0);
            $parametros->adicionaCampoSelect('dono', 'ref_cod_pessoa_fj', 'nome');
            $this->campoListaPesq('dono', 'Dono da agenda', $lista, 0, 'pesquisa_funcionario_lst.php', '', false, '', '', null, null, '', false, $parametros->serializaCampos());
            //$this->campoLista( "dono", "Dono da agenda", $lista, "", "", false, "", "<img id='lupa' src=\"imagens/lupa.png\" border=\"0\" onclick=\"showExpansivel( 500,500, '<iframe name=\'miolo\' id=\'miolo\' frameborder=\'0\' height=\'100%\' width=\'500\' marginheight=\'0\' marginwidth=\'0\' src=\'pesquisa_funcionario_lst.php?campos=$serializedcampos\'></iframe>' );\">", false, true );
        }
    }

    public function Novo()
    {
        $campos = '';
        $values = '';
        $db = new Banco();

        if ($this->nm_agenda) {
            if (is_string($this->nm_agencia)) {
                $campos .= ', nm_agencia';
                $values .= ", '{$this->nm_agencia}'";
            }

            if (is_numeric($this->dono)) {
                if ($this->dono) {
                    $campos .= ', ref_ref_cod_pessoa_own';
                    $values .= ", '{$this->dono}'";
                } else {
                    $campos .= ', ref_ref_cod_pessoa_own';
                    $values .= ', NULL';
                }
            }

            if (is_numeric($this->publica)) {
                $campos .= ', publica';
                $values .= ", '{$this->publica}'";
            }

            if (is_numeric($this->envia_alerta)) {
                $campos .= ', envia_alerta';
                $values .= ", '{$this->envia_alerta}'";
            }

            $db->Consulta("INSERT INTO Portal.agenda( ref_ref_cod_pessoa_cad, data_cad, nm_agenda $campos) VALUES( '{$this->pessoa_logada}', NOW(), '{$this->nm_agenda}' $values)");
            $id_agenda = $db->insertId('Portal.agenda_cod_agenda_seq');

            $db->Consulta("INSERT INTO Portal.agenda_responsavel( ref_ref_cod_pessoa_fj, ref_cod_agenda ) VALUES( '{$this->pessoa_logada}', '{$id_agenda}' )");

            $this->simpleRedirect('agenda_admin_lst.php');
        } else {
            return false;
        }
    }

    public function Editar()
    {
        $set = '';
        $db = new Banco();

        if (is_numeric($this->cod_agenda)) {
            if (is_string($this->nm_agenda)) {
                $set .= ", nm_agenda = '{$this->nm_agenda}'";
            }

            if (is_numeric($this->publica)) {
                $set .= ", publica = '{$this->publica}'";
            }

            if (is_numeric($this->envia_alerta)) {
                $set .= ", envia_alerta = '{$this->envia_alerta}'";
            }

            $db->Consulta("UPDATE Portal.agenda SET ref_ref_cod_pessoa_exc = '{$this->pessoa_logada}', data_edicao = NOW() $set WHERE cod_agenda = '{$this->cod_agenda}'");
            $this->simpleRedirect('agenda_admin_lst.php');
        } else {
            $this->mensagem = 'Codigo de Diaria invalido!';

            return false;
        }

        return true;
    }

    public function Excluir()
    {
        if (is_numeric($this->cod_agenda)) {
            $db = new Banco();

            $db->Consulta("DELETE FROM Portal.agenda_compromisso WHERE ref_cod_agenda={$this->cod_agenda}");
            $db->Consulta("DELETE FROM Portal.agenda_responsavel WHERE ref_cod_agenda={$this->cod_agenda}");

            $db->Consulta("DELETE FROM Portal.agenda WHERE cod_agenda={$this->cod_agenda}");
            $this->simpleRedirect('agenda_admin_lst.php');
        }
        $this->mensagem = 'Codigo da Agenda inválido!';

        return false;
    }
}

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm($miolo);

$pagina->MakeAll();

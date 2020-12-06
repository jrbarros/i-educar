<?php

$desvio_diretorio = '';
require_once('include/Base.php');
require_once('include/clsListagem.inc.php');
require_once('include/Banco.inc.php');

class clsIndex extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} Agenda");
        $this->processoAp = '343';
    }
}

class indice extends clsListagem
{
    public $cd_agenda;
    public $nm_agenda;

    public function Gerar()
    {
        $this->titulo = 'Agendas Admin';

        $this->addCabecalhos([ 'Agenda' ]);

        $this->campoTexto('pesquisa', 'Agenda', '', 50, 255);

        $where = '';

        if (!empty($_GET['pesquisa'])) {
            $pesquisa = str_replace(' ', '%', $_GET['pesquisa']);
            $where = "WHERE nm_agenda ILIKE '%{$pesquisa}%'";
            $pesquisa = str_replace('%', ' ', $_GET['pesquisa']);
        }

        $db = new Banco();
        $total = $db->UnicoCampo("SELECT COUNT(0) FROM portal.agenda {$where}");

        // Paginador
        $limite = 15;
        $iniciolimit = ($_GET["pagina_{$this->nome}"]) ? $_GET["pagina_{$this->nome}"]*$limite-$limite: 0;

        $sql = "SELECT cod_agenda, nm_agenda, ref_ref_cod_pessoa_own FROM portal.agenda {$where} ORDER BY nm_agenda ASC LIMIT $limite OFFSET $iniciolimit";

        $db2 = new Banco();
        $db2->Consulta($sql);
        while ($db2->ProximoRegistro()) {
            list($cod_agenda, $nm_agenda, $cod_pessoa_own) = $db2->Tupla();
            $this->addLinhas([ "<a href='agenda_admin_det.php?cod_agenda={$cod_agenda}'><img src='imagens/noticia.jpg' border=0>$nm_agenda</a>"]);
        }

        // Paginador
        $this->addPaginador2('agenda_admin_lst.php', $total, $_GET, $this->nome, $limite);

        $this->acao = 'go("agenda_admin_cad.php")';
        $this->nome_acao = 'Novo';

        $this->largura = '100%';

        $this->breadcrumb('Listagem de agendas');
    }
}

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm($miolo);

$pagina->MakeAll();

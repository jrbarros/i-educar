<?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/Portal/PortalAcesso.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} Acesso");
        $this->processoAp = '666';
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

    public $cod_acesso;
    public $data_hora;
    public $ip_externo;
    public $ip_interno;
    public $cod_pessoa;
    public $obs;
    public $sucesso;

    public function Gerar()
    {
        $this->titulo = 'Acesso - Detalhe';

        $this->cod_acesso=$_GET['cod_acesso'];

        $tmp_obj = new clsPortalAcesso($this->cod_acesso);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('portal_acesso_lst.php');
        }

        if ($registro['cod_acesso']) {
            $this->addDetalhe([ 'Acesso', "{$registro['cod_acesso']}"]);
        }
        if ($registro['data_hora']) {
            $this->addDetalhe([ 'Data Hora', dataFromPgToBr($registro['data_hora'], 'd/m/Y H:i') ]);
        }
        if ($registro['ip_externo']) {
            $this->addDetalhe([ 'Ip Externo', "{$registro['ip_externo']}"]);
        }
        if ($registro['ip_interno']) {
            $this->addDetalhe([ 'Ip Interno', "{$registro['ip_interno']}"]);
        }
        if ($registro['cod_pessoa']) {
            $this->addDetalhe([ 'Pessoa', "{$registro['cod_pessoa']}"]);
        }
        if ($registro['obs']) {
            $this->addDetalhe([ 'Obs', "{$registro['obs']}"]);
        }
        if (! is_null($registro['sucesso'])) {
            $this->addDetalhe([ 'Sucesso', dbBool($registro['sucesso']) ? 'Sim': 'Não' ]);
        }

        $this->url_novo = 'portal_acesso_cad.php';
        $this->url_editar = "portal_acesso_cad.php?cod_acesso={$registro['cod_acesso']}";

        $this->url_cancelar = 'portal_acesso_lst.php';
        $this->largura = '100%';
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

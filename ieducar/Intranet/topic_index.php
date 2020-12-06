<?php

$desvio_diretorio = '';
require_once('include/Base.php');
require_once('include/Banco.inc.php');

class clsIndex extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-OpeTopicE");
        $this->processoAp = '459';
    }
}

class indice
{
    public function RenderHTML()
    {
        return '
                <table width=\'100%\' height=\'100%\'>
                    <tr align=center valign=\'top\'><td><img src=\'imagens/i-pauta/splashscreen.jpg\' style=\'padding-top: 50px\'></td></tr>
                </table>
                ';
    }
}

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm($miolo);

$pagina->MakeAll();

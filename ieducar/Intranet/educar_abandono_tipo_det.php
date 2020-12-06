<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    *                                                                        *
    *   @author Prefeitura Municipal de ItajaÃ­                              *
    *   @updated 29/03/2007                                                  *
    *   Pacote: i-PLB Software PÃºblico Livre e Brasileiro                   *
    *                                                                        *
    *   Copyright (C) 2006  PMI - Prefeitura Municipal de ItajaÃ­            *
    *                       ctima@itajai.sc.gov.br                           *
    *                                                                        *
    *   Este  programa  Ã©  software livre, vocÃª pode redistribuÃ­-lo e/ou  *
    *   modificÃ¡-lo sob os termos da LicenÃ§a PÃºblica Geral GNU, conforme  *
    *   publicada pela Free  Software  Foundation,  tanto  a versÃ£o 2 da    *
    *   LicenÃ§a   como  (a  seu  critÃ©rio)  qualquer  versÃ£o  mais  nova.     *
    *                                                                        *
    *   Este programa  Ã© distribuÃ­do na expectativa de ser Ãºtil, mas SEM  *
    *   QUALQUER GARANTIA. Sem mesmo a garantia implÃ­cita de COMERCIALI-    *
    *   ZAÃÃO  ou  de ADEQUAÃÃO A QUALQUER PROPÃSITO EM PARTICULAR. Con-     *
    *   sulte  a  LicenÃ§a  PÃºblica  Geral  GNU para obter mais detalhes.   *
    *                                                                        *
    *   VocÃª  deve  ter  recebido uma cÃ³pia da LicenÃ§a PÃºblica Geral GNU     *
    *   junto  com  este  programa. Se nÃ£o, escreva para a Free Software    *
    *   Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
    *   02111-1307, USA.                                                     *
    *                                                                        *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once __DIR__ . '/include/pmieducar/geral.inc.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Motivo Abandono");
        $this->processoAp = '950';
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

    public $cod_abandono_tipo;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nome;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_instituicao;

    public function Gerar()
    {
        $this->titulo = 'Abandono Tipo - Detalhe';

        $this->cod_abandono_tipo=$_GET['cod_abandono_tipo'];

        $tmp_obj = new clsPmieducarAbandonoTipo($this->cod_abandono_tipo);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_abandono_tipo_lst.php');
        }

        $obj_instituicao = new Instituicao($registro['ref_cod_instituicao']);
        $obj_instituicao_det = $obj_instituicao->detalhe();
        $registro['ref_cod_instituicao'] = $obj_instituicao_det['nm_instituicao'];

        $obj_permissoes = new Permissoes();
        $nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);

        if (($nivel_usuario === 1) && $registro['ref_cod_instituicao']) {
            $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$registro['ref_cod_instituicao']}"]);
        }

        if ($registro['nome']) {
            $this->addDetalhe([ 'Motivo Abandono', "{$registro['nome']}"]);
        }

        if ($obj_permissoes->permissao_cadastra(950, $this->pessoa_logada, 7)) {
            $this->url_novo = 'educar_abandono_tipo_cad.php';
            $this->url_editar = "educar_abandono_tipo_cad.php?cod_abandono_tipo={$registro['cod_abandono_tipo']}";
        }
        $this->url_cancelar = 'educar_abandono_tipo_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do tipo de abandono', [
            url('Intranet/educar_index.php') => 'Escola',
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

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
 * @author    Lucas Schmoeller da Silva <lucas@portabilis.com.br>
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

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;

require_once 'include/Base.php';
require_once 'include/clsDetalhe.inc.php';
require_once 'include/Banco.inc.php';
require_once 'include/pmieducar/geral.inc.php';

/**
 * clsIndexBase class.
 *
 * @author    Lucas Schmoeller da Silva <lucas@portabilis.com.br>
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
        $this->SetTitulo($this->_instituicao . ' i-Educar - Vagas por série');
        $this->processoAp = 21253;
    }
}

/**
 * indice class.
 *
 * @author    Lucas Schmoeller da Silva <lucas@portabilis.com.br>
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
class indice extends clsDetalhe
{
    public $titulo;

    public $cod_serie_vaga;

    public function Gerar()
    {
        $this->titulo = 'Vagas por série - Detalhe';
        $this->addBanner(
        'imagens/nvp_top_intranet.jpg',
        'imagens/nvp_vert_intranet.jpg',
        'Intranet'
    );

        $this->cod_serie_vaga = $_GET['cod_serie_vaga'];

        $tmp_obj = new SerieVaga($this->cod_serie_vaga);

        $registro = $tmp_obj->detalhe();

        if (!$registro) {
            throw new HttpResponseException(
            new RedirectResponse('educar_serie_vaga_lst.php')
        );
        }

        $obj_serie = new Serie($registro['ref_cod_serie']);
        $det_serie = $obj_serie->detalhe();
        $registro['ref_ref_cod_serie'] = $det_serie['nm_serie'];

        // Dados do curso
        $obj_ref_cod_curso = new Curso($registro['ref_cod_curso']);
        $det_ref_cod_curso = $obj_ref_cod_curso->detalhe();
        $registro['ref_cod_curso'] = $det_ref_cod_curso['nm_curso'];

        // Dados da escola
        $obj_ref_cod_escola = new Escola($registro['ref_cod_escola']);
        $det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
        $registro['ref_cod_escola'] = $det_ref_cod_escola['nome'];

        if ($registro['ano']) {
            $this->addDetalhe(['Ano', $registro['ano']]);
        }

        if ($registro['ref_cod_escola']) {
            $this->addDetalhe(['Escola', $registro['ref_cod_escola']]);
        }

        if ($registro['ref_cod_curso']) {
            $this->addDetalhe(['Curso', $registro['ref_cod_curso']]);
        }

        if ($registro['ref_ref_cod_serie']) {
            $this->addDetalhe(['Série', $registro['ref_ref_cod_serie']]);
        }

        if ($registro['turno']) {
            $turnos = [
        0 => 'Selecione',
        1 => 'Matutino',
        2 => 'Vespertino',
        3 => 'Noturno',
        4 => 'Integral'
      ];
            $this->addDetalhe(['Turno', $turnos[$registro['turno']]]);
        }

        if ($registro['vagas']) {
            $this->addDetalhe(['Vagas', $registro['vagas']]);
        }

        $obj_permissoes = new Permissoes();

        if ($obj_permissoes->permissao_cadastra(21253, $this->pessoa_logada, 7)) {
            $this->url_novo   = 'educar_serie_vaga_cad.php';
            $this->url_editar = sprintf('educar_serie_vaga_cad.php?cod_serie_vaga=%d', $this->cod_serie_vaga);
        }

        $this->url_cancelar = 'educar_serie_vaga_lst.php';
        $this->largura      = '100%';

        $this->breadcrumb('Detalhe de vagas da série', [
        url('Intranet/educar_index.php') => 'Escola',
    ]);
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

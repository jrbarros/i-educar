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
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
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

require_once 'Source/Base.php';
require_once 'Source/Cadastro.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';

require_once 'App/Model/Finder.php';
require_once 'Calendario/Model/TurmaDataMapper.php';

/**
 * clsIndexBase class.
 *
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
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
        $this->SetTitulo($this->_instituicao . ' i-Educar - Calendário Dia');
        $this->processoAp = 620;
    }
}

/**
 * indice class.
 *
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
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
class indice extends clsCadastro
{
    public $pessoa_logada;

    public $ref_cod_calendario_ano_letivo;
    public $mes;
    public $dia;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $ref_cod_calendario_dia_motivo;
    public $descricao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ano;
    public $ref_cod_escola;

    /**
     * @var Calendario_Model_TurmaDataMapper
     */
    public $_calendarioTurmaDataMapper = null;

    /**
     * Getter.
     *
     * @access protected
     *
     * @return Calendario_Model_TurmaDataMapper
     */
    public function _getCalendarioTurmaDataMapper()
    {
        if (is_null($this->_calendarioTurmaDataMapper)) {
            $this->_calendarioTurmaDataMapper = new Calendario_Model_TurmaDataMapper();
        }

        return $this->_calendarioTurmaDataMapper;
    }

    /**
     * Verifica se existe uma instância de Turma.
     *
     * @access protected
     *
     * @param int $codCalendarioAnoLetivo Código da chave primária pmieducar.calendario_ano_letivo
     * @param int $mes
     * @param int $dia
     * @param int $ano
     * @param int $codTurma               Código da chave primária de pmieducar.turma
     *
     * @return bool
     */
    public function _hasEntry($codCalendarioAnoLetivo, $mes, $dia, $ano, $codTurma)
    {
        $args = [
      'calendarioAnoLetivo' => $codCalendarioAnoLetivo,
      'mes'                 => $mes,
      'dia'                 => $dia,
      'ano'                 => $ano,
      'turma'               => $codTurma
    ];

        try {
            $this->_getCalendarioTurmaDataMapper()->find($args);

            return true;
        } catch (Exception $e) {
        }

        return false;
    }

    /**
     * Retorna um array de instâncias de Turma para um dado
     * calendário de ano letivo de escola em mês, dia e ano específicos.
     *
     * @access protected
     *
     * @param int $codCalendarioAnoLetivo Código de pmieducar.calendario_ano_letivo
     * @param int $mes
     * @param int $dia
     * @param int $ano
     *
     * @return array (cod_turma => Turma)
     */
    public function _getEntries($codCalendarioAnoLetivo, $mes, $dia, $ano)
    {
        $where = [
      'calendarioAnoLetivo' => $codCalendarioAnoLetivo,
      'mes'                 => $mes,
      'dia'                 => $dia,
      'ano'                 => $ano
    ];

        $turmas = $this->_getCalendarioTurmaDataMapper()->findAll([], $where);

        $ret = [];
        foreach ($turmas as $turma) {
            $ret[$turma->turma] = $turma;
        }

        return $ret;
    }

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->dia = $_GET['dia'];
        $this->mes = $_GET['mes'];
        $this->ref_cod_calendario_ano_letivo = $_GET['ref_cod_calendario_ano_letivo'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(
        620,
        $this->pessoa_logada,
        7,
        'educar_calendario_dia_lst.php'
    );

        if (is_numeric($this->ref_cod_calendario_ano_letivo) &&
      is_numeric($this->mes) && is_numeric($this->dia)
    ) {
            $obj = new CalendarioDia(
          $this->ref_cod_calendario_ano_letivo,
          $this->mes,
          $this->dia
      );

            $registro  = $obj->detalhe();

            if ($registro) {
                // passa todos os valores obtidos no registro para atributos do objeto
                foreach ($registro as $campo => $val) {
                    $this->$campo = $val;
                }

                $obj_permissoes = new Permissoes();

                if ($obj_permissoes->permissao_excluir(620, $this->pessoa_logada, 7)) {
                    if ($this->descricao) {
                        $this->fexcluir = true;
                    }
                }

                $retorno = 'Editar';
            }

            $objTemp = new CalendarioAnoLetivo($this->ref_cod_calendario_ano_letivo);
            $det = $objTemp->detalhe();
            $this->ano = $det['ano'];
        }

        $this->url_cancelar = sprintf(
        'educar_calendario_anotacao_lst.php?ref_cod_calendario_ano_letivo=%d&ano=%d&mes=%d&dia=%d',
        $registro['ref_cod_calendario_ano_letivo'],
        $this->ano,
        $registro['mes'],
        $registro['dia']
    );
        $this->nome_url_cancelar = 'Cancelar';

        return $retorno;
    }

    public function Gerar()
    {
        // Primary keys
        $this->campoRotulo(
        'dia_',
        'Dia',
        sprintf('<b>%d/%d/%d</b>', $this->dia, $this->mes, $this->ano)
    );

        $this->campoOculto(
        'ref_cod_calendario_ano_letivo',
        $this->ref_cod_calendario_ano_letivo
    );

        $obj_calendario_ano_letivo = new CalendarioAnoLetivo(
        $this->ref_cod_calendario_ano_letivo
    );

        $det_calendario_ano_letivo = $obj_calendario_ano_letivo->detalhe();
        $ref_cod_escola = $det_calendario_ano_letivo['ref_cod_escola'];

        $this->campoRotulo('ano', 'Ano Letivo', $this->ano);

        $this->campoOculto('mes', $this->mes);
        $this->campoOculto('dia', $this->dia);

        // Foreign keys
        $opcoes = ['' => 'Selecione'];
        $objTemp = new CalendarioDiaMotivo();
        $lista = $objTemp->lista(
        null,
        $ref_cod_escola,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        1
    );

        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $opcoes[$registro['cod_calendario_dia_motivo']] = $registro['nm_motivo'];
            }
        }

        $this->campoLista(
        'ref_cod_calendario_dia_motivo',
        'Calendário Dia Motivo',
        $opcoes,
        $this->ref_cod_calendario_dia_motivo,
        '',
        false,
        '',
        '',
        false,
        false
    );

        $seletor = '<label><input id="_turmas_sel" onclick="new ied_forms.checkAll(document, \'formcadastro\', \'turmas\')" type="checkbox" /> Selecionar todas</label>';
        $this->campoRotulo('turmas_rotulo', 'Turmas', $seletor);
        $turmas = App_Model_IedFinder::getTurmas($ref_cod_escola, null, $this->ano);

        foreach ($turmas as $codTurma => $nomeTurma) {
            $checked = $this->_hasEntry(
          $this->ref_cod_calendario_ano_letivo,
          $this->mes,
          $this->dia,
          $this->ano,
          $codTurma
      );

            $this->campoCheck('turmas[' . $codTurma  . ']', '', $checked, $nomeTurma, false);
        }

        $this->campoMemo('descricao', 'Descrição', $this->descricao, 30, 10, true);
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(
        620,
        $this->pessoa_logada,
        7,
        'educar_calendario_dia_lst.php'
    );

        $obj = new CalendarioDia(
        $this->ref_cod_calendario_ano_letivo,
        $this->mes,
        $this->dia,
        $this->pessoa_logada,
        $this->pessoa_logada,
        $this->ref_cod_calendario_dia_motivo,
        $this->descricao,
        $this->data_cadastro,
        $this->data_exclusao,
        $this->ativo
    );

        $cadastrou = $obj->cadastra();

        foreach ($this->turmas as $codTurma => $turma) {
            $calendarioTurma = new Calendario_Model_Turma([
        'calendarioAnoLetivo' => $this->ref_cod_calendario_ano_letivo,
        'ano'                 => $this->ano,
        'mes'                 => $this->mes,
        'dia'                 => $this->dia,
        'turma'               => $codTurma
      ]);
            $this->_getCalendarioTurmaDataMapper()->save($calendarioTurma);
        }

        if ($cadastrou) {
            $this->mensagem .= 'Cadastro efetuado com sucesso. <br />';
            $url = sprintf(
          'educar_calendario_anotacao_lst.php?dia=%d&mes=%d&ano=%d&ref_cod_calendario_ano_letivo=%d',
          $this->dia,
          $this->mes,
          $this->ano,
          $this->ref_cod_calendario_ano_letivo
      );
            throw new HttpResponseException(
          new RedirectResponse($url)
      );
        }

        $this->mensagem = 'Cadastro não realizado. <br />';

        return false;
    }

    public function Editar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(
        620,
        $this->pessoa_logada,
        7,
        'educar_calendario_dia_lst.php'
    );

        $obj = new CalendarioDia(
        $this->ref_cod_calendario_ano_letivo,
        $this->mes,
        $this->dia,
        $this->pessoa_logada,
        $this->pessoa_logada,
        $this->ref_cod_calendario_dia_motivo,
        $this->descricao,
        $this->data_cadastro,
        $this->data_exclusao,
        1
    );

        $editou = $obj->edita();

        // Inicialização de arrays
        $insert = $delete = $entries = $intersect = [];

        if (isset($this->turmas)) {
            foreach ($this->turmas as $codTurma => $turma) {
                $calendarioTurma = new Calendario_Model_Turma([
          'calendarioAnoLetivo' => $this->ref_cod_calendario_ano_letivo,
          'ano'                 => $this->ano,
          'mes'                 => $this->mes,
          'dia'                 => $this->dia,
          'turma'               => $codTurma
        ]);
                $insert[$codTurma] = $calendarioTurma;
            }
        }

        // Instâncias persistidas de Turma
        $entries = $this->_getEntries(
        $this->ref_cod_calendario_ano_letivo,
        $this->mes,
        $this->dia,
        $this->ano
    );

        // Instâncias para apagar
        $delete = array_diff(array_keys($entries), array_keys($insert));

        // Instâncias já persistidas
        $intersect = array_intersect(array_keys($entries), array_keys($insert));

        foreach ($delete as $id) {
            $this->_getCalendarioTurmaDataMapper()->delete($entries[$id]);
        }

        foreach ($insert as $key => $entry) {
            if (in_array($key, $intersect)) {
                continue;
            }
            $this->_getCalendarioTurmaDataMapper()->save($entry);
        }

        if ($editou) {
            $this->mensagem .= 'Edição efetuada com sucesso. <br />';
            $url = sprintf(
          'educar_calendario_anotacao_lst.php?dia=%d&mes=%d&ano=%d&ref_cod_calendario_ano_letivo=%d',
          $this->dia,
          $this->mes,
          $this->ano,
          $this->ref_cod_calendario_ano_letivo
      );
            throw new HttpResponseException(
          new RedirectResponse($url)
      );
        }

        $this->mensagem = 'Edição não realizada. <br />';

        return false;
    }

    public function Excluir()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_excluir(
        620,
        $this->pessoa_logada,
        7,
        'educar_calendario_dia_lst.php'
    );

        $obj = new CalendarioDia(
        $this->ref_cod_calendario_ano_letivo,
        $this->mes,
        $this->dia,
        $this->pessoa_logada,
        $this->pessoa_logada,
        null,
        null,
        $this->data_cadastro,
        $this->data_exclusao,
        0
    );

        $excluiu = $obj->edita();

        $entries = $this->_getEntries(
        $this->ref_cod_calendario_ano_letivo,
        $this->mes,
        $this->dia,
        $this->ano
    );

        foreach ($entries as $entry) {
            $this->_getCalendarioTurmaDataMapper()->delete($entry);
        }

        if ($excluiu) {
            $this->mensagem .= 'Exclusão efetuada com sucesso. <br />';
            $url = sprintf(
          'educar_calendario_anotacao_lst.php?dia=%d&mes=%d&ano=%d&ref_cod_calendario_ano_letivo=%d',
          $this->dia,
          $this->mes,
          $this->ano,
          $this->ref_cod_calendario_ano_letivo
      );
            throw new HttpResponseException(
          new RedirectResponse($url)
      );
        }

        $this->mensagem = 'Exclusão não realizada. <br />';

        return false;
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

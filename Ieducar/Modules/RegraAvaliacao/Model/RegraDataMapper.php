<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class RegraDataMapper
 * @package iEducarLegacy\Modules\RegraAvaliacao\Model
 */
class RegraDataMapper extends DataMapper
{
    protected $_entityClass = 'Regra';

    protected $_tableName = 'regra_avaliacao';

    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'id' => 'id',
        'instituicao' => 'instituicao_id',
        'tipoNota' => 'tipo_nota',
        'tipoProgressao' => 'tipo_progressao',
        'tabelaArredondamento' => 'tabela_arredondamento_id',
        'tabelaArredondamentoConceitual' => 'tabela_arredondamento_id_conceitual',
        'formulaMedia' => 'formula_media_id',
        'formulaRecuperacao' => 'formula_recuperacao_id',
        'porcentagemPresenca' => 'porcentagem_presenca',
        'parecerDescritivo' => 'parecer_descritivo',
        'tipoPresenca' => 'tipo_presenca',
        'mediaRecuperacao' => 'media_recuperacao',
        'tipoRecuperacaoParalela' => 'tipo_recuperacao_paralela',
        'tipoCalculoRecuperacaoParalela' => 'tipo_calculo_recuperacao_paralela',
        'mediaRecuperacaoParalela' => 'media_recuperacao_paralela',
        'calculaMediaRecParalela' => 'calcula_media_rec_paralela',
        'notaMaximaGeral' => 'nota_maxima_geral',
        'notaMinimaGeral' => 'nota_minima_geral',
        'notaMaximaExameFinal' => 'nota_maxima_exame_final',
        'qtdCasasDecimais' => 'qtd_casas_decimais',
        'notaGeralPorEtapa' => 'nota_geral_por_etapa',
        'definirComponentePorEtapa' => 'definir_componente_etapa',
        'qtdDisciplinasDependencia' => 'qtd_disciplinas_dependencia',
        'disciplinasAglutinadas' => 'disciplinas_aglutinadas',
        'qtdMatriculasDependencia' => 'qtd_matriculas_dependencia',
        'aprovaMediaDisciplina' => 'aprova_media_disciplina',
        'reprovacaoAutomatica' => 'reprovacao_automatica',
        'regraDiferenciada' => 'regra_diferenciada_id',
    ];

    protected $_primaryKey = [
        'id' => 'id',
        'instituicao' => 'instituicao_id'
    ];

    /**
     * @var FormulaMedia_Model_FormulaDataMapper
     */
    protected $_formulaDataMapper = null;

    /**
     * @var TabelaArredondamento_Model_TabelaDataMapper
     */
    protected $_tabelaDataMapper = null;

    /**
     * Setter.
     *
     * @param FormulaMedia_Model_FormulaDataMapper $mapper
     *
     * @return RegraDataMapper
     */
    public function setFormulaDataMapper(FormulaMedia_Model_FormulaDataMapper $mapper)
    {
        $this->_formulaDataMapper = $mapper;

        return $this;
    }

    /**
     * Getter.
     *
     * @return FormulaMedia_Model_FormulaDataMapper
     */
    public function getFormulaDataMapper()
    {
        if (is_null($this->_formulaDataMapper)) {
            require_once 'FormulaMedia/Model/FormulaDataMapper.php';
            $this->setFormulaDataMapper(new FormulaMedia_Model_FormulaDataMapper());
        }

        return $this->_formulaDataMapper;
    }

    /**
     * Setter.
     *
     * @param TabelaArredondamento_Model_TabelaDataMapper $mapper
     *
     * @return CoreExt_DataMapper Provê interface fluída
     */
    public function setTabelaDataMapper(TabelaArredondamento_Model_TabelaDataMapper $mapper)
    {
        $this->_tabelaDataMapper = $mapper;

        return $this;
    }

    /**
     * Getter.
     *
     * @return TabelaArredondamento_Model_TabelaDataMapper
     */
    public function getTabelaDataMapper()
    {
        if (is_null($this->_tabelaDataMapper)) {
            require_once 'TabelaArredondamento/Model/TabelaDataMapper.php';
            $this->setTabelaDataMapper(
                new TabelaArredondamento_Model_TabelaDataMapper()
            );
        }

        return $this->_tabelaDataMapper;
    }

    /**
     * Finder.
     *
     * @return array Collection de objetos Formula
     */
    public function findFormulaMediaFinal($where = [])
    {
        return $this->_findFormulaMedia(
            [$this->_getTableColumn('tipoFormula') =>
                FormulaMedia_Model_TipoFormula::MEDIA_FINAL]
        );
    }

    /**
     * Finder.
     *
     * @return array Collection de objetos Formula
     */
    public function findFormulaMediaRecuperacao($where = [])
    {
        return $this->_findFormulaMedia(
            [$this->_getTableColumn('tipoFormula')
                => FormulaMedia_Model_TipoFormula::MEDIA_RECUPERACAO]
        );
    }

    /**
     * Finder genérico para Formula.
     *
     * @param array $where
     *
     * @return array Collection de objetos Formula
     */
    protected function _findFormulaMedia(array $where = [])
    {
        return $this->getFormulaDataMapper()->findAll(['nome'], $where);
    }

    /**
     * Finder para instâncias de TabelaArredondamento_Model_Tabela. Utiliza
     * o valor de instituição por instâncias que referenciem a mesma instituição.
     *
     * @param Regra $instance
     *
     * @return array
     */
    public function findTabelaArredondamento(Regra $instance, array $where = [])
    {
        if (isset($instance->instituicao)) {
            $where['instituicao'] = $instance->instituicao;
        }

        return $this->getTabelaDataMapper()->findAll([], $where);
    }

    /**
     * @var RegraRecuperacaoDataMapper
     */
    protected $_regraRecuperacaoDataMapper = null;

    /**
     * Setter.
     *
     * @param RegraRecuperacaoDataMapper $mapper
     *
     * @return CoreExt_DataMapper Provê interface fluída
     */
    public function setRegraRecuperacaoDataMapper(RegraRecuperacaoDataMapper $mapper)
    {
        $this->_regraRecuperacaoDataMapper = $mapper;

        return $this;
    }

    /**
     * Getter.
     *
     * @return RegraAvaliacao_Model_RegraRecuperacaoDataMappers
     */
    public function getRegraRecuperacaoDataMapper()
    {
        if (is_null($this->_regraRecuperacaoDataMapper)) {
            require_once 'RegraAvaliacao/Model/RegraRecuperacaoDataMapper.php';
            $this->setRegraRecuperacaoDataMapper(
                new RegraRecuperacaoDataMapper()
            );
        }

        return $this->_regraRecuperacaoDataMapper;
    }

    /**
     * Finder para instâncias de RegraRecuperacao que tenham
     * referências a instância Regra passada como
     * parâmetro.
     *
     * @param Regra $instance
     *
     * @return array Um array de instâncias RegraRecuperacao
     */
    public function findRegraRecuperacao(Regra $instance)
    {
        $where = [
      'regraAvaliacao' => $instance->id
    ];

        $orderby = [
      'etapasRecuperadas' => 'ASC'
    ];

        return $this->getRegraRecuperacaoDataMapper()->findAll(
            [],
            $where,
            $orderby
        );
    }
}

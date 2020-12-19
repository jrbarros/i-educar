<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model;

use iEducarLegacy\Lib\App\Model\Finder;
use iEducarLegacy\Lib\CoreExt\Entity;
use iEducarLegacy\Modules\RegraAvaliacao\Model\Nota\TipoValor;


class Regra extends Entity
{
    protected $_data = [
        'instituicao' => null,
        'nome' => null,
        'tipoNota' => null,
        'tipoProgressao' => null,
        'tabelaArredondamento' => null,
        'tabelaArredondamentoConceitual' => null,
        'media' => null,
        'formulaMedia' => null,
        'formulaRecuperacao' => null,
        'porcentagemPresenca' => null,
        'parecerDescritivo' => null,
        'tipoPresenca' => null,
        'mediaRecuperacao' => null,
        'tipoRecuperacaoParalela' => null,
        'mediaRecuperacaoParalela' => null,
        'notaMaximaGeral' => null,
        'notaMinimaGeral' => null,
        'notaMaximaExameFinal' => null,
        'qtdCasasDecimais' => null,
        'notaGeralPorEtapa' => null,
        'definirComponentePorEtapa' => null,
        'qtdDisciplinasDependencia' => null,
        'disciplinasAglutinadas' => null,
        'qtdMatriculasDependencia' => null,
        'aprovaMediaDisciplina' => null,
        'reprovacaoAutomatica' => null,
        'regraDiferenciada' => null,
        'calculaMediaRecParalela' => null,
        'tipoCalculoRecuperacaoParalela' => null,
    ];

    protected $_dataTypes = [
        'media' => 'numeric',
        'porcentagemPresenca' => 'numeric',
        'mediaRecuperacao' => 'numeric',
        'tipoRecuperacaoParalela' => 'numeric',
        'notaMaximaGeral' => 'numeric',
        'notaMinimaGeral' => 'numeric',
        'notaMaximaExameFinal' => 'numeric',
        'qtdCasasDecimais' => 'numeric',
        'qtdDisciplinasDependencia' => 'numeric',
        'qtdMatriculasDependencia' => 'numeric',
    ];

    protected $_references = [
        'tipoNota' => [
            'value' => 1,
            'class' => 'TipoValor',
            'file' => 'RegraAvaliacao/Model/Nota/TipoValor.php'
        ],
        'tabelaArredondamento' => [
            'value' => 1,
            'class' => 'TabelaArredondamento_Model_TabelaDataMapper',
            'file' => 'TabelaArredondamento/Model/TabelaDataMapper.php',
            'null' => true
        ],
        'tabelaArredondamentoConceitual' => [
            'value' => 1,
            'class' => 'TabelaArredondamento_Model_TabelaDataMapper',
            'file' => 'TabelaArredondamento/Model/TabelaDataMapper.php',
            'null' => true
        ],
        'tipoProgressao' => [
            'value' => 1,
            'class' => 'TipoProgressao',
            'file' => 'RegraAvaliacao/Model/TipoProgressao.php'
        ],
        'parecerDescritivo' => [
            'value' => 0,
            'class' => 'TipoParecerDescritivo',
            'file' => 'RegraAvaliacao/Model/TipoParecerDescritivo.php',
            'null' => true
        ],
        'tipoPresenca' => [
            'value' => 1,
            'class' => 'TipoPresenca',
            'file' => 'RegraAvaliacao/Model/TipoPresenca.php'
        ],
        'formulaMedia' => [
            'value' => null,
            'class' => 'FormulaDataMapper',
            'file' => 'FormulaMedia/Model/FormulaDataMapper.php',
            'null' => true
        ],
        'formulaRecuperacao' => [
            'value' => null,
            'class' => 'FormulaDataMapper',
            'file' => 'FormulaMedia/Model/FormulaDataMapper.php',
            'null' => true
        ],
        'tipoRecuperacaoParalela' => [
            'value' => 0,
            'class' => 'TipoRecuperacaoParalela',
            'file' => 'RegraAvaliacao/Model/TipoRecuperacaoParalela.php',
            'null' => true
        ],
        'regraDiferenciada' => [
            'value' => null,
            'class' => 'RegraDataMapper',
            'file' => 'RegraAvaliacao/Model/RegraDataMapper.php',
            'null' => true
        ]
    ];

    /**
     * @var array
     */
    protected $_regraRecuperacoes = [];

    /**
     * @see Entity::getDataMapper()
     */
    public function getDataMapper()
    {
        if (is_null($this->_dataMapper)) {

            $this->setDataMapper(new RegraDataMapper());
        }

        return parent::getDataMapper();
    }

    /**
     * @see Validatable::getDefaultValidatorCollection()
     */
    public function getDefaultValidatorCollection()
    {
        // Enums
        $tipoNotaValor = TipoValor::getInstance();
        $tipoProgressao = TipoProgressao::getInstance();
        $tipoParecerDescritivo = TipoParecerDescritivo::getInstance();
        $tipoPresenca = TipoPresenca::getInstance();
        $tipoRecuperacaoParalela = TipoRecuperacaoParalela::getInstance();

        // ids de fórmulas de média
        $formulaMedia = $this->getDataMapper()->findFormulaMediaFinal();
        $formulaMedia = Entity::entityFilterAttr($formulaMedia, 'id');

        // ids de fórmulas de recuperação
        $formulaRecuperacao = $this->getDataMapper()->findFormulaMediaRecuperacao();
        $formulaRecuperacao = Entity::entityFilterAttr($formulaRecuperacao, 'id');
        $formulaRecuperacao[0] = null;

        // ids de regras diferenciadas
        $regraDiferenciada = $this->getDataMapper()->findAll();
        $regraDiferenciada = Entity::entityFilterAttr($regraDiferenciada, 'id');
        $regraDiferenciada[0] = null;

        // ids de tabelas de arredondamento
        $tabelas = $this->getDataMapper()->findTabelaArredondamento($this);
        $tabelas = Entity::entityFilterAttr($tabelas, 'id');

        // Instituições
        $instituicoes = array_keys(Finder::getInstituicoes());

        // Fórmula de média é obrigatória?
        $isFormulaMediaRequired = true;

        // Média é obrigatória?
        $isMediaRequired = true;

        if ($this->get('tipoNota') == TipoValor::NENHUM) {
            $isFormulaMediaRequired = false;
            $isMediaRequired = false;

            // Aceita somente o valor NULL quando o tipo de nota é Nenhum.
            $formulaMedia = $formulaMedia + [null];
        }

        return [
            'instituicao' => new _Validate_Choice([
                'choices' => $instituicoes
            ]),
            'nome' => new _Validate_String([
                'min' => 5, 'max' => 50
            ]),
            'formulaMedia' => new _Validate_Choice([
                'choices' => $formulaMedia,
                'required' => $isFormulaMediaRequired
            ]),
            'formulaRecuperacao' => new _Validate_Choice([
                'choices' => $formulaRecuperacao,
                'required' => false
            ]),
            'regraDiferenciada' => new _Validate_Choice([
                'choices' => $regraDiferenciada,
                'required' => false
            ]),
            'tipoNota' => new _Validate_Choice([
                'choices' => $tipoNotaValor->getKeys()
            ]),
            'tipoProgressao' => new _Validate_Choice([
                'choices' => $tipoProgressao->getKeys()
            ]),
            'tabelaArredondamento' => new _Validate_Choice([
                'choices' => $tabelas,
                'choice_error' => 'A tabela de arredondamento selecionada não ' .
                    'corresponde ao sistema de nota escolhido.'
            ]),
            'tabelaArredondamentoConceitual' => new _Validate_Choice([
                'choices' => $tabelas,
                'choice_error' => 'A tabela de arredondamento selecionada não ' .
                    'corresponde ao sistema de nota escolhido.'
            ]),
            'parecerDescritivo' => new _Validate_Choice([
                'choices' => $tipoParecerDescritivo->getKeys()
            ]),
            'tipoPresenca' => new _Validate_Choice([
                'choices' => $tipoPresenca->getKeys()
            ]),
            'media' => $this->validateIfEquals(
                'tipoProgressao',
                TipoProgressao::CONTINUADA,
                'Numeric',
                ['required' => $isMediaRequired, 'min' => 1, 'max' => 10],
                ['required' => $isMediaRequired, 'min' => 0, 'max' => 10]
            ),
            'porcentagemPresenca' => new _Validate_Numeric([
                'min' => 1, 'max' => 100
            ]),
            'mediaRecuperacao' => $this->validateIfEquals(
                'tipoProgressao',
                TipoProgressao::CONTINUADA,
                'Numeric',
                ['required' => $isMediaRequired, 'min' => 1, 'max' => 14],
                ['required' => $isMediaRequired, 'min' => 0, 'max' => 14]
            ),
            'tipoRecuperacaoParalela' => new _Validate_Choice([
                'choices' => $tipoRecuperacaoParalela->getKeys()
            ]),
            'mediaRecuperacaoParalela' => new _Validate_String([
                'min' => 1, 'max' => 10
            ])
        ];
    }

    /**
     * Método finder para RegraRecuperacao. Wrapper simples
     * para o mesmo método de RegraAvaliacao_Model_TabelaDataMapper.
     *
     * @return array
     */
    public function findRegraRecuperacao()
    {
        if (0 == count($this->_regraRecuperacoes)) {
            $this->_regraRecuperacoes = $this->getDataMapper()->findRegraRecuperacao($this);
        }

        return $this->_regraRecuperacoes;
    }

    public function getRegraRecuperacaoByEtapa($etapa)
    {
        foreach ($this->findRegraRecuperacao() as $key => $_regraRecuperacao) {
            if (in_array($etapa, $_regraRecuperacao->getEtapas())) {
                return $_regraRecuperacao;
            }
        }

        return null;
    }

    /**
     * Pega a nota máxima permitida para a recuperação
     *
     * @param $etapa
     *
     * @return float
     */
    public function getNotaMaximaRecuperacao($etapa)
    {
        $tipoRecuperacaoParalela = $this->get('tipoRecuperacaoParalela');

        if ($tipoRecuperacaoParalela != TipoRecuperacaoParalela::USAR_POR_ETAPAS_ESPECIFICAS) {
            return $this->notaMaximaGeral;
        }

        return $this->getRegraRecuperacaoByEtapa($etapa)->notaMaxima;
    }

    /**
     * @see Entity::__toString()
     */
    public function __toString()
    {
        return $this->nome;
    }
}

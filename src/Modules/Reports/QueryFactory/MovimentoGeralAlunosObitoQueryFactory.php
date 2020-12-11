<?php

namespace iEducar\Modules\Reports\QueryFactory;

class MovimentoGeralAlunosObitoQueryFactory extends QueryFactory
{
    protected $keys = [
        'ano',
        'escola',
        'seleciona_curso',
        'curso',
        'data_inicial',
        'data_final'
    ];

    protected $defaults = [
        'seleciona_curso' => 0,
        'curso' => 0
    ];

    protected $query = <<<'SQL'
        select
            m.cod_matricula,
            Pessoa.nome,
            turma.nm_turma
        from
            pmieducar.Matricula m
        inner join
            pmieducar.matricula_turma mt
                on mt.ref_cod_matricula = m.cod_matricula
        inner join
            pmieducar.aluno
                on aluno.cod_aluno = m.ref_cod_aluno
        inner join
            cadastro.Pessoa
                on Pessoa.idpes = aluno.ref_idpes
        inner join
            pmieducar.turma
                on turma.cod_turma = mt.ref_cod_turma
        where true
            and m.ref_ref_cod_escola = :escola
            and m.ano = :ano
            and m.ref_ref_cod_serie in (
                select ref_cod_serie
                from Modules.config_movimento_geral
                inner join pmieducar.serie on serie.cod_serie = config_movimento_geral.ref_cod_serie
                where true
                    and (case
                        when :seleciona_curso = 0 then
                            true
                        else
                            serie.ref_cod_curso in (:curso)
                    end)
            )
            and m.aprovado = 15
            and coalesce(mt.data_exclusao, m.data_cancel) between :data_inicial::date and :data_final::date
        order by
            Pessoa.nome asc
SQL;
}

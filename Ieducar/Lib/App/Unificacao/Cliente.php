<?php

namespace iEducarLegacy\Lib\App\Unificacao;

/**
 * Class Cliente
 * @package iEducarLegacy\Lib\App\Unificacao
 */
class Cliente extends Base
{
    protected $chavesManterPrimeiroVinculo = [
        [
            'tabela' => 'pmieducar.cliente',
            'coluna' => 'cod_cliente'
        ],
        [
            'tabela' => 'pmieducar.cliente_suspensao',
            'coluna' => 'ref_cod_cliente'
        ],
        [
            'tabela' => 'pmieducar.cliente_tipo_cliente',
            'coluna' => 'ref_cod_cliente'
        ]
    ];

    protected $chavesManterTodosVinculos = [
        [
            'tabela' => 'pmieducar.exemplar_emprestimo',
            'coluna' => 'ref_cod_cliente'
        ],
        [
            'tabela' => 'pmieducar.pagamento_multa',
            'coluna' => 'ref_cod_cliente'
        ],
        [
            'tabela' => 'pmieducar.reservas',
            'coluna' => 'ref_cod_cliente'
        ],
    ];
}

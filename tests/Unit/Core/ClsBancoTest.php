<?php

use Tests\TestCase;

require_once 'include/pmieducar/ClienteSuspensao.php';

class ClsBancoTest extends TestCase
{
    public function testDoCountFromObj()
    {
        $db = new Banco();

        $obj = new ClienteSuspensao();

        $this->assertNotEquals(true, is_null($db->doCountFromObj($obj)));
    }

    public function testFormatacaoDeValoresBooleanos()
    {
        $data = [
            'id' => 1,
            'hasChild' => true
        ];

        $db = new Banco();

        $formatted = $db->formatValues($data);
        $this->assertSame('t', $formatted['hasChild']);

        $data['hasChild'] = false;
        $formatted = $db->formatValues($data);

        $this->assertSame('f', $formatted['hasChild']);
    }

    public function testOpcaoDeLancamentoDeExcecaoEFalsePorPadrao()
    {
        $db = new Banco();

        $this->assertFalse($db->getThrowException());
    }

    public function testConfiguracaoDeOpcaoDeLancamentoDeExcecao()
    {
        $db = new Banco();
        $db->setThrowException(true);

        $this->assertTrue($db->getThrowException());
    }

    public function testFetchTipoArrayDeResultadosDeUmaQuery()
    {
        $db = new Banco();
        $db->Consulta('SELECT spcname FROM pg_tablespace');

        $row = $db->ProximoRegistro();
        $row = $db->Tupla();

        $this->assertNotNull($row[0]);
        $this->assertNotNull($row['spcname']);
    }

    public function testFetchTipoAssocDeResultadosDeUmaQuery()
    {
        $db = new Banco(['fetchMode' => Banco::FETCH_ASSOC]);
        $db->Consulta('SELECT spcname FROM pg_tablespace');

        $row = $db->ProximoRegistro();
        $row = $db->Tupla();

        $this->assertFalse(array_key_exists(0, $row));
        $this->assertNotNull($row['spcname']);
    }
}

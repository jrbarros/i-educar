<?php

use Tests\TestCase;

require_once 'include/pmieducar/QuadroHorarioHorarios.php';

class ClsPmieducarQuadroHorarioHorariosTest extends TestCase
{
    /**
     * Testa o método substituir_servidor()
     */
    public function testSubstituirServidor()
    {
        $stub = $this->getMockBuilder('QuadroHorarioHorarios')->getMock();

        $stub->expects($this->any())
            ->method('substituir_servidor')
            ->will($this->returnValue(true));

        $this->assertTrue($stub->substituir_servidor(1));
    }
}

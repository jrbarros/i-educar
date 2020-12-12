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
 * @author      Eriksen Costa Paixão <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     Enum
 * @subpackage  UnitTests
 * @since       Arquivo disponível desde a versão 1.1.0
 * @version     $Id$
 */

require_once __DIR__.'/_stub/Enum1.php';
require_once __DIR__.'/_stub/Enum2.php';
require_once __DIR__.'/_stub/EnumCoffee.php';
require_once __DIR__.'/_stub/EnumString.php';

/**
 * CoreExt_EnumTest class.
 *
 * @author      Eriksen Costa Paixão <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     Enum
 * @subpackage  UnitTests
 * @since       Classe disponível desde a versão 1.1.0
 * @version     @@package_version@@
 */
class CoreExt_EnumTest extends PHPUnit\Framework\TestCase
{
  public function testRetornaTodosOsValoresDoEnum()
  {
    $enum = _Enum1Stub::getInstance();
    $this->assertEquals(array(1), $enum->getKeys());
    $enum = _Enum2Stub::getInstance();
    $this->assertEquals(array(2), $enum->getKeys());
    $enum = _EnumCoffeeStub::getInstance();
    $this->assertEquals(array(0, 1, 2), $enum->getKeys());
    $enum = _EnumStringStub::getInstance();
    $this->assertEquals(array('red'), $enum->getKeys());
  }

  public function testItemDeEnumRetornaDescricao()
  {
    $enum = _Enum1Stub::getInstance();
    $this->assertEquals(1, $enum->getValue(_Enum1Stub::ONE));
    $enum = _Enum2Stub::getInstance();
    $this->assertEquals(2, $enum->getValue(_Enum2Stub::TWO));
    $enum = _EnumCoffeeStub::getInstance();
    $this->assertEquals('Mocha', $enum->getValue(_EnumCoffeeStub::MOCHA));
    $enum = _EnumStringStub::getInstance();
    $this->assertEquals('#FF0000', $enum->getValue(_EnumStringStub::RED));
  }

  public function testEnumAcessadosComoArray()
  {
    $enum = _Enum1Stub::getInstance();
    $this->assertEquals(1, $enum[_Enum1Stub::ONE]);
    $enum = _Enum2Stub::getInstance();
    $this->assertEquals(2, $enum[_Enum2Stub::TWO]);
    $enum = _EnumCoffeeStub::getInstance();
    $this->assertEquals('Mocha', $enum[_EnumCoffeeStub::MOCHA]);
    $enum = _EnumStringStub::getInstance();
    $this->assertEquals('#FF0000', $enum[_EnumStringStub::RED]);
  }

  public function testEnumAcessosDiversosComoArray()
  {
    $enum = _Enum1Stub::getInstance();
    $this->assertTrue(isset($enum[_Enum1Stub::ONE]));

    $this->assertEquals(array(1), $enum->getValues());
    $this->assertEquals(array(1), $enum->getKeys());
    $this->assertEquals(array(1 => 1), $enum->getEnums());
    $this->assertEquals(1, $enum->getKey(_Enum1Stub::ONE));

    $enum = _EnumStringStub::getInstance();
    $this->assertTrue(isset($enum[_EnumStringStub::RED]));

    $this->assertEquals(array('#FF0000'), $enum->getValues());
    $this->assertEquals(array('red'), $enum->getKeys());
    $this->assertEquals(array('red' => '#FF0000'), $enum->getEnums());
    $this->assertEquals('red', $enum->getKey('#FF0000'));
  }

  /**
   * @expectedException CoreExtensionException
   */
  public function testEnumEApenasLeitura()
  {
    $enum = _Enum1Stub::getInstance();
    $enum['foo'] = 'bar';
  }

  /**
   * @expectedException CoreExtensionException
   */
  public function testEnumNaoPermiteRemoverEntrada()
  {
    $enum = _Enum1Stub::getInstance();
    unset($enum['foo']);
  }
}

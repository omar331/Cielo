<?php
/**
 * Cielo
 *
 * Cliente para o Web Service da Cielo.
 *
 * O Web Service permite efetuar vendas com cartões de bandeira
 * VISA e Mastercard, tanto no débito quanto em compras a vista ou parceladas.
 *
 * Licença
 * Este código fonte está sob a licença GPL-3.0+
 *
 * @category   Library
 * @package    MrPrompt\Cielo\Tests
 * @subpackage Cliente
 * @copyright  Thiago Paes <mrprompt@gmail.com> (c) 2013
 * @license    GPL-3.0+
 */
namespace MrPrompt\Cielo\Tests;

use MrPrompt\Cielo\Cartao;
use ReflectionProperty;

class CartaoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @covers \MrPrompt\Cielo\Cartao::getCartao
     */
    public function proprieadadeComNumeroDoCartaoNaoPodeSerModificado()
    {
        $cartao = new Cartao();

        $reflection = new \ReflectionProperty(Cartao::class, 'cartao');
        $reflection->setAccessible(true);
        $reflection->setValue($cartao, 'Elo');

        $this->assertEquals('Elo', $cartao->getCartao());
    }

    /**
     * @test
     *
     * @covers \MrPrompt\Cielo\Cartao::setCartao
     * @covers \MrPrompt\Cielo\Cartao::getCartao
     */
    public function numeroDoCartaoDeveSerValido()
    {
        $cartao = new Cartao();
        $cartao->setCartao('4923993827951627');

        $this->assertEquals('4923993827951627', $cartao->getCartao());
    }

    /**
     * @test
     * @covers \MrPrompt\Cielo\Cartao::setCartao
     * @covers \MrPrompt\Cielo\Cartao::getCartao
     */
    public function caracteresNaoNumericosDevemSerRemovidosAoConfigurarOCartao()
    {
        $cartao = new Cartao();
        $cartao->setCartao('4a9a2a3a9a9a3a8aa2a7a9a5a1a6a2a7a');

        $this->assertEquals('4923993827951627', $cartao->getCartao());
    }

    /**
     * @test
     * @covers \MrPrompt\Cielo\Cartao::setCartao
     * @expectedException InvalidArgumentException
     */
    public function numeroDoCartaoNaoPodeSerVazio()
    {
        $cartao = new Cartao();
        $cartao->setCartao('');
    }

    /**
     * @test
     * @covers \MrPrompt\Cielo\Cartao::setCartao
     * @expectedException InvalidArgumentException
     */
    public function numeroDoCartaoSerUmNumeroInvalido()
    {
        $cartao = new Cartao();
        $cartao->setCartao('49239938');
    }

    /**
     * @test
     *
     * @covers \MrPrompt\Cielo\Cartao::getIndicador
     */
    public function indicadorDeCodigoDeSegurancaDeveRetornarValorDaPropriedadeIndicador()
    {
        $cartao = new Cartao();

        $reflection = new \ReflectionProperty(Cartao::class, 'indicador');
        $reflection->setAccessible(true);
        $reflection->setValue($cartao, 1);

        $this->assertEquals(1, $cartao->getIndicador());
    }

    /**
     * @test
     * @covers       \MrPrompt\Cielo\Cartao::setIndicador
     * @covers       \MrPrompt\Cielo\Cartao::getIndicador
     * @dataProvider indicadoresValidos
     */
    public function indicadorDeCodigoDeSegurancaDeveSerValido($valor)
    {
        $cartao = new Cartao();
        $cartao->setIndicador($valor);

        $this->assertEquals($valor, $cartao->getIndicador());
    }

    /**
     * data provider
     *
     * @return array
     */
    public function indicadoresValidos()
    {
        return array(
            array(0),
            array(1),
            array(2),
            array(9)
        );
    }

    /**
     * @test
     * @covers       \MrPrompt\Cielo\Cartao::setIndicador
     * @dataProvider indicadoresInvalidos
     * @expectedException \InvalidArgumentException
     */
    public function deveLancarErroCasoRecebaIndicadorInvalido($valor)
    {
        $cartao = new Cartao();
        $cartao->setIndicador($valor);
    }

    public function indicadoresInvalidos()
    {
        return array(
            array(3),
            array(-1),
            array(null),
            array('d'),
            array(array('d')),
            array((object)array('d' => 'asdad')),
        );
    }

    /**
     * @test
     *
     * @covers \MrPrompt\Cielo\Cartao::getCodigoSeguranca
     */
    public function codigoSegurancaDeveRetornarPropriedadeCodigoSeguranca()
    {
        $cartao = new Cartao();

        $reflection = new \ReflectionProperty(Cartao::class, 'codigoSeguranca');
        $reflection->setAccessible(true);
        $reflection->setValue($cartao, '1');

        $this->assertEquals('1', $cartao->getCodigoSeguranca());
    }

    /**
     * @test
     *
     * @covers \MrPrompt\Cielo\Cartao::setCodigoSeguranca
     * @covers \MrPrompt\Cielo\Cartao::getCodigoSeguranca
     */
    public function codigoDeSegurancaDeveSerNumerico()
    {
        $cartao = new Cartao();
        $cartao->setCodigoSeguranca(123);

        $this->assertEquals(123, $cartao->getCodigoSeguranca());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function codigoDeSegurancaNaoPodeConterCaracteresAlfabeticos()
    {
        $cartao = new Cartao();
        $cartao->setCodigoSeguranca('aaa');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function codigoDeSegurancaNaoPodeConterPontuacao()
    {
        $cartao = new Cartao();
        $cartao->setCodigoSeguranca('22.');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function codigoDeSegurancaNaoPodeConterEspacos()
    {
        $cartao = new Cartao();
        $cartao->setCodigoSeguranca('22 2');
    }

    /**
     * @test
     */
    public function nomePortadorDeveSerAlfanumerico()
    {
        $cartao = new Cartao();
        $cartao->setNomePortador('Thiago Paes 1000');

        $this->assertEquals('Thiago Paes 1000', $cartao->getNomePortador());
    }

    /**
     * @test
     */
    public function nomePortadorDeveSerTruncadoEm50Caracteres()
    {
        $cartao = new Cartao();
        $cartao->setNomePortador(str_repeat('a', 60));

        $this->assertEquals(str_repeat('a', 50), $cartao->getNomePortador());
    }

    /**
     * @test
     * @dataProvider nomesInvalidos
     * @expectedException \InvalidArgumentException
     */
    public function nomePortadorNaoPodeSerInvalido($valor)
    {
        $cartao = new Cartao();
        $cartao->setNomePortador($valor);
    }

    public function nomesInvalidos()
    {
        return array(
            array(null),
            array(''),
            array(array('djkhkdh')),
            array((object)array('aa' => 'dsfsdff'))
        );
    }

    /**
     * @test
     */
    public function validadeDeveSerInformadaNoFormatoCorreto()
    {
        $cartao = new Cartao();
        $cartao->setValidade('201606', '201302');

        $this->assertEquals('201606', $cartao->getValidade());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function validadeNaoPodeConterCaracteresAlfabeticos()
    {
        $cartao = new Cartao();
        $cartao->setValidade('aaa');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function validadeNaoPodeConterPontuacao()
    {
        $cartao = new Cartao();
        $cartao->setValidade('22.');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function validadeNaoPodeConterEspacos()
    {
        $cartao = new Cartao();
        $cartao->setValidade('22 2');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function naoPodeSerUtilizadaValidadeNoPassado()
    {
        $cartao = new Cartao();
        $cartao->setValidade('201210', '201305');
    }

    /**
     * @test
     */
    public function bandeiraDeveSerValido()
    {
        $cartao = new Cartao();
        $cartao->setBandeira('visa');

        $this->assertEquals('visa', $cartao->getBandeira());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function bandeiraDeveReceberApenasMinusculos()
    {
        $cartao = new Cartao();
        $cartao->setBandeira('MASTERCARD');

        $this->assertEquals('mastercard', $cartao->getBandeira());
    }

    /**
     * @test
     * @dataProvider bandeirasInvalidas
     * @expectedException \InvalidArgumentException
     */
    public function deveLancarErroCasoRecebaBandeiraInvalido($valor)
    {
        $cartao = new Cartao();
        $cartao->setBandeira($valor);
    }

    public function bandeirasInvalidas()
    {
        return array(
            array(3),
            array(-1),
            array(null),
            array('d'),
            array(array('d')),
            array((object)array('d' => 'ADSFS')),
        );
    }

    /**
     * @test
     *
     * @covers \MrPrompt\Cielo\Cartao::getBandeiras
     */
    public function listarBandeirasDeveRetornarUmArray()
    {
        $cartao = new Cartao();
        $bandeiras = $cartao->getBandeiras();

        $this->assertTrue(is_array($bandeiras));
    }

    /**
     * @test
     *
     * @covers \MrPrompt\Cielo\Cartao::getToken
     */
    public function getTokenDeveRetornarPropriedadeTokenInalterada()
    {
        $cartao = new Cartao();

        $reflection = new \ReflectionProperty(Cartao::class, 'token');
        $reflection->setAccessible(true);
        $reflection->setValue($cartao, 'fooo');

        $this->assertEquals('fooo', $cartao->getToken());
    }

    /**
     * @test
     *
     * @covers \MrPrompt\Cielo\Cartao::setToken
     */
    public function setTokenDeveRetornarValorDoTokenInalterado()
    {
        $cartao = new Cartao();
        $token  = $cartao->setToken('fooo');

        $this->assertEquals('fooo', $token);
    }

    /**
     * @test
     *
     * @covers \MrPrompt\Cielo\Cartao::hasToken
     */
    public function hasTokenDeveRetornarTrueSeTokenEstiverDefinido()
    {
        $cartao = new Cartao();

        $reflection = new \ReflectionProperty(Cartao::class, 'token');
        $reflection->setAccessible(true);
        $reflection->setValue($cartao, 'fooo');

        $this->assertTrue($cartao->hasToken());
    }
}
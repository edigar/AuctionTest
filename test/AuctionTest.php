<?php

require "..\User.php";
require_once "CriadorDeLeilao.php";

class AuctionTest extends PHPUnit\Framework\TestCase {

    private $joao;
    private $jose;
    private $maria;

    public function setUp() {
        $this->joao = new User("João");
        $this->jose = new User("José");
        $this->maria = new User("Maria");
    }

    public static function setUpBeforeClass() {
      var_dump("before class");
    }

    public static function tearDownAfterClass() {
      var_dump("after class");
    }

    public function testDeveReceberUmLance() {
        $criador = new CriadorDeLeilao();
        $leilao = $criador->para("Macbook Pro 15")->constroi();

        $this->assertEquals(0, count($leilao->getLances()));

        $leilao->propoe(new Bid(new User("Steve Jobs"), 2000));

        $this->assertEquals(1, count($leilao->getLances()));
        $this->assertEquals(2000, $leilao->getLances()[0]->getValor(), 0.00001);
    }

    public function testDeveReceberVariosLances() {
      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Macbook Pro 15")
              ->lance($this->joao, 2000.0)
              ->lance($this->maria, 3000.0)
              ->constroi();

        $this->assertEquals(2, count($leilao->getLances()));
        $this->assertEquals(2000, $leilao->getLances()[0]->getValor(), 0.00001);
        $this->assertEquals(3000, $leilao->getLances()[1]->getValor(), 0.00001);
    }

    public function testNaoDeveAceitarDoisLancesSeguidosDoMesmoUsuario() {
      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Macbook Pro 15")
              ->lance($this->joao, 2000.0)
              ->lance($this->joao, 3000.0)
              ->constroi();

        $this->assertEquals(1, count($leilao->getLances()));
        $this->assertEquals(2000, $leilao->getLances()[0]->getValor(), 0.00001);
    }

    public function testNaoDeveAceitarMaisDoQue5LancesDeUmMesmoUsuario() {
      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Macbook Pro 15")
              ->lance($this->joao, 2000.0)
              ->lance($this->maria, 3000.0)
              ->lance($this->joao, 4000.0)
              ->lance($this->maria, 5000.0)
              ->lance($this->joao, 6000.0)
              ->lance($this->maria, 7000.0)
              ->lance($this->joao, 8000.0)
              ->lance($this->maria, 9000.0)
              ->lance($this->joao, 10000.0)
              ->lance($this->maria, 11000.0)
              ->lance($this->joao, 12000.0) // deve ser ignorado
              ->constroi();

        $this->assertEquals(10, count($leilao->getLances()));
        $ultimo = count($leilao->getLances()) - 1;
        $ultimoLance = $leilao->getLances()[$ultimo];
        $this->assertEquals(11000.0, $ultimoLance->getValor(), 0.00001);
    }

    public function testDeveDobrarUltimoLanceDoUsuário() {
        $criador = new CriadorDeLeilao();
        $leilao = $criador->para("Macbook Pro 15")
                ->lance($this->joao, 2000.0)
                ->lance($this->maria, 3000.0)
                ->constroi();

        $leilao->dobraLance($this->joao);

        $this->assertEquals(3, count($leilao->getLances()));
        $this->assertEquals(4000, $leilao->getLances()[2]->getValor(), 0.00001);
    }

    public function testNaoDeveDobrarUltimoLanceDoUsuárioSemUltimoLance() {
      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Macbook Pro 15")
              ->lance($this->joao, 2000.0)
              ->constroi();

        $leilao->dobraLance($this->maria);

        $this->assertEquals(1, count($leilao->getLances()));
        $this->assertEquals(2000, $leilao->getLances()[0]->getValor(), 0.00001);
    }

    public function testNaoDeveDobrarUltimoLanceSeguidoDoMesmoUsuario() {
        $criador = new CriadorDeLeilao();
        $leilao = $criador->para("Macbook Pro 15")
                ->lance($this->joao, 2000.0)
                ->constroi();

        $leilao->dobraLance($this->joao);

        $this->assertEquals(1, count($leilao->getLances()));
        $this->assertEquals(2000, $leilao->getLances()[0]->getValor(), 0.00001);
    }

    public function testNaoDeveAceitarDobrarEmMaisDoQue5LancesDeUmMesmoUsuario() {
      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Macbook Pro 15")
              ->lance($this->joao, 2000.0)
              ->lance($this->maria, 3000.0)
              ->lance($this->joao, 4000.0)
              ->lance($this->maria, 5000.0)
              ->lance($this->joao, 6000.0)
              ->lance($this->maria, 7000.0)
              ->lance($this->joao, 8000.0)
              ->lance($this->maria, 9000.0)
              ->lance($this->joao, 10000.0)
              ->lance($this->maria, 11000.0)
              ->constroi();

        // deve ser ignorado
        $leilao->dobraLance($this->joao);

        $this->assertEquals(10, count($leilao->getLances()));
        $ultimo = count($leilao->getLances()) - 1;
        $ultimoLance = $leilao->getLances()[$ultimo];
        $this->assertEquals(11000.0, $ultimoLance->getValor(), 0.00001);
    }


    //ANO BISSEXTO
    public function testIsBissexto() {
        $leilao = new Auction("Ano");

        $this-> assertEquals(true, $leilao->isBissexto(1004));
        $this-> assertEquals(true, $leilao->isBissexto(1292));
        $this-> assertEquals(true, $leilao->isBissexto(1512));
        $this-> assertEquals(true, $leilao->isBissexto(1640));
        $this-> assertEquals(true, $leilao->isBissexto(1708));
        $this-> assertEquals(true, $leilao->isBissexto(1980));
        $this-> assertEquals(true, $leilao->isBissexto(2020));
    }

    public function testNaoIsBissexto() {
        $leilao = new Auction("Ano");

        $this-> assertEquals(false, $leilao->isBissexto(1101));
        $this-> assertEquals(false, $leilao->isBissexto(1418));
        $this-> assertEquals(false, $leilao->isBissexto(1555));
        $this-> assertEquals(false, $leilao->isBissexto(1700));
        $this-> assertEquals(false, $leilao->isBissexto(1982));
        $this-> assertEquals(false, $leilao->isBissexto(2018));
        $this-> assertEquals(false, $leilao->isBissexto(2021));
    }
}

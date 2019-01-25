<?php

require "..\User.php";
require_once "CriadorDeLeilao.php";

class ValuerTest extends PHPUnit\Framework\TestCase {

    private $leiloeiro;
    private $joao;
    private $jose;
    private $maria;

    public function setUp() {
        $this->leiloeiro = new Valuer();
        $this->joao = new User("João");
        $this->jose = new User("José");
        $this->maria = new User("Maria");
    }

    public function tearDown() {

    }

    public function testAceitaLeilaoEmOrdemCrescente() {

      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Playstation 3 Novo")
              ->lance($this->joao, 250.0)
              ->lance($this->maria, 300.0)
              ->lance($this->jose, 400.0)
              ->constroi();

        $this->leiloeiro->avalia($leilao);

        $maiorEsperado = 400;
        $menorEsperado = 250;

        $this->assertEquals($this->leiloeiro->getMaiorLance(), $maiorEsperado);
        $this->assertEquals($this->leiloeiro->getMenorLance(), $menorEsperado);

    }

    public function testgetAverageInAscendingOrder() {
      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Playstation 3 Novo")
              ->lance($this->joao, 250.0)
              ->lance($this->maria, 300.0)
              ->lance($this->jose, 400.0)
              ->constroi();

        $this->leiloeiro->avalia($leilao);

        $esperado = (250 + 300 + 400)/3;

        $this->assertEquals($this->leiloeiro->getAverage(), $esperado);
    }

    public function testAceitaLeilaoComUmLance() {

      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Playstation 3 Novo")
              ->lance($this->joao, 250.0)
              ->constroi();

        $this->leiloeiro->avalia($leilao);

        $maiorEsperado = 250;
        $menorEsperado = 250;

        $this->assertEquals($this->leiloeiro->getMaiorLance(),$maiorEsperado);
        $this->assertEquals($this->leiloeiro->getMenorLance(),$menorEsperado);
    }

    public function testPegaOsTresMaiores() {

      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Playstation 3 Novo")
              ->lance($this->joao, 100.0)
              ->lance($this->maria, 200.0)
              ->lance($this->joao, 300.0)
              ->lance($this->maria, 400.0)
              ->constroi();

        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getTresMaiores();

        $this->assertEquals(3,count($maiores));
        $this->assertEquals(400, $maiores[0]->getValor());
        $this->assertEquals(300, $maiores[1]->getValor());
        $this->assertEquals(200, $maiores[2]->getValor());
    }

    public function testTwoBidList() {

      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Playstation 3 Novo")
              ->lance($this->joao, 250.0)
              ->lance($this->maria, 300.0)
              ->constroi();

        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getTresMaiores();

        $this->assertEquals(count($maiores),2);
        $this->assertEquals($maiores[0]->getValor(),300);
        $this->assertEquals($maiores[1]->getValor(),250);
    }

    public function testEmptyBidList() {

      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Playstation 3 Novo")->constroi();

        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getTresMaiores();

        $this->assertEquals(count($maiores),0);
    }

    public function testRandomValues() {

      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Playstation 3 Novo")
              ->lance($this->joao, 250.0)
              ->lance($this->maria, 300.0)
              ->lance($this->jose, 400.0)
              ->lance($this->joao, 120.0)
              ->lance($this->maria, 450.0)
              ->lance($this->jose, 630.0)
              ->lance($this->joao, 700.0)
              ->constroi();

        $this->leiloeiro->avalia($leilao);

        $maiorEsperado = 700;
        $menorEsperado = 120;

        $this->assertEquals($this->leiloeiro->getMaiorLance(),$maiorEsperado);
        $this->assertEquals($this->leiloeiro->getMenorLance(),$menorEsperado);
    }

    public function testAceitaLeilaoEmOrdemDecrescente() {

      $criador = new CriadorDeLeilao();
      $leilao = $criador->para("Playstation 3 Novo")
              ->lance($this->joao, 400.0)
              ->lance($this->maria, 300.0)
              ->lance($this->jose, 250.0)
              ->constroi();

        $this->leiloeiro->avalia($leilao);

        $maiorEsperado = 400;
        $menorEsperado = 250;

        $this->assertEquals($maiorEsperado, $this->leiloeiro->getMaiorLance());
        $this->assertEquals($menorEsperado, $this->leiloeiro->getMenorLance());
    }

    /**
    * @expectedException InvalidArgumentException
    **/
    public function testeDeveRecusarLeilaoSemLance() {
        $criador = new CriadorDeLeilao();
        $leilao = $criador->para("Playstation 4")->constroi();
        $this->leiloeiro->avalia($leilao);
    }
}

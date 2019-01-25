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

    /**
    * @expectedException InvalidArgumentException
    **/
    public function testDeveRecusarLanceZero() {
        $criador = new CriadorDeLeilao();
        $leilao = $criador->para("Playstation 4")->lance($this->joao, 0)->constroi();
    }

    /**
    * @expectedException InvalidArgumentException
    **/
    public function testDeveRecusarLanceNegativo() {
        $criador = new CriadorDeLeilao();
        $leilao = $criador->para("Playstation 4")->lance($this->joao, -200)->constroi();
    }

}

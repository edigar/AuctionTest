<?php

require_once "..\Bid.php";
require_once "..\Auction.php";
require_once "..\Valuer.php";

class CriadorDeLeilao {

    private $leilao;

    public function para($descricao) {
        $this->leilao = new Auction($descricao);
        return $this;
    }

    public function lance(User $usuario, $valor) {
        $this->leilao->propoe(new Bid($usuario, $valor));
        return $this;
    }

    public function constroi() {
        return $this->leilao;
    }
}

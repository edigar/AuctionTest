<?php

//namespace Auction;

class Valuer {

    private $maiorDeTodos = -INF;
    private $menorDeTodos = INF;
    private $media;
    private $maiores;

    public function avalia(Auction $leilao) {

        if(count($leilao->getLances()) == 0) {
            throw new InvalidArgumentException("Um leilão precisa de pelo menos 1 lance");
            //throw new \Exception("Um leilão precisa de pelo menos 1 lance");
        }
        $soma = 0;
        foreach($leilao->getLances() as $lance) {
            if($lance->getValor() > $this->maiorDeTodos) $this->maiorDeTodos = $lance->getValor();
            if($lance->getValor() < $this->menorDeTodos) $this->menorDeTodos = $lance->getValor();
            $soma += $lance->getValor();
        }
        if(count($leilao->getLances()) > 0) $this->media = $soma / count($leilao->getLances());
        $this->pegaOsMaioresNo($leilao);
    }

    public function pegaOsMaioresNo(Auction $leilao) {

        $lances = $leilao->getLances();
        usort($lances,function ($a,$b) {
            if($a->getValor() == $b->getValor()) return 0;
            return ($a->getValor() < $b->getValor()) ? 1 : -1;
        });

        $this->maiores = array_slice($lances, 0,3);
    }

    public function getMaiorLance() {
        return $this->maiorDeTodos;
    }

    public function getMenorLance() {
        return $this->menorDeTodos;
    }

    public function getAverage() {
        return $this->media;
    }

    public function getTresMaiores() {
        return $this->maiores;
    }
}

<?php

//namespace Auction;

class Auction {
		private $descricao;
		private $lances;

		function __construct($descricao) {
			$this->descricao = $descricao;
			$this->lances = array();
		}

    public function propoe(Bid $lance) {

        if(count($this->lances) == 0 || $this->podeDarLance($lance->getUsuario())) {
            $this->lances[] = $lance;
        }
    }

    public function dobraLance(User $usuario) {
        $ultimoLance = $this->getUltimoLanceDo($usuario);
        if($ultimoLance != null) $this->propoe(new Bid($usuario, $ultimoLance->getValor() * 2));
    }

    private function getUltimoLanceDo(User $usuario) {
      $userBid = null;
      foreach($this->lances as $lance) {
          if($lance->getUsuario()->getNome() == $usuario->getNome()) $userBid = $lance;
      }
      return $userBid;
    }

    private function podeDarLance(User $usuario) {
        return !($this->ultimoLanceDado()->getUsuario()->getNome() == $usuario->getNome()) && $this->qtdDelancesDo($usuario) < 5;
    }

    private function qtdDelancesDo(User $usuario) {
        $total = 0;
        foreach($this->lances as $lance) {
            if($lance->getUsuario()->getNome() == $usuario->getNome()) $total++;
        }
        return $total;
    }

    private function ultimoLanceDado() {
        return $this->lances[count($this->lances) - 1];
    }

		public function getDescricao() {
			return $this->descricao;
		}

		public function getLances() {
			return $this->lances;
		}

    public function isBissexto(int $ano) {
      if (($ano % 4 == 0 && $ano % 100 != 0) || $ano % 400 == 0) return true;
      return false;
    }
}

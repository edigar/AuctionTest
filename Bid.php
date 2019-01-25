<?php

//namespace Auction;

class Bid {
		private $usuario;
		private $valor;

		function __construct(User $usuario, $valor) {
        if($valor <= 0)
            throw new InvalidArgumentException("O valor de 1 lance deve ser maior que 0");

  			$this->usuario = $usuario;
  			$this->valor = $valor;
		}

		public function getUsuario() {
			   return $this->usuario;
		}

		public function getValor() {
			   return $this->valor;
		}
}

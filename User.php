<?php

//namespace Auction;

class User {
		private $id;
		private $nome;

		function __construct($nome,$id = null) {
			$this->nome = $nome;
			$this->id = $id;
		}

		public function getId() {
			return $this->id;
		}

		public function getNome() {
			return $this->nome;
		}
}

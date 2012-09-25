<?php

	class PruebaController  extends AutorizationController {

		public function __construct() {
			if(!isset($_SESSION)) {
				session_start();
			}
			parent::__construct();
		}

		function index() {

			$user = $this->verifyUser();

			$this->layout = "prueba";
			$this->partial("asd");

		}	



	}


?>

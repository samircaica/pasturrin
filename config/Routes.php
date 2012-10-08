<?php



	class Routes {

		public $default;
		private $controller;
		private $action;
		private $id;

		public function __construct() {
			if (file_exists(PASTURRIN_BASE_DIR. DS . 'config'. DS . 'config.php')) {
				include_once(PASTURRIN_BASE_DIR. DS . 'config'. DS . 'config.php');
				if (file_exists(PASTURRIN_BASE_DIR. DS . 'config'. DS . 'environments'. DS . ENVIRONMENT.'.php')) {
					include(PASTURRIN_BASE_DIR. DS . 'config'. DS . 'environments'. DS . ENVIRONMENT.'.php');
					
					$this->controller 	= $default[0];
					$this->action 		= $default[1];
					$this->id 			= $default[2];
				}
			}
			
		}

		public function getController() {
			return $this->controller;
		}

		public function getAction() {
			return $this->action;
		}

		public function getId() {
			return $this->id;
		}

	}

?>
<?php



	class Dispatch {
		private $request;
		private $response;
		private $params;
		private $controller;
		private $action;

		public function __construct() {
				
			if (isset($_GET['controller']) && !empty($_GET['controller']) ){
				$this->params['controller'] = $_GET['controller'];
				if (isset($_GET['action']) && !empty($_GET['action']) ) {
					$this->params['action'] = $_GET['action'];
					if (isset($_GET['id'])){
						$this->params['id'] = $_GET['id'];
					}
				} else {
					$this->params['action'] = 'index';
				}
				$this->process();				
			} else {
				//echo "inicio";
				header('location: index.html' );
        		die;
			}
			
			
				
		}

		function process() {
			//echo "Dispatch::process";
			$class_name = $this->params['controller'] . "Controller";
			try {
				$this->controller = new $class_name;
			
			
				$this->controller->params = $this->params;

				$this->controller->before_filter();
				$this->render();
				$this->controller->after_filter();
				//$this->debug();
			} catch (Exception $e) {
			    echo 'Caught exceptionnnn: ',  $e->getMessage(), "\n";

			}

		}

		private function render() {
			if(in_array($this->params['action'], get_class_methods(get_class($this->controller)))) {
				call_user_func(array($this->controller, $this->params['action']));
			}
			elseif(file_exists(PASTURRIN_APP_DIR . DS . 'views' . DS . $this->params['controller'] . DS . $this->params['action'] . '.phtml')) {
				$this->controller->render(PASTURRIN_APP_DIR . DS . 'views' . DS . $this->params['controller'] . DS . $this->params['action'] . '.phtml');
			} else {
				call_user_func(array($this->controller, 'action_missing'));
			}

			if($this->controller->auto_render) {
				$this->controller->render();
			}
		}

		private function debug() {
			if(DEBUG) {
				$this->controller->debug($this->controller);
			}
		}

	}

	

?>
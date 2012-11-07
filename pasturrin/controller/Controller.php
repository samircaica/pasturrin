<?php

class Controller {
	public $params;
	public $helper;
	public $page_title;
	public $logger;
	public $request;
	public $response;
	public $use_layout	=	"";
	public $template;
	public $partial;
	public $auto_render =	true;

	public $flash;

	private $template_root;

	function  content() {
		if(file_exists($this->template)) {
			$this->render_template();
		} else {
			$this->template_missing();
		}
	}

	function render($options = array()) {
		$this->use_layout = PASTURRIN_APP_DIR . DS . 'views/layouts' . DS . $this->layout . '.phtml';
		if(empty($this->template)) {
			$this->template = PASTURRIN_APP_DIR . DS . 'views' . DS . $this->params['controller'] . DS . $this->params['action'] . '.phtml';
		} else {
			$this->template = PASTURRIN_APP_DIR . DS . 'views' . DS . $this->params['controller'] . DS . $this->template . '.phtml';
		}
		
		
		ob_start();
		if(file_exists($this->use_layout)) {
			include($this->use_layout);
		} else {
			$this->render_template();
		}
		ob_end_flush();
	}

	function render_template() {
		if(file_exists($this->template)) {
			include($this->template);
		} else {
			$this->template_missing();
		}
	}

	function render_partial() {
		if(file_exists($this->partial)) {
			include($this->partial);
		} else {
			$this->partial_missing();
		}
	}

	function partial($text=null) {
			$this->partial = PASTURRIN_APP_DIR . DS . 'views' . DS . $this->params['controller'] . DS ."_". $text . '.phtml';
			$this->render_partial();
	}

	function redirect_to($text=null) {
		
		$this->auto_render = false;
		$this->template = PASTURRIN_APP_DIR . DS . 'views' . DS . $this->params['controller'] . DS . $text . '.phtml';
		$this->render_template();
	}

	public function redirect_too($controller = '', $action = '', $id = '') {
	        $this->redirect_to_url($this->url_for($controller, $action, $id));
	}


	public function redirect_to_url($url) {
	        header('location: ' . $url);
        	die;
	}

	

	public function action_missing() {
		include(PASTURRIN_LIB_DIR . DS . 'views' . DS . 'action_missing.phtml');
	}

	public function controller_missing() {
		include(PASTURRIN_LIB_DIR . DS . 'views' . DS . 'controller_missing.phtml');
	}

	public function template_missing() {
		include(PASTURRIN_LIB_DIR . DS . 'views' . DS . 'template_missing.phtml');
	}

	public function partial_missing() {
		include(PASTURRIN_LIB_DIR . DS . 'views' . DS . 'partial_missing.phtml');
	}

}
?>

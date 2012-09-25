<?php

	class Helpers {
	
		function __autoload() {
			if(file_exists(PASTURRIN_LIB_DIR . DS . 'helper' . DS . strtolower($class_name) . '.php')) {
				// include PASTURRIN/view/lib
				include_once(PASTURRIN_LIB_DIR . DS . 'helper' . DS . strtolower($class_name) . '.php');
			}
		}
	}

?>

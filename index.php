<?php

require_once 'php-activerecord/ActiveRecord.php';


ActiveRecord\Config::initialize(function($cfg) {
	$cfg->set_model_directory('app/models');
	$cfg->set_connections(array(
	    'development' => 'database_connection'));
});


require_once('twitteroauth/twitteroauth.php');

include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'environment.php');
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dispatch.php');  

define('DS', DIRECTORY_SEPARATOR);
define('PASTURRIN_BASE_DIR', dirname(__FILE__));
define('PASTURRIN_LIB_DIR', PASTURRIN_BASE_DIR . DS . 'pasturrin');
define('PASTURRIN_APP_DIR', PASTURRIN_BASE_DIR . DS . 'app');
define('APP_ROOT', PASTURRIN_BASE_DIR . DS . 'public');
define('APP_LIB_DIR', PASTURRIN_BASE_DIR . DS . 'lib');
define('PASTURRIN_VENDOR_DIR', PASTURRIN_BASE_DIR . DS . 'vendor');
define('BASE_URL', 'http://www.project.com');


set_include_path(get_include_path() . PATH_SEPARATOR . PASTURRIN_LIB_DIR . PATH_SEPARATOR . PASTURRIN_VENDOR_DIR);
set_include_path(get_include_path() . PATH_SEPARATOR . PASTURRIN_APP_DIR . PATH_SEPARATOR . 'models');

function application_autoload($class_name) {
	if(preg_match('/\w+Controller$/', $class_name)) {
		// include app/controllers
		if (file_exists(PASTURRIN_APP_DIR . DS . 'controllers' . DS . ucwords(str_replace('Controller', '', $class_name)) . '.php')) {
			include_once(PASTURRIN_APP_DIR . DS . 'controllers' . DS . ucwords(str_replace('Controller', '', $class_name)) . '.php');
		} else {
			throw new Exception('Controller '. ucwords(str_replace('Controller', '', $class_name)) . ' doesn\'t exist.');
		}
	}
	elseif(preg_match('/\w+Helper$/', $class_name)) {
		// include app/helers
		include_once(PASTURRIN_APP_DIR . DS . 'helpers' . DS . str_replace('Helper', '', $class_name) . '_helper.php');
	}
	elseif(file_exists(PASTURRIN_APP_DIR . DS . 'models' . DS . strtolower($class_name) . '.php')) {
		// include app/models
		include_once(PASTURRIN_APP_DIR . DS . 'models' . DS . strtolower($class_name) . '.php');
		if($class_name != 'activerecords') {
			//$tmp = new $class_name;
			//$tmp->_init();
			// because of STATIC PROBLEMS in php versions below 5.3.0
			//call_user_func(array($class_name, '_init'), $class_name);
		}
	}
	elseif(file_exists(PASTURRIN_LIB_DIR . DS . 'controller' . DS . ucwords(strtolower($class_name)) . '.php')) {
		// include PASTURRIN/controller/lib
		include_once(PASTURRIN_LIB_DIR . DS . 'controller' . DS . ucwords(strtolower($class_name)) . '.php');
	}
	elseif(file_exists(PASTURRIN_LIB_DIR . DS . 'model' . DS . ucwords(strtolower($class_name)) . '.php')) {
		// include PASTURRIN/controller/lib
		include_once(PASTURRIN_LIB_DIR . DS . 'model' . DS . ucwords(strtolower($class_name)) . '.php');
	}
	elseif(file_exists(PASTURRIN_LIB_DIR . DS . 'helper' . DS . 'helpers' . DS . strtolower($class_name) . '.php')) {
		// include PASTURRIN/database/adapters/lib
		include_once(PASTURRIN_LIB_DIR . DS . 'helper' . DS . 'helpers' . DS . strtolower($class_name) . '.php');
	}
	elseif(file_exists(PASTURRIN_LIB_DIR . DS . 'view' . DS . strtolower($class_name) . '.php')) {
		// include PASTURRIN/view/lib
		include_once(PASTURRIN_LIB_DIR . DS . 'view' . DS . strtolower($class_name) . '.php');
	}
	elseif(file_exists(PASTURRIN_LIB_DIR . DS . 'helper' . DS . strtolower($class_name) . '.php')) {
		// include PASTURRIN/view/lib
		include_once(PASTURRIN_LIB_DIR . DS . 'helper' . DS . strtolower($class_name) . '.php');
	}
	elseif(file_exists(PASTURRIN_LIB_DIR . DS . strtolower($class_name) . '.php')) {
		// include PASTURRIN/lib
		include_once(PASTURRIN_LIB_DIR . DS . strtolower($class_name) . '.php');
	}
	elseif(file_exists(APP_LIB_DIR . DS . strtolower($class_name) . '.php')) {
		// include app libs add by user.
		include_once(APP_LIB_DIR . DS . strtolower($class_name) . '.php');
	}

}

spl_autoload_register('application_autoload');

$dispatch = new Dispatch;

?>


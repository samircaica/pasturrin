<?php

	

	define('DEBUG', 1); // 0. production, 1. development.

	define('LOG_COLORING', 1); // 0. log disable coloring, 1. log enable coloring.

	define('LOG_LOCATION', 'page'); // append logs to: file, page.

	define('ENVIRONMENT', 'development'); // current environment: development, test and production

	define('DB', 'mysql');

	//to use with twitter oauth
	define('CONSUMER_KEY', '');
	define('CONSUMER_SECRET', '');
	define('OAUTH_CALLBACK', '');

	if (file_exists(PASTURRIN_BASE_DIR. DS . 'config'. DS . 'environments'. DS . ENVIRONMENT.'.php')) {
		include(PASTURRIN_BASE_DIR. DS . 'config'. DS . 'environments'. DS . ENVIRONMENT.'.php');
				
		$connections = array(
			"development" => "mysql://".$database[DB]['user'].":@".$database[DB]['host']."/".$database[DB]['schema']
		    
		);
	}
	
?>

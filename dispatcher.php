<?php

	class Dispatcher {

		var $className;

		function __autoload( $className ) {
			//echo "registrando la huea";
			require_once('controllers/' . $className .  '.php' );
		}

	}


?>

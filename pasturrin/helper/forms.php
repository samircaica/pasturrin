<?php

	class Forms {

		function form_for($arr) {
			echo "controller ".$arr['controller'];
			echo '<form method="'.$arr['method'].'" action="'.$arr['controller'].'/'.$arr['action'].'">';
		}

		function form_end() {
			echo "</form>";
		}
	}

?>

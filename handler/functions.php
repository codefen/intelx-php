<?php
	global $value;

	Function deliver($cadena) {
		$cadena = Json_encode($cadena, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		echo $cadena;
		die;
	}
?>
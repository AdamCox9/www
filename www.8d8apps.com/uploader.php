<?php

	require 'local.php';

	if( isset( $_POST['hidden'] ) && $_POST['hidden'] == 'hidden' ) {

		$DB = new DatabaseConnection();
		$conn = $DB->getConn();

		SaveMP3();
		SaveMP3Data();

		echo "OK";
	} else {
		echo "ERROR";
	}

?>
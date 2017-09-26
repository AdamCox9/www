<?php

	require 'local.php';

	if( isset( $_POST['hidden'] ) && $_POST['hidden'] == 'hidden' ) {
		$conn = open_db_conn();
		SaveMP3();
		SaveMP3Data();
		close_db_conn();
		echo "OK";
	} else {
		echo "ERROR";
	}

?>
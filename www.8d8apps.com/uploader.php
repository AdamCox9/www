<?php

	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );

	require 'library.php';

	//print_r( $_FILES );
	//print_r( $_POST );

	if( isset( $_POST['hidden'] ) && $_POST['hidden'] == 'hidden' ) {
		set_db_vars();
		$conn = open_db_conn();
		SaveMP3();
		SaveMP3Data();
		close_db_conn();
		echo "OK";
	} else {
		echo "ERROR";
	}

?>
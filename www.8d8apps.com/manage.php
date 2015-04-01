<?php

	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );

	if( $_SERVER['REMOTE_ADDR'] != "10.1.10.13" ) {
		die( $_SERVER['REMOTE_ADDR'] );
	}

	die( 'test' );

	require 'library.php';

	//print_r( $_FILES );
	//print_r( $_POST );

	if( isset( $_GET['action'] ) ) {
		set_db_vars();
		$conn = open_db_conn();

		switch( $_GET['action'] ) {
			case 'getall':
				echo json_encode( GetAllMP3Data() );
			break;
			case 'update':
				echo UpdateMP3Entry();
			break;
			default:
				echo "ACTION ERROR";
			break;
		}

		close_db_conn();
	} else {
		echo "ISSET ERROR";
	}

?>
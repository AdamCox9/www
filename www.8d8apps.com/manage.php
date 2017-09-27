<?php

	die( 'test' );

	require_once 'local.php';

	$DB = new DatabaseConnection();
	$conn = $DB->getConn();

	//TODO put in a config file:
	if( $_SERVER['REMOTE_ADDR'] != "70.90.241.53" ) {
		die( $_SERVER['REMOTE_ADDR'] );
	}

	//print_r( $_FILES );
	//print_r( $_POST );

	if( isset( $_GET['action'] ) ) {

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

	} else {
		echo "ISSET ERROR";
	}

?>
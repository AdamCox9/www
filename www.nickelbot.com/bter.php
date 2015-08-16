<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	/*if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 50058 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}*/
		
	require_once( "../php/bter/bter_lib.php" );

	$api_key = 'EAD04357-77CE-4A75-9B7D-CFAD0B481D09';
	$api_secret = '1dd9b32a6a0a92ab70bc2944ad567db9b133593eee8c597d80e9d4392235f11b';

	$Bter = new bter( $api_key, $api_secret );

	//$Bter->list_all_orders();

	$Bter->cancel_all_orders();

	//$Bter->make_buy_orders();
	
	//$Bter->make_sell_orders();

?> 
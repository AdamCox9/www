<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 50058 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}
	
	require_once( "../php/bitfinex/bitfinex_lib.php" );

	$api_key = "A4K0wCj6KLdChWkp2Xxd4xPLDWj9nYD7dCvdZ5Wj7jr";
	$api_secret = "xYKT35o3rJloES7GWkO2lWKYH9c5kwxCVV0W3XqqaP7";

	$Bitfinex = new bitfinex( $api_key, $api_secret );

	$result = $Bitfinex->query("/order/cancel", array("order_id" => 390713935));

	print_r( $result );

?> 
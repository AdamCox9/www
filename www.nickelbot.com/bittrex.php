<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 50058 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}
		
	require_once( "../php/bittrex/bittrex_lib.php" );

	$api_key = "5d46d0942fea4f059d95c3bce1377f57";
	$api_secret = "15aa417db72249d5831b402cab1aa289";

	$Bittrex = new bittrex( $api_key, $api_secret );


	//$result = $Bittrex->query("/public/getmarkets");
	//$result = $Bittrex->query("/market/getopenorders");
	//$result = $Bittrex->query("/public/getmarkets");
	
	//$result = $Cryptsy->query("marketorders", array("marketid" => 26));

	$result = $Bittrex->query("/market/getopenorders");

	foreach( $result['result'] as $order ) {
		$Bittrex->query("/market/cancel", array("uuid" => $order['OrderUuid'] ) );
	}

?> 
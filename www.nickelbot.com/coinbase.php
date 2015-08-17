<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 50058 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}
		
	require_once( "../php/coinbase/coinbase_lib.php" );

	$api_key = "";
	$api_secret = "";

	$Coinbase = new coinbase( $api_key, $api_secret );


	$result = $Coinbase->query("/order/cancel", array("order_id" => "390714463"));
	
	print_r( $result );

	//$result = $Coinbase->query("/market/getopenorders");
	//$result = $Coinbase->query("/public/getmarkets");
	
	//$result = $Coinbase->query("marketorders", array("marketid" => 26));

	/*$result = $Coinbase->query("/market/getopenorders");

	foreach( $result['result'] as $order ) {
		$Coinbase->query("/market/cancel", array("uuid" => $order['OrderUuid'] ) );
	}*/

?> 
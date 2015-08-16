<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 50058 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}
		
	require_once( "../php/cryptsy/cryptsy_lib.php" );

	$api_key = "48d4648b4a93b38a292c41dcde74b6bdec185c83";
	$api_secret = "4c8d67097d0243dcabe368a1db09cf1665966fe9abf2c2f4b1a21c52393582522d58ea6ffbbdfeb4";

	$Cryptsy = new cryptsy( $api_key, $api_secret );

	//$result = $Cryptsy->query("getinfo");
	//$result = $Cryptsy->query("getmarkets");
	//$result = $Cryptsy->query("mytransactions");
	//$result = $Cryptsy->query("markettrades", array("marketid" => 26));
	//$result = $Cryptsy->query("marketorders", array("marketid" => 26));
	//$result = $Cryptsy->query("mytrades", array("marketid" => 26, "limit" => 1000));
	//$result = $Cryptsy->query("allmytrades");
	//$result = $Cryptsy->query("myorders", array("marketid" => 26));
	//$result = $Cryptsy->query("allmyorders");
	$result = $Cryptsy->query("cancelallorders");
	//$result = $Cryptsy->query("createorder", array("marketid" => 26, "ordertype" => "Sell", "quantity" => 1000, "price" => 0.00031000));
	//$result = $Cryptsy->query("cancelorder", array("orderid" => 139567));
	//$result = $Cryptsy->query("calculatefees", array("ordertype" => 'Buy', 'quantity' => 1000, 'price' => '0.005'));

	echo "<pre>".print_r($result, true)."</pre>";

?> 
<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	/*if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 50058 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}*/

	require_once( "/var/www/php/btc-e/btc-e_lib.php" );

	$api_key = '2ZCV2VA7-GQA3QKHA-VJ1IGJ1N-7K86RGKW-6IHMCY2P'; // your API-key
	$api_secret = 'cfb855a2ccbdf1e9baf8c7fcd19327e53c9b080db3aec1fa98a3a6a62cccfb87'; // your Secret-key


	$Btce = new btce($api_key, $api_secret);


	/*//_____Show basic info
	$result = btce_query('getInfo');
	var_dump($result);*/

	/*//_____Make a trade
	$result = btce_query('Trade', array('pair' => 'btc_usd', 'type' => 'buy', 'amount' => 1, 'rate' => 10)); //buy 1 BTC @ 10 USD
	var_dump($result);*/

	/*//_____Show log of transactions:
	$result = btce_query('TransHistory');
	foreach( $result['return'] as $key => $pair ) {
		echo $key . " => " . $pair['desc'] . "\n<br/>";
	}*/


	//_____Show trade history:
	$result = $Btce->query('TradeHistory');
	foreach( $result['return'] as $key => $pair ) {
		print_r( $pair );
		//echo $key . " => " . $pair['desc'] . "\n<br/>";
	}



?>
<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 53673 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}

	require_once( "config.php" );

	$exchanges = [];
	$currencies = [];
	$markets = [];
	$market_summaries = [];
	foreach( $Adapters as $Adapter ) {
		echo "\n*******" . get_class( $Adapter ) . "******\n";
		array_push( $exchanges, get_class( $Adapter ) );
		try{
			$currencies = array_merge( $currencies, $Adapter->get_currencies() );
			$markets = array_merge( $markets, $Adapter->get_markets() );
			$market_summaries = array_merge( $market_summaries, $Adapter->get_market_summaries() );
			$keys = array_keys( $market_summaries[sizeof($market_summaries)-1] );
			if( ! isset( $prev_keys ) )
				$prev_keys = $keys;

			print_r( array_diff( $keys, $prev_keys ) );
			print_r( array_diff( $prev_keys, $keys ) );

			if( sizeof( array_diff( $keys, $prev_keys ) ) != sizeof( array() ) || sizeof( array_diff( $prev_keys, $keys ) ) != sizeof( array() ) ) {
				sort( $keys );
				print_r( $keys );
				die( "\n\nArrays have different keys!\n\n" );
			}
			sort( $keys );
			print_r( $keys );
			$prev_keys = $keys;


			//foreach( $markets as $market ) {
				//$market_summary = $Adapter->get_market_summary( $market );
			//}

			//print_r( $Adapter->get_open_orders() );
			//print_r( $Adapter->cancel_all() );
			//print_r( $Adapter->buy('btcusd','1','5','limit') );
			//print_r( $Adapter->sell('btcusd','1','500','limit') );
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}

	$currencies = array_unique( $currencies );
	$markets = array_unique( $markets );

	sort( $currencies );
	sort( $markets );

	print_r( $exchanges );
	echo "\n\nCurrencies\n";
	foreach( $currencies as $currency) {
		echo $currency . ", ";
	}
	echo "\n\n***Markets***\n";
	foreach( $markets as $market) {
		echo $market . ", ";
	}
	//print_r( array_keys( $market_summaries ) );

?> 
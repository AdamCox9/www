<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 50058 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}

	require_once( "config.php" );

	/*****
		Bitfinex
	 *****/

	try{
		//$result = $BitfinexAdapter->buy('ltcbtc','1','0.01','limit');
		//var_dump( $result );
		//$result = $BitfinexAdapter->sell('ltcbtc','1','1','limit');
		//var_dump( $result );
	}catch(Exception $e){
		echo $e->getMessage()."\n";
	}


	/*****
		Bitstamp
	 *****/
	
	try{
		//$result = $BitstampAdapter->buy('btcusd','1','0.01','limit');
		//var_dump( $result );
		//$result = $BitstampAdapter->sell('btcusd','1','500','limit');
		//var_dump( $result );
	}catch(Exception $e){
		echo $e->getMessage()."\n";
	}

	/*****
		Bittrex
	 *****/

	try{
		//$result = $BittrexAdapter->cancel_all();
		//var_dump( $result );
	}catch(Exception $e){
		echo $e->getMessage()."\n";
	}

	/*****
		BTC-e
	 *****/

	try{
		//$result = $BtceAdapter->cancel_all();
		//var_dump($result);
	}catch(Exception $e){
		echo $e->getMessage()."\n";
	}

	/*****
		Bter
	 *****/

	try{
		$result = $BterAdapter->get_open_orders();
		var_dump($result);
	}catch(Exception $e){
		echo $e->getMessage()."\n";
	}

	/*****
		Coinbase
	 *****/

	try{
		//_____Show basic info
		//$result = $Coinbase->query('/accounts');
		//var_dump($result);
		//$result = $Coinbase->query('/currencies');
		//var_dump($result);
	}catch(Exception $e){
		echo $e->getMessage()."\n";
	}


	/*****
		Cryptsy
	 *****/

	try{
		/*
		$cryptsy_getinfo = $Cryptsy->query("getinfo");
		//echo "<pre>".print_r($cryptsy_getinfo, true)."</pre>";
		$cryptsy_get_total_btc_balance = 0;
		foreach( $cryptsy_getinfo['return']['balances_available_btc'] as $balance ) {
			$cryptsy_get_total_btc_balance += $balance;
		}
		echo "\nCryptsy Bitcoin Worth " . $cryptsy_get_total_btc_balance . "\n";
		echo "Cryptsy Available Bitcoin Balance " . $cryptsy_getinfo['return']['balances_available_btc']['BTC'] . "\n";
		*/
	}catch(Exception $e){
		echo $e->getMessage()."\n";
	}

	/*****
		Poloniex
	 *****/

	try{

		//$poloniex_ticker = $Poloniex->get_ticker();
		//$poloniex_balances = $Poloniex->get_balances();
		//$poloniex_total_btc_balance = $Poloniex->get_total_btc_balance();

		//print_r( $poloniex_ticker );
		//print_r( $poloniex_balances );
		//print_r( $poloniex_total_btc_balance );

		//echo "\nPoloniex Total Bitcoin Balance " . $poloniex_total_btc_balance . "\n";
		//echo "Poloniex Available Bitcoin Balance " . $poloniex_balances['BTC'] . "\n";

		//$Poloniex->list_detailed_info();
		//$Poloniex->cancel_all_orders();
		//$Poloniex->make_buy_orders();
		//$Poloniex->make_sell_orders();

	}catch(Exception $e){
		echo $e->getMessage()."\n";
	}

?> 
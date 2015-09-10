<?php

	/*****

		Development sandbox

		Definitions:

		Currency -
		Quote Currency -
		Base Currency -
		Volume -
		Quote Volume -
		Base Volume -
		Market -
		EMA -
		Trade -
		Order -
		Worth -

	 *****/

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || strstr( $_SERVER['SSH_CONNECTION'], '76.24.176.23' ) === FALSE ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}

	require_once( "config.php" );

	$exchanges = [];
	$currencies = [];
	$markets = [];
	$market_summaries = [];
	$balances = [];
	$open_orders = [];
	$worths = [];
	$completed_orders = [];
	$deposit_addresses = [];
	$trades = [];
	$orderbook = [];

	try{
		foreach( $Adapters as $Adapter ) {
			echo "\n\n*******" . get_class( $Adapter ) . "******\n\n";

			echo " -> exchange name\n";
			$exchanges = array_merge( $exchanges, array( get_class( $Adapter ) ) );
			test_exchanges( $exchanges );
			
			echo " -> getting currencies\n";
			$currencies = array_unique( array_merge( $currencies, $Adapter->get_currencies() ) );
			test_currencies( $currencies );
			
			echo " -> getting markets\n";
			$markets = array_unique( array_merge( $markets, $Adapter->get_markets() ) );
			test_markets( $markets );

			echo " -> getting market summaries\n";
			$market_summaries = array_merge( $market_summaries, $Adapter->get_market_summaries() );
			test_market_summaries( $market_summaries );

			echo " -> getting balances\n";
			$balances = array_merge( $balances, $Adapter->get_balances() );
			test_balances( $balances );

			echo " -> getting open orders\n";
			$open_orders = array_merge( $open_orders, $Adapter->get_open_orders() );
			test_open_orders( $open_orders );

			echo " -> getting completed orders\n";
			$completed_orders = array_merge( $completed_orders, $Adapter->get_completed_orders() );
			test_completed_orders( $completed_orders );

			//Should be the same
			$all_orders = array_merge( $completed_orders, $open_orders );
			test_all_orders( $all_orders );
			
			echo " -> getting worths\n";
			$worths[ get_class( $Adapter ) ]= $Adapter->get_worth();
			test_worths( $worths );

			echo " -> getting volumes\n";
			$volumes[ get_class( $Adapter ) ] = Utilities::get_total_volumes( $Adapter->get_market_summaries() );
			test_volumes( $volumes );

			echo " -> generating deposit addresses\n";
			$deposit_addresses = array_merge( $deposit_addresses, $Adapter->deposit_addresses() );
			test_deposit_addresses( $deposit_addresses );
			
			echo " -> getting all recent trades\n";
			$trades = array_merge( $trades, $Adapter->get_trades( $market = "BTC-USD", $time = 0 ) );
			test_trades( $trades );

			foreach( $trades as $trade ) {
				print_r( $trade );
			}

			echo " -> getting some depth of orderbook\n";
			$orderbook = array_merge( $orderbook, $Adapter->get_orderbook( $market = "BTC-USD", $depth = 20 ) );
			test_orderbook( $orderbook );

			foreach( $orderbook as $order ) {
				print_r( $order );
			}

			//echo " -> cancelling all orders\n"
			//print_r( $Adapter->cancel_all() );

			//make_max_orders( array( $Adapter ) );

		}

		sort( $currencies );
		sort( $markets );

		//print_r( $balances );

		echo "\n\nCurrencies(".sizeof($currencies).")\n";
		foreach( $currencies as $currency) {
			echo $currency . ", ";
		}

		echo "\n\n***Markets(".sizeof($markets).")***\n";
		foreach( $markets as $market) {
			echo $market . ", ";
		}

		echo "\n\n***Worths***\n";
		$total_worth = 0;
		foreach( $worths as $key => $worth) {
			echo "$key BTC Balance: " . $worth['btc_worth'] . "\n";
			$total_worth += $worth['btc_worth'];
		}
		echo "\n\n#####Total Worth: $total_worth#####\n\n";

		echo "\n\n***Volumes***\n";
		$total_volume = 0;
		foreach( $volumes as $key => $volume) {
			echo "$key BTC Volume: " . $volume['total_volume'] . "\n";
			$total_volume += $volume['total_volume'];
		}
		echo "\n\n#####Total Volume: $total_volume#####\n\n";

	}catch(Exception $e){
		echo $e->getMessage()."\n";
	}

?> 
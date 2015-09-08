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

	try{
		foreach( $Adapters as $Adapter ) {
			echo "\n\n*******" . get_class( $Adapter ) . "******\n\n";

			/*** Get Data from $Adapter ***/
			//Make sure each piece of data contains key 'exchange' in it to refer back to adapter if necessary
			$exchanges = array_merge( $exchanges, array( get_class( $Adapter ) ) );
			$currencies = array_unique( array_merge( $currencies, $Adapter->get_currencies() ) );
			$markets = array_unique( array_merge( $markets, $Adapter->get_markets() ) );
			$market_summaries = array_merge( $market_summaries, $Adapter->get_market_summaries() );
			$balances = array_merge( $balances, $Adapter->get_balances() );
			$open_orders = array_merge( $open_orders, $Adapter->get_open_orders() );
			$worths[ get_class( $Adapter ) ]= $Adapter->get_worth();
			$volumes[ get_class( $Adapter ) ] = Utilities::get_total_volumes( $Adapter->get_market_summaries() );
			$completed_orders = array_merge( $completed_orders, $Adapter->get_completed_orders() );
			//print_r( $Adapter->cancel_all() );
			$deposit_addresses = array_merge( $deposit_addresses, $Adapter->deposit_addresses() );
			if( isset( $deposit_addresses['error'] ) && $deposit_addresses['error'] == "NOT_IMPLEMENTED" )
				echo "\nThis exchange does not generate addresses\n";


		}

		//TODO test all the arrays above to make sure each are consistent with the next like the following:
		test_market_summaries( $market_summaries );
		test_balances( $balances );
		$all_orders = array_merge( $completed_orders, $open_orders );
		test_orders( $all_orders );

		make_max_orders( $Adapters );

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
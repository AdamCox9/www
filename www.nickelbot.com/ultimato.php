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
		try{
			echo "\n*******" . get_class( $Adapter ) . "******\n";
			array_push( $exchanges, get_class( $Adapter ) );
			$currencies = array_merge( $currencies, $Adapter->get_currencies() );
			$markets = array_merge( $markets, $Adapter->get_markets() );
			$new_market_summaries = $Adapter->get_market_summaries();
			$market_summaries = array_merge( $market_summaries, $new_market_summaries );
			//print_r( $Adapter->cancel_all() );

			foreach( $new_market_summaries as $market_summary ) {
				$keys = array_keys( $market_summary );
				if( ! isset( $prev_keys ) )
					$prev_keys = $keys;

				if( $market_summary['frozen'] )
					continue;

				if( sizeof( array_diff( $keys, $prev_keys ) ) != sizeof( array() ) || sizeof( array_diff( $prev_keys, $keys ) ) != sizeof( array() ) ) {
					print_r( $market_summary );
					print_r( array_diff( $keys, $prev_keys ) );
					print_r( array_diff( $prev_keys, $keys ) );
					die( "\n\nArrays have different keys!\n\n" );
				}
				$prev_keys = $keys;

				test_market_summary( $market_summary );

				print_r( $market_summary );
				
				$price_precision = $market_summary['price_precision'];

				if( $market_summary['bid'] < $market_summary['low'] )
					if( $market_summary['low'] == 0 )
						$buy_price = bcmul( $market_summary['bid'], 0.95, $price_precision );
					else
						$buy_price = $market_summary['bid'];
				else
					$buy_price = $market_summary['low'];

				if( $market_summary['ask'] > $market_summary['high'] )
					if( $market_summary['high'] == 0 )
						$sell_price = bcmul( $market_summary['ask'], 1.1, $price_precision );
					else
						$sell_price = $market_summary['ask'];
				else
					$sell_price = $market_summary['high'];

				/*
					TODO make sure the spread leaves room for at least 5% or so...
					[low] => 2.60037475
					[high] => 2.60037475

				*/

				$minimum_order_size_base = number_format( $market_summary['minimum_order_size_base'], $price_precision, '.', '' );
				$buy_price = number_format( $buy_price, 8, '.', '' );
				$sell_price = number_format( $sell_price, 8, '.', '' );

				if( $buy_price > 0 ) {
					echo "buy_price $buy_price\n";
					$buy_order_size = number_format( bcdiv( $minimum_order_size_base, $buy_price, 32 ), $price_precision, '.', '');
					$total_buy_cost = number_format( bcmul( $buy_order_size, $buy_price, 32 ), $price_precision, '.', '');
					echo "***Buying $buy_order_size of {$market_summary['pair']} at {$market_summary['exchange']} for $buy_price costing $total_buy_cost quote***\n";
					print_r( $Adapter->buy( $market_summary['pair'], $buy_order_size, $buy_price, 'limit', array( 'market_id' => $market_summary['market_id'] ) ) );
				}

				if( $sell_price > 0 ) {
					echo "sell_price $sell_price\n";
					$sell_order_size = number_format( bcdiv( $minimum_order_size_base, $sell_price, 32 ), $price_precision, '.', '');
					$total_sell_gain = number_format( bcmul( $sell_order_size, $sell_price, 32 ), $price_precision, '.', '');
					echo "***Selling $sell_order_size of {$market_summary['pair']} at {$market_summary['exchange']} for $sell_price gaining $total_sell_gain quote***\n";
					print_r( $Adapter->sell( $market_summary['pair'], $sell_order_size, $sell_price, 'limit', array( 'market_id' => $market_summary['market_id'] ) ) );
				}

			}
			unset( $prev_keys );

			//TODO test each market_summary in market_summaries vs market_summary directly
			//foreach( $markets as $market ) {
				//$market_summary = $Adapter->get_market_summary( $market );
				//TODO make sure $market_summary == $market_summaries[$market]
			//}

			//print_r( $Adapter->get_open_orders() );
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}

	$currencies = array_unique( $currencies );
	$markets = array_unique( $markets );

	sort( $currencies );
	sort( $markets );

	echo "\n\nCurrencies(".sizeof($currencies).")\n";
	foreach( $currencies as $currency) {
		echo $currency . ", ";
	}

	echo "\n\n***Markets(".sizeof($markets).")***\n";
	foreach( $markets as $market) {
		echo $market . ", ";
	}

	function test_market_summaries( $market_summaries ) {
		foreach( $market_summaries as $market_summary ) {
			test_market_summary( $market_summary );
		}
	}

	function test_market_summary( $market_summary ) {
		//Data:
		$keys = array(	'ask', 'base_volume', 'bid', 'created', 'display_name', 'exchange', 
						'expiration', 'frozen', 'high', 'initial_margin', 'last_price', 
						'low', 'market_id', 'maximum_order_size', 'mid', 'minimum_margin', 
						'minimum_order_size_base', 'minimum_order_size_quote', 'open_buy_orders',
						'open_sell_orders', 'pair', 'percent_change', 'price_precision', 
						'quote_volume', 'result', 'timestamp', 'verified_only', 'vwap' );

		//Make sure all these and only these array keys:
		if( sizeof( $keys ) != sizeof( $market_summary ) ) {
			$broken_keys = array_keys( $market_summary );
			print_r( array_diff( $keys, $broken_keys ) );
			print_r( array_diff( $broken_keys, $keys ) );
			print_r( $market_summary );
			die( "\n\nMismatched Array Keys" );
		}

		$numbers = array( 'ask', 'base_volume', 'bid', 'high', 'last_price', 'low', 'minimum_order_size_base', 'minimum_order_size_quote', 'quote_volume' );
		$strings = array( 'display_name', 'exchange' );
		$above_zero = array( 'minimum_order_size_base' );
		$not_null = array_merge( $numbers, $strings );

		//Tests:
		foreach( $numbers as $number ) {
			if( ! isset( $market_summary[$number] ) || is_null( $market_summary[$number] ) || ! is_numeric( $market_summary[$number] ) /*|| ! $market_summary[$number] > 0*/ ) {
				print_r( $market_summary );
				die( "\n\nRequired Number ($number)\n\n" );
			}
		}
		foreach( $not_null as $field ) {
			if( is_null( $field ) ) {
				print_r( $market_summary );
				die( "\n\nRequired Not Null ($field)\n\n" );
			}
		}
		foreach( $above_zero as $field ) {
			if( is_nan( $market_summary[$field] ) || is_null( $market_summary[$field] ) || ! is_numeric( $market_summary[$field] ) || $market_summary[$field] <= 0 ) {
				print_r( $market_summary );
				die( "\n\nRequired Above Zero ($field)\n\n" );
			}
		}
		return true;
	}

?> 
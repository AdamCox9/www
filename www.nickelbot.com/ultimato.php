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
			print_r( $Adapter->cancel_all() );

			foreach( $new_market_summaries as $market_summary ) {
				$keys = array_keys( $market_summary );
				if( ! isset( $prev_keys ) )
					$prev_keys = $keys;

				print_r( $market_summary );

				if( sizeof( array_diff( $keys, $prev_keys ) ) != sizeof( array() ) || sizeof( array_diff( $prev_keys, $keys ) ) != sizeof( array() ) ) {
					sort( $keys );
					sort( $prev_keys );
					print_r( array_diff( $keys, $prev_keys ) );
					print_r( array_diff( $prev_keys, $keys ) );
					print_r( $keys );
					die( "\n\nArrays have different keys!\n\n" );
				}
				$prev_keys = $keys;
				if( ! is_numeric( $market_summary['high'] ) || ! is_numeric( $market_summary['low'] ) )
					die( "High & Low Not Numbers!!!\nLow " . $market_summary['low'] . "\n" . "\nHigh " . $market_summary['high'] . "\n" );
				if( is_null( $market_summary['minimum_order_size'] ) )
					die( "minimum_order_size is null!!!\n " );
				if( is_null( $market_summary['price_precision'] ) ) {
					die( "Price precision is null" );
				}

				if( $market_summary['bid'] < $market_summary['low'] )
					$buy_price = $market_summary['bid'];
				else
					$buy_price = $market_summary['low'];

				if( $market_summary['ask'] > $market_summary['high'] )
					$sell_price = $market_summary['ask'];
				else
					$sell_price = $market_summary['high'];

				$price_precision = $market_summary['price_precision'];
				$minimum_order_size = number_format( $market_summary['minimum_order_size'], $price_precision, '.', '' );
				$buy_price = number_format( $buy_price, $price_precision, '.', '' );
				$sell_price = number_format( $sell_price, $price_precision, '.', '' );

				if( $buy_price > 0 ) {
					echo "buy_price $buy_price\n";
					$buy_order_size = bcdiv( $minimum_order_size, $buy_price, $price_precision );
					$total_buy_cost = bcmul( $buy_order_size, $buy_price, $price_precision );
					echo "***Buying $buy_order_size of {$market_summary['pair']} at {$market_summary['exchange']} for $buy_price gaining $total_buy_cost base***\n";
					print_r( $Adapter->buy( $market_summary['pair'], $buy_order_size, $buy_price, 'limit' ) );
				}

				if( $sell_price > 0 ) {
					echo "sell_price $sell_price\n";
					$sell_order_size = bcdiv( $minimum_order_size, $sell_price, $price_precision );
					$total_sell_gain = bcmul( $sell_order_size, $sell_price, $price_precision );
					echo "***Selling $sell_order_size of {$market_summary['pair']} at {$market_summary['exchange']} for $sell_price costing $total_sell_gain base***\n";
					print_r( $Adapter->sell( $market_summary['pair'], $sell_order_size, $sell_price, 'limit' ) );
				}

			}
			unset( $prev_keys );

			//die( print_r( $market_summaries, 1 ) );

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
	sort( $market_summaries );

	print_r( $exchanges );
	echo "\n\nCurrencies(".sizeof($currencies).")\n";
	foreach( $currencies as $currency) {
		echo $currency . ", ";
	}
	echo "\n\n***Markets(".sizeof($markets).")***\n";
	foreach( $markets as $market) {
		echo $market . ", ";
	}
	//print_r( array_keys( $market_summaries ) );

?> 
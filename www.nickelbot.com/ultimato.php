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
	$balances = [];
	foreach( $Adapters as $Adapter ) {
		try{
			echo "\n*******" . get_class( $Adapter ) . "******\n";
			array_push( $exchanges, get_class( $Adapter ) );
			$currencies = array_merge( $currencies, $Adapter->get_currencies() );
			$markets = array_merge( $markets, $Adapter->get_markets() );
			$new_market_summaries = $Adapter->get_market_summaries();
			$market_summaries = array_merge( $market_summaries, $new_market_summaries );
			$balances = array_merge( $balances, $Adapter->get_balances() );
			//print_r( $Adapter->cancel_all() );

			print_r( $balances );

			test_balances( $balances );

			sleep(2);

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

				$curs_bq = explode( "-", $market_summary['pair'] );
				$base_cur = $curs_bq[0];
				$quote_cur = $curs_bq[1];

				if( $buy_price > 0 ) {
					$buy_order_size = number_format( bcdiv( $minimum_order_size_base, $buy_price, 32 ), $price_precision, '.', '');
					$total_buy_cost = number_format( bcmul( $buy_order_size, $buy_price, 32 ), $price_precision, '.', '');
					echo "***Buying $buy_order_size $base_cur in market {$market_summary['pair']} at {$market_summary['exchange']} for total cost $total_buy_cost of $quote_cur at rate $buy_price of $quote_cur per $base_cur***\n";
					print_r( $Adapter->buy( $market_summary['pair'], $buy_order_size, $buy_price, 'limit', array( 'market_id' => $market_summary['market_id'] ) ) );
				}

				if( $sell_price > 0 ) {
					$sell_order_size = number_format( bcdiv( $minimum_order_size_base, $sell_price, 32 ), $price_precision, '.', '');
					$total_sell_gain = number_format( bcmul( $sell_order_size, $sell_price, 32 ), $price_precision, '.', '');
					echo "***Selling $sell_order_size of $base_cur in market {$market_summary['pair']} at {$market_summary['exchange']} for total gain $total_sell_gain of $quote_cur at rate $sell_price of $quote_cur per $base_cur***\n";
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

?> 
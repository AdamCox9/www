<?PHP

	function test_adapter( $Adapter ) {
		//Make sure there are no extra functions, etc...
		//Use reflection?
	}

	function test_exchanges( $exchanges ) {
		//Make sure they are strings that have AdapterEtcForm or something like that...
		//not null, etc...
	}

	function test_currencies( $currencies ) {
		//Make sure these have the correct keys, etc...
		//Make sure all upper case and 3, 4 or 5 alpha-numeric characters, etc...
	}

	function test_markets( $markets) {
		//Make sure these have the correct keys, etc...
		//Make sure all upper case and 3, 4 or 5 alpha-numeric characters, etc...
		//Make sure two valid currencies are separated by a "-"
	}

	function test_market_summaries( $market_summaries ) {
		//TODO test each market_summary in market_summaries vs market_summary directly
		//foreach( $markets as $market ) {
			//$market_summary = $Adapter->get_market_summary( $market );
			//TODO make sure $market_summary == $market_summaries[$market]
		//}

		foreach( $market_summaries as $market_summary ) {
			test_market_summary( $market_summary );
		}
	}

	function test_market_summary( $market_summary ) {
		//Data:
		$keys = array(	'ask', 'base_volume', 'bid', 'btc_volume', 'created', 'display_name', 'exchange', 
						'expiration', 'frozen', 'high', 'initial_margin', 'last_price', 
						'low', 'market_id', 'maximum_order_size', 'mid', 'minimum_margin', 
						'minimum_order_size_base', 'minimum_order_size_quote', 'open_buy_orders',
						'open_sell_orders', 'pair', 'percent_change', 'price_precision', 
						'quote_volume', 'result', 'timestamp', 'verified_only', 'vwap' );

		$numbers = array( 'ask', 'base_volume', 'bid', 'high', 'last_price', 'low', 'quote_volume' );
		$strings = array( 'display_name', 'exchange' );
		$above_zero = array( );
		$not_null = array_merge( $numbers, $strings );

		//Tests:
		equal_keys( $keys, $market_summary );
		numbers( $numbers, $market_summary );
		not_null( $not_null, $market_summary );
		above_zero( $above_zero, $market_summary );

		if(  is_null( $market_summary['minimum_order_size_base'] ) && is_null( $market_summary['minimum_order_size_quote'] ) ) {
			print_r( $market_summary );
			die( "\n\nEither base or quote minimum order size is required!\n\n" );
		}

	}

	function test_balances( $balances ) {
		$keys = array( 'type', 'currency', 'available', 'total', 'reserved', 'pending', 'btc_value' );
		$numbers = array( 'available', 'total', 'reserved', 'pending' );
		foreach( $balances as $balance ) {
			equal_keys( $keys, $balance );
		}
		numbers( $numbers, $balance );
	}

	function test_open_orders( $active_orders ) {
		//Make sure these have the correct keys, etc...
	}

	function test_completed_orders( $completed_orders ) {
		//Make sure these have the correct keys, etc...
	}

	function test_all_orders( $completed_orders ) {
		//Make sure these have the correct keys, etc...
		//More generic than test_open_orders and test_sell_orders, but the same
	}

	function test_worths( $worths ) {
		//Make sure all worths for each currency are all good under the hood!!!
	}

	function test_buy_order( $buy_order ) {
		//Make sure the order was placed and returns correct keys, etc...
	}

	function test_sell_order( $sell_order ) {
		//Make sure the order was placed and returns correct keys, etc...
	}
	
	function test_volumes( $volumes ) {
		//Make sure the order was placed and returns correct keys, etc...
	}

	function test_deposit_addresses( $addresses ) {
		//Make sure the order was placed and returns correct keys, etc...
	}

	//Time or Quantity?
	function test_trades( $trades ) {
		$keys = array( 'pair', 'base_cur', 'quote_cur', 'price', 'amount', 'timestamp', 'etc' );
	}

	//Depth?
	function test_orderbook( $orderbook ) {
		$keys = array( 'pair', 'base_cur', 'quote_cur', 'price', 'amount', 'timestamp', 'etc' );
	}

	/***********************

		Test Utility Methods

	 ***********************/

	function not_null( $not_null, $market_summary ) {
		foreach( $not_null as $field ) {
			if( is_null( $field ) ) {
				print_r( $market_summary );
				die( "\n\nRequired Not Null ($field)\n\n" );
			}
		}
		return true;
	}

	function above_zero( $above_zero, $market_summary ) {
		foreach( $above_zero as $field ) {
			if( is_nan( $market_summary[$field] ) || is_null( $market_summary[$field] ) || ! is_numeric( $market_summary[$field] ) || $market_summary[$field] <= 0 ) {
				print_r( $market_summary );
				die( "\n\nRequired Above Zero ($field)\n\n" );
			}
		}
		return true;
	}

	function equal_keys($keys, $arr) {
		if( sizeof( $keys ) != sizeof( $arr ) ) {
			$broken_keys = array_keys( $arr );
			print_r( array_diff( $keys, $broken_keys ) );
			print_r( array_diff( $broken_keys, $keys ) );
			print_r( $arr );
			die( "\n\nMismatched Array Keys" );
		}
		return true;
	}

	function numbers( $numbers, $arr ) {
		foreach( $numbers as $number ) {
			if( ! isset( $arr[$number] ) || is_null( $arr[$number] ) || ! is_numeric( $arr[$number] ) ) {
				print_r( $arr );
				die( "\n\nRequired Number ($number)\n\n" );
			}
		}
		return true;
	}

	function capitals( $capitals, $arr ) {
		foreach( $capitals as $capital ) {
			if( ! isset( $arr[$number] ) || is_null( $arr[$number] ) || ( ! strtoupper( $capital ) == $capital ) ) {
				print_r( $arr );
				die( "\n\nRequired Capital ($capital)\n\n" );
			}
		}
		return true;
	}

?>
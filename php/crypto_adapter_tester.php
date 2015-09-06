<?PHP

	function test_balances( $balances ) {
		$keys = array( 'type', 'currency', 'available', 'total', 'reserved', 'pending', 'btc_value' );
		$numbers = array( 'available', 'total', 'reserved', 'pending' );
		foreach( $balances as $balance ) {
			compare_keys( $keys, $balance );
		}
		test_numbers( $numbers, $balance );
	}

	function test_orders( $orders ) {
		//Make sure these have the correct keys, etc...
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
		compare_keys( $keys, $market_summary );

		if(  is_null( $market_summary['minimum_order_size_base'] ) && is_null( $market_summary['minimum_order_size_quote'] ) ) {
			print_r( $market_summary );
			die( "\n\nEither base or quote minimum order size is required!\n\n" );
		}

		$numbers = array( 'ask', 'base_volume', 'bid', 'high', 'last_price', 'low', 'quote_volume' );
		$strings = array( 'display_name', 'exchange' );
		$above_zero = array( );
		$not_null = array_merge( $numbers, $strings );

		//Tests:
		test_numbers( $numbers, $market_summary );

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

	function compare_keys($keys, $arr) {
		if( sizeof( $keys ) != sizeof( $arr ) ) {
			$broken_keys = array_keys( $arr );
			print_r( array_diff( $keys, $broken_keys ) );
			print_r( array_diff( $broken_keys, $keys ) );
			print_r( $arr );
			die( "\n\nMismatched Array Keys" );
		}
	}

	function test_numbers( $numbers, $arr ) {
		foreach( $numbers as $number ) {
			if( ! isset( $arr[$number] ) || is_null( $arr[$number] ) || ! is_numeric( $arr[$number] ) ) {
				print_r( $arr );
				die( "\n\nRequired Number ($number)\n\n" );
			}
		}

	}

?>
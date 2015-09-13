<?PHP

	class Tester {

		public function test_currencies( $currencies ) {
			foreach( $currencies as $currency ) {
				if( strtoupper( $currency ) !== $currency )
					die( "Currency must be uppercase: $currency" );
				if( strlen( $currency ) < 1 || strlen( $currency ) > 6 )
					die( "Currency must be 1-6 characters: $currency" );
			}
		}

		public function test_markets( $markets) {
			foreach( $markets as $market ) {
				if( strtoupper( $market ) !== $market )
					die( "Currency must be uppercase: $currency" );
				$curs = explode( "-", $market );
				if( sizeof( $curs ) !== 2 )
					die( "invalid market format: $market" );
				if( strlen( $curs[0] ) < 1 || strlen( $curs[0] ) > 6 )
					die( "Currency must be 1-6 characters: {$curs[0]}" );
				if( strlen( $curs[1] ) < 1 || strlen( $curs[1] ) > 6 )
					die( "Currency must be 1-6 characters: {$curs[1]}" );
			}
		}

		public function test_market_summaries( $market_summaries ) {
			//TODO test each market_summary in market_summaries vs market_summary directly
			//foreach( $markets as $market ) {
				//$market_summary = $Adapter->get_market_summary( $market );
				//TODO make sure $market_summary == $market_summaries[$market]
			//}

			foreach( $market_summaries as $market_summary ) {
				$this->test_market_summary( $market_summary );
			}
		}

		public function test_market_summary( $market_summary ) {
			//Data:
			$keys = array(	'ask', 'base_volume', 'bid', 'btc_volume', 'created', 'display_name', 'exchange', 
							'expiration', 'frozen', 'high', 'initial_margin', 'last_price', 
							'low', 'market', 'market_id', 'maximum_order_size', 'mid', 'minimum_margin', 
							'minimum_order_size_base', 'minimum_order_size_quote', 'open_buy_orders',
							'open_sell_orders', 'percent_change', 'price_precision', 
							'quote_volume', 'result', 'timestamp', 'verified_only', 'vwap' );

			$numbers = array( 'ask', 'base_volume', 'bid', 'high', 'last_price', 'low', 'quote_volume' );
			$strings = array( 'display_name', 'exchange' );
			$above_zero = array( );
			$not_null = array_merge( $numbers, $strings );

			//Tests:
			$this->equal_keys( $keys, $market_summary );
			$this->numbers( $numbers, $market_summary );
			$this->not_null( $not_null, $market_summary );
			$this->above_zero( $above_zero, $market_summary );

			if(  is_null( $market_summary['minimum_order_size_base'] ) && is_null( $market_summary['minimum_order_size_quote'] ) ) {
				print_r( $market_summary );
				die( "\n\nEither base or quote minimum order size is required!\n\n" );
			}
		}

		public function test_balances( $balances ) {
			$keys = array( 'type', 'currency', 'available', 'total', 'reserved', 'pending', 'btc_value' );
			$numbers = array( 'available', 'total', 'reserved', 'pending' );
			foreach( $balances as $balance ) {
				$this->equal_keys( $keys, $balance );
				$this->numbers( $numbers, $balance );
			}
		}

		public function test_volumes( $volumes ) {

		}

		public function test_deposit_addresses( $addresses ) {

		}

		public function test_open_orders( $active_orders ) {

		}

		public function test_completed_orders( $completed_orders ) {

		}

		public function test_buy_order( $buy_order ) {

		}

		public function test_sell_order( $sell_order ) {

		}
		
		//Time or Quantity?
		public function test_trades( $trades ) {
			$keys = array( 'pair', 'base_cur', 'quote_cur', 'price', 'amount', 'timestamp', 'etc' );
		}

		//Depth?
		public function test_orderbook( $orderbook ) {
			$keys = array( 'pair', 'base_cur', 'quote_cur', 'price', 'amount', 'timestamp', 'etc' );
		}

		/***********************

			Test Utility Methods

		 ***********************/

		public function not_null( $not_null, $market_summary ) {
			foreach( $not_null as $field ) {
				if( is_null( $field ) ) {
					print_r( $market_summary );
					die( "\n\nRequired Not Null ($field)\n\n" );
				}
			}
			return true;
		}

		public function above_zero( $above_zero, $market_summary ) {
			foreach( $above_zero as $field ) {
				if( is_nan( $market_summary[$field] ) || is_null( $market_summary[$field] ) || ! is_numeric( $market_summary[$field] ) || $market_summary[$field] <= 0 ) {
					print_r( $market_summary );
					die( "\n\nRequired Above Zero ($field)\n\n" );
				}
			}
			return true;
		}

		public function equal_keys($keys, $arr) {
			if( sizeof( $keys ) != sizeof( $arr ) ) {
				$broken_keys = array_keys( $arr );
				print_r( array_diff( $keys, $broken_keys ) );
				print_r( array_diff( $broken_keys, $keys ) );
				print_r( $arr );
				die( "\n\nMismatched Array Keys" );
			}
			return true;
		}

		public function numbers( $numbers, $arr ) {
			foreach( $numbers as $number ) {
				if( ! isset( $arr[$number] ) || is_null( $arr[$number] ) || ! is_numeric( $arr[$number] ) ) {
					print_r( $arr );
					die( "\n\nRequired Number ($number)\n\n" );
				}
			}
			return true;
		}

		public function capitals( $capitals, $arr ) {
			foreach( $capitals as $capital ) {
				if( ! isset( $arr[$number] ) || is_null( $arr[$number] ) || ( ! strtoupper( $capital ) == $capital ) ) {
					print_r( $arr );
					die( "\n\nRequired Capital ($capital)\n\n" );
				}
			}
			return true;
		}

	}

?>
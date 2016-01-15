<?PHP

	class BitstampAdapter implements CryptoExchange {

		public function __construct( $Exch ) {
			$this->exch = $Exch;
		}

		public function get_info() {
			return [];
		}

		public function withdraw( $account = "exchange", $currency = "BTC", $address = "1fsdaa...dsadf", $amount = 1 ) {
			return [];
		}

		public function get_currency_summary( $currency = "BTC" ) {
			return [];
		}
		
		public function get_currency_summaries( $currency = "BTC" ) {
			return [];
		}
		
		public function get_order( $orderid = "1" ) {
			return [];
		}
		
		public function cancel( $orderid="1", $opts = array() ) {
			return $this->exch->cancel_order($orderid);
		}

		public function cancel_all() {
			$result = $this->exch->cancel_all_orders();
			if( $result == 1 ) {
				return array( 'success' => true, 'error' => false, 'message' => $result );
			}
			return array( 'success' => false, 'error' => true, 'message' => $result );
		}

		public function buy( $pair="BTC-LTC", $amount=0, $price=0, $type="LIMIT", $opts=array() ) {
			$price = number_format( $price, 2, ".", "" );
			$amount = number_format( $amount, 4, ".", "" );
			$buy = $this->exch->buy( $amount, $price );
			if( isset( $buy['error'] ) )
				print_r( $buy );
			return $buy;
		}
		
		public function sell( $pair="BTC_LTC", $amount=0, $price=0, $type="LIMIT", $opts=array() ) {
			$price = number_format( $price, 2, ".", "" );
			$amount = number_format( $amount, 4, ".", "" );
			$sell = $this->exch->sell( $amount, $price );
			if( isset( $buy['error'] ) )
				print_r( $sell );
				return $sell;
		}

		public function get_open_orders() {
			if( isset( $this->open_orders ) )
				return $this->open_orders;
			$open_orders = $this->exch->open_orders();
			$this->open_orders = [];
			foreach( $open_orders as $open_order ) {
				$open_order['market'] = $market;
				$open_order['timestamp'] = $open_order['datetime'];
				$open_order['exchange'] = "bitstamp";
				$open_order['avg_execution_price'] = null;
				$open_order['side'] = null;
				$open_order['is_live'] = null;
				$open_order['is_cancelled'] = null;
				$open_order['is_hidden'] = null;
				$open_order['was_forced'] = null;
				$open_order['original_amount'] = null;
				$open_order['amount'] = null;
				$open_order['remaining_amount'] = null;
				$open_order['executed_amount'] = null;
				unset( $open_order['datetime'] );
				array_push( $this->open_orders, $open_order );
			}
			return $this->open_orders;
		}

		public function get_completed_orders() {
			if( isset( $this->completed_orders ) )
				return $this->completed_orders;
			$completed_orders = $this->exch->user_transactions( array( 'offset' => 0, 'limit' => 1000, 'sort' => 'desc' ) );
			$this->completed_orders = [];
			foreach( $completed_orders as $completed_order ) {
				$completed_order['market'] = "BTC-USD";
				$completed_order['exchange'] = "bitstamp";
				$completed_order['timestamp'] = $completed_order['datetime'];
				$completed_order['price'] = $completed_order['btc_usd'];
				$completed_order['amount'] = $completed_order['btc'];
				$completed_order['total'] = $completed_order['usd'];

				unset( $completed_order['datetime'] );
				unset( $completed_order['btc_usd'] );
				unset( $completed_order['usd'] );
				unset( $completed_order['btc'] );

				$completed_order['tid'] = null;
				$completed_order['fee_amount'] = null;
				$completed_order['fee_currency'] = null;

				array_push( $this->completed_orders, $completed_order );
			}

			return $this->completed_orders;
		}

		public function get_markets() {
			return array( 'BTC-USD' );
		}

		public function get_currencies() {
			return array( 'BTC', 'USD' );
		}
		
		public function deposit_address( $currency = "BTC" ){
			$response = array();
			if( $currency === "BTC" ) {
				$address = $this->exch->bitcoin_deposit_address();
				$response = array( 'wallet_type' => 'exchange', 'currency' => $currency, 'address' => $address, 'method' => "bitcoin" );
			}
			if( $currency === "XRP" ) {
				$address = $this->exch->ripple_address();
				$address = $address['address'];
				$response = array( 'wallet_type' => 'exchange', 'currency' => $currency, 'address' => $address, 'method' => "ripple" );
			}

			return $response;
		}
		
		public function deposit_addresses(){
			$addresses = [];
			array_push( $addresses, $this->deposit_address( "BTC" ) );
			array_push( $addresses, $this->deposit_address( "XRP" ) );
			return $addresses;
		}

		public function get_balances() {
			$balances = $this->get_balance();

			$response = [];

			$balance['type'] = "exchange";
			$balance['currency'] = "BTC";
			$balance['available'] = $balances['btc_available'];
			$balance['total'] = $balances['btc_balance'];
			$balance['reserved'] = $balances['btc_reserved'];
			$balance['pending'] = 0;
			$balance['btc_value'] = 0;

			array_push( $response, $balance );

			$balance['type'] = "exchange";
			$balance['currency'] = "USD";
			$balance['available'] = $balances['usd_available'];
			$balance['total'] = $balances['usd_balance'];
			$balance['reserved'] = $balances['usd_reserved'];
			$balance['pending'] = 0;
			$balance['btc_value'] = 0;

			array_push( $response, $balance );

			return $response;
		}

		public function get_balance( $currency = "BTC" ) {
			return $this->exch->balance();
		}

		public function get_market_summary( $market = "BTC-USD" ) {

			$market_summary = $this->exch->ticker();

			//Set variables:
			$market_summary['market'] = strtoupper( $market );
			$market_summary['exchange'] = 'bitstamp';
			$market_summary['display_name'] = $market;
			$market_summary['last_price'] = $market_summary['last'];
			$market_summary['mid'] = ( $market_summary['ask'] + $market_summary['bid'] ) / 2;
			$market_summary['result'] = true;
			$market_summary['created'] = null;
			$market_summary['frozen'] = null;
			$market_summary['percent_change'] = null;
			$market_summary['verified_only'] = null;
			$market_summary['expiration'] = null;
			$market_summary['initial_margin'] = null;
			$market_summary['maximum_order_size'] = null;
			$market_summary['minimum_margin'] = null;
			$market_summary['minimum_order_size_quote'] = 5;
			$market_summary['minimum_order_size_base'] = null;
			$market_summary['price_precision'] = 4;
			$market_summary['vwap'] = null;
			$market_summary['base_volume'] = $market_summary['volume'];
			$market_summary['quote_volume'] = bcmul( $market_summary['base_volume'], $market_summary['mid'], 32 );
			$market_summary['btc_volume'] = null;
			$market_summary['open_buy_orders'] = null;
			$market_summary['open_sell_orders'] = null;
			$market_summary['market_id'] = null;

			unset( $market_summary['last'] );
			unset( $market_summary['open'] );
			unset( $market_summary['volume'] );

			ksort( $market_summary );

			return array( $market_summary );
		}

		public function get_market_summaries() {
			if( isset( $this->market_summaries ) ) //cache
				return $this->market_summaries;

			$this->market_summaries = $this->get_market_summary( "BTC-USD" );
			return $this->market_summaries;
		}

		public function get_trades( $market = "BTC-USD", $time = 0 ) {
			$trades = $this->exch->transactions( $time );
			$results = [];
			foreach( $trades as $trade ) {
				$trade['exchange'] = 'bitstamp';
				$trade['market'] = $market;
				$trade['timestamp'] = $trade['date'];
				unset( $trade['date'] );
				array_push( $results, $trade );
			}
			return $results;
		}

		public function get_all_trades( $time = 0 ) {
			$result = $this->get_trades( "BTC-USD", $time );
			return $result;
		}

		public function get_orderbook( $market = 'BTC-USD', $depth = 0 ) {
			if( $market != 'BTC-USD' )
				return array( 'error' => true, 'message' => "Only BTC-USD is accepted" );
			$book = $this->exch->order_book();

			$results = [];
			foreach( $book['bids'] as $bid ) {
				$bid['timestamp' ] = $book['timestamp'];
				$bid['exchange'] = "bitstamp";
				$bid['market'] = $market;
				$bid['type'] = "buy";
				$bid['price'] = $bid[0];
				$bid['amount'] = $bid[1];
				unset( $bid[0] );
				unset( $bid[1] );
				array_push( $results, $bid );
			}
			foreach( $book['asks'] as $ask ) {
				$ask['timestamp' ] = $book['timestamp'];
				$ask['exchange'] = "bitstamp";
				$ask['market'] = $market;
				$ask['type'] = "sell";
				$ask['price'] = $ask[0];
				$ask['amount'] = $ask[1];
				unset( $ask[0] );
				unset( $ask[1] );
				array_push( $results, $ask );
			}
			return $results;
		}

		public function get_orderbooks( $depth = 0 ) {
			$result = $this->get_orderbook( "BTC-USD", $depth );
			return $result;
		}

	}

?>
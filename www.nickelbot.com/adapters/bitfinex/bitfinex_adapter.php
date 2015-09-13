<?PHP

	class BitfinexAdapter implements CryptoExchange {

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
			return $this->exch->order_cancel( $orderid );
		}

		public function cancel_all() {
			return $this->exch->order_cancel_all();
		}

		public function buy( $pair="BTC-USD", $amount=0, $price=0, $type="LIMIT", $opts=array() ) {
			$pair = strtolower( $pair );
			$pair = str_replace( "-", "", $pair );
			$buy = $this->exch->order_new( $pair, $amount, $price, "bitfinex", "buy", "exchange limit", true );
			if( isset( $sell['message'] ) )
				print_r( $buy );
			return $buy;
		}
		
		public function sell( $pair="BTC-USD", $amount=0, $price=0, $type="LIMIT", $opts=array() ) {
			$pair = strtolower( $pair );
			$pair = str_replace( "-", "", $pair );
			$sell = $this->exch->order_new( $pair, $amount, $price, "bitfinex", "sell", "exchange limit", true );
			if( isset( $sell['message'] ) )
				print_r( $sell );
			return $sell;
		}

		public function get_open_orders( $market = "BTC-USD" ) {
			if( isset( $this->open_orders ) )
				return $this->open_orders;
			$this->open_orders = $this->exch->orders();
			return $this->open_orders;
		}


		public function get_completed_orders( $market = "BTC-USD" ) {
			if( isset( $this->completed_orders ) )
				return $this->completed_orders;
			$markets = $this->get_markets();
			$this->completed_orders = [];
			foreach( $markets as $market ) {
				$market = str_replace( "-", "", strtoupper( $market ) );
				array_push( $this->completed_orders, $this->exch->mytrades( array( 'symbol' => $market, 'timestamp' => 0, 'until' => time(), 'limit_trades' => 10000 ) ) );
			}
			return $this->completed_orders;
		}

		public function get_markets() {
			$markets = $this->exch->symbols();
			$results = [];
			foreach( $markets as $market ) {
				$market = strtoupper( $market );
				array_push( $results, substr_replace($market, '-', 3, 0) );
			}
			return $results;
		}

		public function get_currencies() {
			return array( 'USD', 'BTC', 'LTC', 'DRK' );
		}

		public function deposit_address( $currency = "BTC" ){
			$wallet_types = array( "exchange", "deposit", "trading" );
			$addresses = [];
			foreach( $wallet_types as $wallet ) {
				$wallet_address = $this->exch->deposit_new( $currency, $wallet, $renew = 0 );
				if( $wallet_address['result'] === "success" ) {
					$wallet_address['wallet_type'] = $wallet;
					unset( $wallet_address['result'] );
					array_push( $addresses, $wallet_address );
				}
			}
			return $addresses;
		}
		
		public function deposit_addresses(){
			$currencies = array( "bitcoin", "litecoin", "darkcoin", "mastercoin" );
			$addresses = [];
			foreach( $currencies as $currency ) {
				$addresses = array_merge( $addresses, $this->deposit_address( $currency ) );
			}
			return $addresses;
		}

		public function get_balances() {
			$balances = $this->exch->balances();
			$response = [];

			foreach( $balances as $balance ) {
				$balance['total'] = $balance['amount'];
				$balance['reserved'] = $balance['total'] - $balance['available'];
				$balance['btc_value'] = 0;
				$balance['pending'] = 0;
				$balance['currency'] = strtoupper( $balance['currency'] );
				unset( $balance['amount'] );
				array_push( $response, $balance );
			}

			return $response;
		}

		public function get_balance( $currency = "BTC" ) {
			return [];
		}

		public function get_market_summary( $market = "BTC-LTC" ) {
			$market = strtolower( str_replace( "-", "", $market ) );
			if( isset( $this->market_summaries ) )
				foreach( $this->market_summaries as $market_summary )
					if( $market_summary['pair'] = $market )
						return $market_summary;
			$this->get_market_summaries();
			return $this->get_market_summary( $market );
		}

		public function get_market_summaries() {
			if( isset( $this->market_summaries ) ) //cache
				return $this->market_summaries;

			$market_summaries = $this->exch->symbols_details();
			$this->market_summaries = [];
			foreach( $market_summaries as $market_summary ) {
				$market_summary = array_merge( $market_summary, $this->exch->pubticker( $market_summary['pair'] ) );
				$market_summary['exchange'] = 'bitfinex';
				$market_summary['market'] = strtoupper( $market_summary['pair'] );
				$market_summary['pair'] = substr_replace($market_summary['pair'], '-', 3, 0);
				$market_summary['display_name'] = $market_summary['pair'];
				$market_summary['minimum_order_size_base'] = $market_summary['minimum_order_size'];
				$market_summary['minimum_order_size_quote'] = null;
				$market_summary['result'] = true;
				$market_summary['created'] = null;
				$market_summary['vwap'] = null;
				$market_summary['base_volume'] = $market_summary['volume'];
				$market_summary['quote_volume'] = bcmul( $market_summary['base_volume'], $market_summary['mid'], 32 );
				$market_summary['btc_volume'] = null;
				$market_summary['frozen'] = null;
				$market_summary['percent_change'] = null;
				$market_summary['verified_only'] = null;
				$market_summary['open_buy_orders'] = null;
				$market_summary['open_sell_orders'] = null;
				$market_summary['market_id'] = null;

				unset( $market_summary['pair'] );
				unset( $market_summary['volume'] );
				unset( $market_summary['minimum_order_size'] );

				ksort( $market_summary );

				array_push( $this->market_summaries, $market_summary );
			}
			return $this->market_summaries;
		}

		public function get_trades( $market = 'BTC-USD', $time = 0 ) {
			$result = $this->exch->trades();
			return $result;
		}

		public function get_orderbook( $market = 'BTC-USD', $depth = 20 ) {
			$result = $this->exch->book();
			return $result;
		}

	}
?>
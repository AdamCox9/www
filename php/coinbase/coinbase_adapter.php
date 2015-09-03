<?PHP

	class CoinbaseAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel( $orderid="1", $opts = array() ) {
			return $this->exch->cancel_order( $orderid );
		}
		
		public function cancel_all() {
			$orders = $this->get_open_orders();
			$results = [];
			if( is_array( $orders ) && count( $orders ) > 0 ) {
				foreach( $orders as $order ) {
					array_push( $results, $this->cancel( $order['id'] ) );
				}
			}
			return $results;
		}

		public function buy($pair='BTC-LTC',$amount="0",$price="0",$type="LIMIT",$opts=array()) {
			return $this->exch->create_order( array( 'side' => 'buy', 'product_id' => $pair, 'price' => $price, 'size' => $amount ) );
		}
		
		public function sell($pair='BTC-LTC',$amount="0",$price="0",$type="LIMIT",$opts=array()) {
			return $this->exch->create_order( array( 'side' => 'sell', 'product_id' => $pair, 'price' => $price, 'size' => $amount ) );
		}

		public function get_open_orders( $arr = array( 'pair' => 'btc_usd' ) ) {
			return $this->exch->get_orders();
		}

		public function get_markets() {
			$products = $this->exch->products();
			$response = [];
			foreach( $products as $product ) {
				array_push( $response, $product['id'] );
			}
			return array_map( 'strtoupper', $response );
		}

		public function get_currencies() {
			$currencies = $this->exch->currencies();
			$response = [];
			foreach( $currencies as $currency ) {
				array_push( $response, $currency['id'] );
			}
			return array_map( 'strtoupper', $response );
		}
		
		public function deposit_address($currency="BTC"){
			return [];
		}
		
		public function deposit_addresses(){
			return [];
		}

		public function get_balances() {
			$balances = $this->exch->accounts();

			$response = [];

			foreach( $balances as $balance ) {
				$balance['type'] = "exchange";
				$balance['total'] = $balance['balance'];
				$balance['reserved'] = $balance['hold'];
				$balance['pending'] = 0;
				$balance['btc_value'] = 0;

				unset( $balance['balance'] );
				unset( $balance['hold'] );
				unset( $balance['id'] );
				unset( $balance['profile_id'] );
				
				array_push( $response, $balance );
			}

			return $response;
		}

		public function get_balance($currency="BTC") {
			return [];
		}

		public function get_market_summary( $market = "BTC-LTC" ) {
			return [];
		}

		public function get_market_summaries() {
			$products = $this->exch->products();
			$response = [];
			foreach( $products as $market_summary ) {
				$market_summary['exchange'] = "coinbase";
				$market_summary = array_merge( $market_summary, $this->exch->products_ticker( $market_summary['id'] ) );
				$market_summary = array_merge( $market_summary, $this->exch->products_stats( $market_summary['id'] ) );

				//ticker might not have these avaiable:
				if( ! isset( $market_summary['high'] ) ) {
					$market_summary['high'] = 0;
				}
				if( ! isset( $market_summary['low'] ) ) {
					$market_summary['low'] = 0;
				}
				if( ! isset( $market_summary['volume'] ) ) {
					$market_summary['volume'] = 0;
				}

				$market_summary['pair'] = $market_summary['id'];
				$market_summary['minimum_order_size_quote'] = $market_summary['base_min_size'];
				$market_summary['minimum_order_size_base'] = bcmul( $market_summary['minimum_order_size_quote'], $market_summary['price'], 32);
				$market_summary['maximum_order_size'] = $market_summary['base_max_size'];
				$market_summary['timestamp'] = $market_summary['time'];
				$market_summary['mid'] = $market_summary['price'];
				$market_summary['last_price'] = $market_summary['price'];
				$market_summary['ask'] = $market_summary['price'];
				$market_summary['bid'] = $market_summary['price'];
				$market_summary['price_precision'] = 2;
				$market_summary['result'] = true;
				$market_summary['created'] = null;
				$market_summary['percent_change'] = null;
				$market_summary['frozen'] = null;
				$market_summary['verified_only'] = null;
				$market_summary['vwap'] = null;

				$market_summary['base_volume'] = $market_summary['volume'];
				$market_summary['quote_volume'] = bcmul( $market_summary['base_volume'] * $market_summary['mid'], 32);

				$market_summary['expiration'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['minimum_margin'] = null;
				$market_summary['open_buy_orders'] = null;
				$market_summary['open_sell_orders'] = null;
				$market_summary['market_id'] = null;
				if( isset( $market_summary['message'] ) )
					$market_summary['frozen'] = true;

				unset( $market_summary['volume'] );
				unset( $market_summary['message'] );
				unset( $market_summary['id'] );
				unset( $market_summary['base_min_size'] );
				unset( $market_summary['base_max_size'] );
				unset( $market_summary['time'] );
				unset( $market_summary['price'] );
				unset( $market_summary['quote_increment'] );
				unset( $market_summary['base_currency'] );
				unset( $market_summary['quote_currency'] );
				unset( $market_summary['base_currency'] );
				unset( $market_summary['open'] );
				unset( $market_summary['size'] );
				unset( $market_summary['open'] );
				unset( $market_summary['trade_id'] );

				ksort( $market_summary );

				array_push( $response, $market_summary );
			}
			return $response;
		}

		public function get_lendbook() {
			return [];
		}

		public function get_book() {
			return [];
		}

		public function get_lends() {
			return [];
		}

	}
?>
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

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			return $this->exch->create_order( array( 'side' => 'buy', 'product_id' => 'BTC-USD', 'price' => '100', 'size' => '0.01' ) );
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
			return $this->exch->create_order( array( 'side' => 'sell', 'product_id' => 'BTC-USD', 'price' => '300', 'size' => '0.01' ) );
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
		
		public function unconfirmed_btc(){
			return [];
		}
		
		public function bitcoin_deposit_address(){
			return [];
		}

		public function get_ticker($ticker="BTC-LTC") {
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

				$market_summary['pair'] = $market_summary['id'];
				unset( $market_summary['id'] );

				$market_summary['minimum_order_size'] = $market_summary['base_min_size'];
				unset( $market_summary['base_min_size'] );
				$market_summary['maximum_order_size'] = $market_summary['base_max_size'];
				unset( $market_summary['base_max_size'] );
				$market_summary['timestamp'] = $market_summary['time'];
				unset( $market_summary['time'] );
				$market_summary['mid'] = $market_summary['price'];
				$market_summary['last_price'] = $market_summary['price'];
				$market_summary['ask'] = $market_summary['price'];
				$market_summary['bid'] = $market_summary['price'];
				unset( $market_summary['price'] );
				$market_summary['price_precision'] = $market_summary['quote_increment'];
				unset( $market_summary['quote_increment'] );

				unset( $market_summary['base_currency'] );
				unset( $market_summary['quote_currency'] );
				unset( $market_summary['base_currency'] );
				unset( $market_summary['display_name'] );
				unset( $market_summary['open'] );
				unset( $market_summary['size'] );
				unset( $market_summary['open'] );
				unset( $market_summary['trade_id'] );

				$market_summary['vwap'] = null;
				$market_summary['base_volume'] = null;
				$market_summary['expiration'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['minimum_margin'] = null;

				array_push( $response, $market_summary );
			}
			return $response;
		}

		public function get_detailed_info() {
			return [];
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
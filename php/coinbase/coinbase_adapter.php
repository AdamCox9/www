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
			foreach( $products as $product ) {
				$market_summary = [];
				$market_summary = array_merge( $market_summary, $product );
				$market_summary = array_merge( $market_summary, $this->exch->products_ticker( $product['id'] ) );
				$market_summary = array_merge( $market_summary, $this->exch->products_stats( $product['id'] ) );
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
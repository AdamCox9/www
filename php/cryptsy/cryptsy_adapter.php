<?PHP

	class CryptsyAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel( $orderid="1", $opts = array() ) {
			return $this->exch->cancel_order( $orderid );
		}
		
		public function cancel_all() {
			$orders = $this->get_open_orders();
			$results = [];
			if( isset( $orders['data'] ) && isset( $orders['data']['buyorders'] ) ) {
				foreach( $orders['data']['buyorders'] as $order ) {
					array_push( $results, $this->cancel( $order['orderid'] ) );
				}
			}
			if( isset( $orders['data'] ) && isset( $orders['data']['sellorders'] ) ) {
				foreach( $orders['data']['sellorders'] as $order ) {
					array_push( $results, $this->cancel( $order['orderid'] ) );
				}
			}
			return $results;
		}

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			return $this->exch->create_order( array( 'marketid' => '3', 'ordertype' => 'buy', 'quantity' => '1000', 'price' => '0.0000001' ) );
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
			return $this->exch->create_order( array( 'marketid' => '3', 'ordertype' => 'sell', 'quantity' => '1', 'price' => '100' ) );
		}

		public function get_open_orders( $arr = array( 'pair' => 'btc_usd' ) ) {
			return $this->exch->get_orders();
		}

		public function get_markets() {
			$markets = $this->exch->markets();
			$response = [];
			foreach( $markets['data'] as $market ) {
				array_push( $response, $market['label'] );
			}
			$response = str_replace('/', '-', $response );
			return array_map( 'strtoupper', $response );
		}

		public function get_currencies() {
			$currencies = $this->exch->currencies();
			$response = [];
			foreach( $currencies['data'] as $currency ) {
				array_push( $response, $currency['code'] );
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
			$market = $this->exch->market( $market );
			$market = $market['data'];
			return $market;
		}

		public function get_market_summaries() {
			$markets = $this->exch->markets();
			$markets = $markets['data'];
			return $markets;
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
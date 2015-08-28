<?PHP

	class BittrexAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel($orderid="1", $opts = array() ) {
			return $this->exch->market_cancel( array("uuid" => $orderid ) );
		}
		
		public function cancel_all() {
			$result = $this->get_open_orders();
			$response = array();
			foreach( $result['result'] as $order ) {
				array_push($response,$this->cancel($order['OrderUuid']));
			}
			return $response;
		}

		public function buy( $pair='BTC_LTC', $amount="10", $price="0.01", $type="LIMIT", $opts=array() ) {
			if( $pair == 'btcusd' ) {
				$pair = 'BTC-LTC';
				$amount = '1';
				$price = '0.01';
			}
			return $this->exch->market_buylimit( array( 'market' => strtoupper($pair), 'quantity' => $amount, 'rate' => $price ) );
		}
		
		public function sell( $pair='BTC_LTC', $amount="10", $price="500", $type="LIMIT", $opts=array() ) {
			if( $pair == 'btcusd' ) {
				$pair = 'BTC-LTC';
				$amount = '1';
				$price = '0.02';
			}
			return $this->exch->market_selllimit( array( 'market' => strtoupper($pair), 'quantity' => $amount, 'rate' => $price ) );
		}

		public function get_open_orders( $pair = 'All' ) {
			return $this->exch->market_getopenorders();
		}

		public function get_markets() {
			$markets = $this->exch->getmarketsummaries();
			$response = [];
			foreach( $markets['result'] as $market ) {
				array_push( $response, $market['MarketName'] );
			}
			return array_map( 'strtoupper', $response );
		}

		public function get_currencies() {
			$currencies = $this->exch->getcurrencies();
			$response = [];
			foreach( $currencies['result'] as $currency ) {	
				array_push( $response, $currency['Currency'] );
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
			return $this->exch->getticker( array('market' => $ticker ) );
		}

		public function get_market_summary($market="BTC-LTC") {
			return $this->exch->getmarketsummary( array('market' => $market ) );
		}

		public function get_market_summaries() {
			$market_summaries = $this->exch->getmarketsummaries();
			return $market_summaries['result'];
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
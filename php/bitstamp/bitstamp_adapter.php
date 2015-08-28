<?PHP

	class BitstampAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel($orderid="1", $opts = array() ) {
			return $this->exch->cancel_order($orderid);
		}

		public function cancel_all() {
			return $this->exch->cancel_all_orders();
		}

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			return $this->exch->buy($amount, $price);
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
			return $this->exch->sell($amount, $price);
		}

		public function get_open_orders( $pair = 'All' ) {
			return $this->exch->open_orders();
		}

		public function get_markets() {
			return array( 'BTC-USD' );
		}

		public function get_balance() {
			return $this->exch->balance();
		}

		public function get_currencies() {
			return array( 'BTC', 'USD' );
		}
		
		public function unconfirmed_btc(){
			return $this->exch->unconfirmed_btc();
		}
		
		public function bitcoin_deposit_address(){
			return $this->exch->bitcoin_deposit_address();
		}

		public function get_ticker($ticker="BTC-LTC") {
			return [];
		}

		public function get_market_summary( $market = "BTC-LTC" ) {
			$ticker = $this->exch->ticker();
			$ticker['exchange'] = 'bitstamp';
			return array( $ticker );
		}

		public function get_market_summaries() {
			return $this->get_market_summary( "BTC-LTC" );
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
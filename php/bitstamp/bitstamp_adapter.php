<?PHP

	class BitstampAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel($orderid="1") {
			$this->exch->cancel_order($orderid);
		}

		public function cancel_all() {

		}

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			return $this->exch->buy($amount, $price);
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
			return $this->exch->sell($amount, $price);
		}

		public function get_open_orders() {
			return $this->exch->open_orders();
		}

		public function get_markets() {
			return $this->exch->ticker();
		}

		public function get_balance() {
			return $this->exch->balance();
		}

		public function get_currencies() {
			return $this->exch->get_currencies();
		}
		
		public function unconfirmed_btc(){
			return $this->exch->unconfirmed_btc();
		}
		
		public function bitcoin_deposit_address(){
			return $this->exch->bitcoin_deposit_address();
		}

		public function get_ticker($ticker="BTC-LTC") {

		}

		public function get_market_summary( $market = "BTC-LTC" ) {

		}

		public function get_market_summaries() {

		}

		public function get_detailed_info() {

		}

		public function get_lendbook() {

		}

		public function get_book() {

		}

		public function get_lends() {

		}

	}

?>
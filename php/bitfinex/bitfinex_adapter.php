<?PHP

	class BitfinexAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel($orderid="1") {

		}

		public function cancel_all() {
		}

		public function buy($pair='ltcbtc',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			return $this->exch->order_new($pair,$amount,$price,"bitfinex","buy",$type,true);
		}
		
		public function sell($pair='ltcbtc',$amount="1",$price="100",$type="LIMIT",$opts=array()) {
			return $this->exch->order_new($pair,$amount,$price,"bitfinex","sell",$type,true);
		}

		public function get_open_orders() {
			return $this->exch->get_open_orders();
		}

		public function get_markets() {
			return $this->exch->get_symbols();
		}

		public function get_currencies() {
			return $this->exch->get_symbols_details();
		}
		
		public function unconfirmed_btc(){
		
		}

		public function bitcoin_deposit_address(){
			return $this->exch->deposit_new();
		}

		public function get_ticker($ticker="BTC-LTC") {

		}

		public function get_market_summary( $market = "BTC-LTC" ) {
			return $this->get_trades();
		}

		public function get_market_summaries() {
			return $this->exch->get_stats();
		}

		public function get_detailed_info() {
			return $this->exch->get_pubticker();
		}

		public function get_lendbook() {
			$result = $this->exch->get_lendbook();
		}

		public function get_book() {
			$result = $this->exch->get_book();
		}

		public function get_lends() {
			$result = $this->exch->get_lends();
		}

	}
?>
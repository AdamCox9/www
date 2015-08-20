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
			return $this->exch->get_markets();
		}

		public function get_currencies() {
			return $this->exch->get_currencies();
		}

		public function make_buy_orders() {
		
		}
		
		public function make_sell_orders() {
		
		}
		
		public function unconfirmed_btc(){
		
		}

		public function bitcoin_deposit_address(){

		}

		public function get_ticker($ticker="BTC-LTC") {

		}

		public function get_market_summary( $market = "BTC-LTC" ) {

		}

		public function get_market_summaries() {

		}

		//Unauthenticated
		//$result = $Bitfinex->get_pubticker();
		//$result = $Bitfinex->get_stats();
		//$result = $Bitfinex->get_lendbook();
		//$result = $Bitfinex->get_book();
		//$result = $Bitfinex->get_trades();
		//$result = $Bitfinex->get_lends();
		//$result = $Bitfinex->get_symbols();
		//$result = $Bitfinex->get_symbols_details();

		//Authenticated
		
		//$result = $Bitfinex->deposit_new();

		//$result = $Bitfinex->order_new_multi();

		//print_r( $result );


	}
?>
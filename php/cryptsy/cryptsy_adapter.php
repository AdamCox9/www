<?PHP

	class CryptsyAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}


		/*
		$cryptsy_getinfo = $Cryptsy->query("getinfo");
		//echo "<pre>".print_r($cryptsy_getinfo, true)."</pre>";
		$cryptsy_get_total_btc_balance = 0;
		foreach( $cryptsy_getinfo['return']['balances_available_btc'] as $balance ) {
			$cryptsy_get_total_btc_balance += $balance;
		}
		echo "\nCryptsy Bitcoin Worth " . $cryptsy_get_total_btc_balance . "\n";
		echo "Cryptsy Available Bitcoin Balance " . $cryptsy_getinfo['return']['balances_available_btc']['BTC'] . "\n";
		*/

		public function cancel($orderid="1") {
		
		}
		
		public function cancel_all() {
		
		}

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
		
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
		
		}

		public function get_open_orders() {
		
		}

		public function get_markets() {
		
		}

		public function get_currencies() {
		
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
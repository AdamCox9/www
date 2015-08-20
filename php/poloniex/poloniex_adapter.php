<?PHP

	class PoloniexAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		//$poloniex_ticker = $Poloniex->get_ticker();
		//$poloniex_balances = $Poloniex->get_balances();
		//$poloniex_total_btc_balance = $Poloniex->get_total_btc_balance();

		//print_r( $poloniex_ticker );
		//print_r( $poloniex_balances );
		//print_r( $poloniex_total_btc_balance );

		//echo "\nPoloniex Total Bitcoin Balance " . $poloniex_total_btc_balance . "\n";
		//echo "Poloniex Available Bitcoin Balance " . $poloniex_balances['BTC'] . "\n";

		//$Poloniex->list_detailed_info();
		//$Poloniex->cancel_all_orders();
		//$Poloniex->make_buy_orders();
		//$Poloniex->make_sell_orders();


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
	}
?>
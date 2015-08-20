<?PHP

	class CryptsyAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

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
	}
?>
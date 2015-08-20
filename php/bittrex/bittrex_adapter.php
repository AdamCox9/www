<?PHP

	class BittrexAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel($orderid="1") {
			return $this->exch->cancel_order( array("uuid" => $orderid ) );
		}
		
		public function cancel_all() {
			$result = $this->get_open_orders();
			$response = array();
			foreach( $result['result'] as $order ) {
				array_push($response,$this->cancel($order['OrderUuid']));
			}
			return $response;
		}

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			return $this->exch->buyBTC($amount, $price);
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
			return $this->exch->sellBTC($amount, $price);
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

		//$result = $Bittrex->get_ticker( array('market' => 'BTC-LTC' ) );
		//$result = $Bittrex->get_market_summaries();
		//$result = $Bittrex->get_market_summary( array('market' => 'BTC-LTC' ) );
		//$result = $Bittrex->get_open_orders();
		//var_dump( $result );

	}

?>
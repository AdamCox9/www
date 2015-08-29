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
			$market_summaries = $market_summaries['result'];
			$response = [];
			foreach( $market_summaries as $market_summary ) {
				
				$market_summary['exchange'] = "bittrex";

				//TODO test these:
				$market_summary['pair'] = $market_summary['MarketName'];
				unset( $market_summary['MarketName'] );
				$market_summary['high'] = $market_summary['High'];
				unset( $market_summary['High'] );
				$market_summary['low'] = $market_summary['Low'];
				unset( $market_summary['Low'] );
				$market_summary['volume'] = $market_summary['Volume'];
				unset( $market_summary['Volume'] );
				$market_summary['last_price'] = $market_summary['Last'];
				unset( $market_summary['Last'] );
				$market_summary['base_volume'] = $market_summary['BaseVolume'];
				unset( $market_summary['BaseVolume'] );
				$market_summary['timestamp'] = $market_summary['TimeStamp'];
				unset( $market_summary['TimeStamp'] );
				$market_summary['bid'] = $market_summary['Bid'];
				unset( $market_summary['Bid'] );
				$market_summary['ask'] = $market_summary['Ask'];
				unset( $market_summary['Ask'] );

				//TODO add these:
				unset( $market_summary['Created'] );
				unset( $market_summary['PrevDay'] );
				unset( $market_summary['OpenBuyOrders'] );
				unset( $market_summary['OpenSellOrders'] );

				//TODO calculate these:
				$market_summary['expiration'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['maximum_order_size'] = null;
				$market_summary['mid'] = null;
				$market_summary['minimum_margin'] = null;
				$market_summary['minimum_order_size'] = null;
				$market_summary['price_precision'] = null;
				$market_summary['vwap'] = null;
				array_push( $response, $market_summary );
			}

			return $response;
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
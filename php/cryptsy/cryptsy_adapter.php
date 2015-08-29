<?PHP

	class CryptsyAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel( $orderid="1", $opts = array() ) {
			return $this->exch->cancel_order( $orderid );
		}
		
		public function cancel_all() {
			$orders = $this->get_open_orders();
			$results = [];
			if( isset( $orders['data'] ) && isset( $orders['data']['buyorders'] ) ) {
				foreach( $orders['data']['buyorders'] as $order ) {
					array_push( $results, $this->cancel( $order['orderid'] ) );
				}
			}
			if( isset( $orders['data'] ) && isset( $orders['data']['sellorders'] ) ) {
				foreach( $orders['data']['sellorders'] as $order ) {
					array_push( $results, $this->cancel( $order['orderid'] ) );
				}
			}
			return $results;
		}

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			return $this->exch->create_order( array( 'marketid' => '3', 'ordertype' => 'buy', 'quantity' => '1000', 'price' => '0.0000001' ) );
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
			return $this->exch->create_order( array( 'marketid' => '3', 'ordertype' => 'sell', 'quantity' => '1', 'price' => '100' ) );
		}

		public function get_open_orders( $arr = array( 'pair' => 'btc_usd' ) ) {
			return $this->exch->get_orders();
		}

		public function get_markets() {
			$markets = $this->exch->markets();
			$response = [];
			foreach( $markets['data'] as $market ) {
				array_push( $response, $market['label'] );
			}
			$response = str_replace('/', '-', $response );
			return array_map( 'strtoupper', $response );
		}

		public function get_currencies() {
			$currencies = $this->exch->currencies();
			$response = [];
			foreach( $currencies['data'] as $currency ) {
				array_push( $response, $currency['code'] );
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
			return [];
		}

		public function get_market_summary( $market = "BTC-LTC" ) {
			$market_summary = $this->exch->market( $market );
			$market_summary = $market_summary['data'];
			return $market_summary;
		}

		public function get_market_summaries() {
			$market_summaries = $this->exch->markets();
			$market_summaries = $market_summaries['data'];
			$response = [];
			foreach( $market_summaries as $market_summary ) {

				$market_summary['exchange'] = "cryptsy";

				$market_summary['pair'] = $market_summary['label'];
				$market_summary['volume'] = $market_summary['24hr']['volume'];
				$market_summary['base_volume'] = $market_summary['24hr']['volume_btc'];
				$market_summary['low'] = $market_summary['24hr']['price_low'];
				$market_summary['high'] = $market_summary['24hr']['price_high'];
				$market_summary['timestamp'] = $market_summary['last_trade']['timestamp'];
				$market_summary['mid'] = $market_summary['last_trade']['price'];
				$market_summary['bid'] = $market_summary['last_trade']['price'];
				$market_summary['ask'] = $market_summary['last_trade']['price'];
				$market_summary['last_price'] = $market_summary['last_trade']['price'];

				unset( $market_summary['24hr'] );
				unset( $market_summary['label'] );
				unset( $market_summary['last_trade'] );
				unset( $market_summary['maintenance_mode'] );
				unset( $market_summary['verifiedonly'] );
				unset( $market_summary['id'] );
				unset( $market_summary['market_currency_id'] );
				unset( $market_summary['coin_currency_id'] );

				$market_summary['expiration'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['minimum_margin'] = null;
				$market_summary['vwap'] = null;
				$market_summary['price_precision'] = null;
				$market_summary['maximum_order_size'] = null;
				$market_summary['minimum_order_size'] = null;

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
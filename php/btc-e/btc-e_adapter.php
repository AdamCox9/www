<?PHP

	class BtceAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel($orderid="1", $opts = array() ) {
			return $this->exch->CancelOrder( $arr = array( 'order_id' => $orderid ) );
		}
		
		public function cancel_all() {
			$results = array();
			$orders = $this->get_open_orders();
			if( isset( $orders['return'] ) && is_array( $orders['return'] ) ) {
				foreach( array_keys( $orders['return'] ) as $order_id ) {
					array_push( $results, $this->cancel( $order_id ) );
				}
			}
			return $results;
		}

		public function buy($pair='BTC_USD',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			if( strtolower($pair) == "BTCUSD" )
				$pair = "BTC_USD";
			return $this->exch->Trade( array( 'pair' => strtolower($pair), 'type' => 'buy', 'amount' => $amount, 'rate' => $price ) );
		}
		
		public function sell($pair='BTC_USD',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
			if( strtolower($pair) == "BTCUSD" )
				$pair = "BTC_USD";
			return $this->exch->Trade( array( 'pair' => strtolower($pair), 'type' => 'sell', 'amount' => $amount, 'rate' => $price ) );
		}

		public function get_open_orders( $pair = 'All' ) {
			$pair = strtolower( $pair );
			if( $pair == 'all' ) {
				return $this->exch->ActiveOrders( $arr = array() );
			}
			return $this->exch->ActiveOrders( $arr = array( 'pair' => $pair ) );
		}

		public function get_markets() {
			return array(	'BTC-USD', 
							'BTC-RUR', 
							'BTC-EUR', 
							'LTC-BTC', 
							'LTC-USD', 
							'LTC-RUR', 
							'LTC-EUR', 
							'NMC-BTC', 
							'NMC-USD', 
							'NVC-BTC', 
							'NVC-USD', 
							'USD-RUR', 
							'EUR-USD', 
							'EUR-RUR', 
							'PPC-BTC', 
							'PPC-USD' );
		}

		public function get_currencies() {
			return array(	'USD', 
							'RUR', 
							'EUR', 
							'BTC', 
							'LTC', 
							'NMC', 
							'NVC', 
							'PPC' );
		}
		
		public function unconfirmed_BTC(){
			return [];
		}
		
		public function bitcoin_deposit_address(){
			return [];
		}

		public function get_ticker($ticker="BTC-LTC") {
			return [];
		}

		public function get_market_summary( $market = "BTC-LTC" ) {
			$market = strtolower( str_replace( "-", "_", $market ) );
			return $this->exch->ticker( $market );
		}

		public function get_market_summaries() {
			$response = [];
			foreach( $this->get_markets() as $market ) {
				$market_summary = $this->get_market_summary( $market );
				$key = array_keys( $market_summary );
				$key = $key[0];
				$market_summary = $market_summary[$key];
				$market_summary['pair'] = $key;
				$market_summary['exchange'] = "btc-e";

				//TODO test these:
				$market_summary['volume'] = $market_summary['vol'];
				unset( $market_summary['vol'] );
				$market_summary['mid'] = $market_summary['avg'];
				unset( $market_summary['avg'] );
				$market_summary['bid'] = $market_summary['buy'];
				unset( $market_summary['buy'] );
				$market_summary['ask'] = $market_summary['sell'];
				unset( $market_summary['sell'] );
				$market_summary['last_price'] = $market_summary['last'];
				unset( $market_summary['last'] );
				$market_summary['timestamp'] = $market_summary['updated'];
				unset( $market_summary['updated'] );

				//TODO generate these:
				$market_summary['ask'] = null;
				$market_summary['base_volume'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['expiration'] = null;
				$market_summary['maximum_order_size'] = null;
				$market_summary['minimum_order_size'] = null;
				$market_summary['price_precision'] = null;
				$market_summary['minimum_margin'] = null;
				$market_summary['vwap'] = null;

				//TODO add these:
				unset( $market_summary['vol_cur'] );

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
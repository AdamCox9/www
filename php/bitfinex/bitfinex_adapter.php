<?PHP

	class BitfinexAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel($orderid="1", $opts = array() ) {
			return $this->exch->order_cancel($orderid);
		}

		public function cancel_all() {
			return $this->exch->order_cancel_all();
		}

		public function buy($pair='LTC-BTC',$amount=0,$price=0,$type="LIMIT",$opts=array()) {
			$pair = strtolower( $pair );
			$pair = str_replace( "-", "", $pair );
			echo $pair;
			return $this->exch->order_new($pair,$amount,$price,"bitfinex","buy","exchange limit",true);
		}
		
		public function sell($pair='LTC-BTC',$amount=0,$price=0,$type="LIMIT",$opts=array()) {
			$pair = strtolower( $pair );
			$pair = str_replace( "-", "", $pair );
			return $this->exch->order_new($pair,$amount,$price,"bitfinex","sell","exchange limit",true);
		}

		public function get_open_orders( $pair = 'All' ) {
			return $this->exch->orders();
		}

		public function get_markets() {
			$markets = $this->exch->symbols();
			$results = [];
			foreach( $markets as $market ) {
				$market = strtoupper( $market );
				array_push( $results, substr_replace($market, '-', 3, 0) );
			}
			return $results;
		}

		public function get_currencies() {
			return array( 'USD', 'BTC', 'LTC', 'DRK' );
		}
		
		public function deposit_address($currency="BTC"){
			return [];
		}
		
		public function deposit_addresses(){
			return [];
		}

		public function get_balances() {
			$balances = $this->exch->balances();
			$response = [];

			foreach( $balances as $balance ) {
				$balance['total'] = $balance['amount'];
				$balance['reserved'] = $balance['total'] - $balance['available'];
				$balance['btc_value'] = 0;
				$balance['pending'] = 0;
				unset( $balance['amount'] );
				array_push( $response, $balance );
			}

			return $response;
		}

		public function get_balance($currency="BTC") {
			return [];
		}

		public function get_market_summary( $market = "BTC-LTC" ) {
			$market = strtolower( str_replace( "-", "", $market ) );
			if( isset( $this->market_summaries ) )
				foreach( $this->market_summaries as $market_summary )
					if( $market_summary['pair'] = $market )
						return $market_summary;
			$this->get_market_summaries();
			return $this->get_market_summary( $market );
		}

		public function get_market_summaries() {
			if( isset( $this->market_summaries ) ) {
				return $this->market_summaries;
			}
			$market_summaries = $this->exch->symbols_details();
			$response = array();
			foreach( $market_summaries as $market_summary ) {
				$market_summary = array_merge( $market_summary, $this->exch->pubticker( $market_summary['pair'] ) );
				$market_summary['exchange'] = 'bitfinex';
				$market_summary['pair'] = strtoupper( $market_summary['pair'] );
				$market_summary['pair'] = substr_replace($market_summary['pair'], '-', 3, 0);
				$market_summary['display_name'] = $market_summary['pair'];
				$market_summary['minimum_order_size_quote'] = $market_summary['minimum_order_size'];
				$market_summary['minimum_order_size_base'] = bcmul( $market_summary['minimum_order_size'], $market_summary['mid'], $market_summary['price_precision']) + 0.05;
				$market_summary['result'] = true;
				$market_summary['created'] = null;
				$market_summary['vwap'] = null;
				$market_summary['base_volume'] = $market_summary['volume'];
				$market_summary['quote_volume'] = bcmul( $market_summary['mid'], $market_summary['base_volume'], 32 );
				$market_summary['frozen'] = null;
				$market_summary['percent_change'] = null;
				$market_summary['verified_only'] = null;
				$market_summary['open_buy_orders'] = null;
				$market_summary['open_sell_orders'] = null;
				$market_summary['market_id'] = null;

				unset( $market_summary['volume'] );
				unset( $market_summary['minimum_order_size'] );

				ksort( $market_summary );

				array_push( $response, $market_summary );
			}
			return $response;
		}

		public function get_lendbook() {
			$result = $this->exch->lendbook();
		}

		public function get_book() {
			$result = $this->exch->book();
		}

		public function get_lends() {
			$result = $this->exch->lends();
		}

	}
?>
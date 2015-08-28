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

		public function buy($pair='ltcbtc',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			return $this->exch->order_new($pair,$amount,$price,"bitfinex","buy",$type,true);
		}
		
		public function sell($pair='ltcbtc',$amount="1",$price="100",$type="LIMIT",$opts=array()) {
			return $this->exch->order_new($pair,$amount,$price,"bitfinex","sell",$type,true);
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
		
		public function unconfirmed_btc(){
			return [];
		}

		public function bitcoin_deposit_address(){
			return $this->exch->deposit_new();
		}

		public function get_ticker($ticker="BTC-LTC") {
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

			/*
				Pair
				Cur1
				Cur2
				24HourVolume
				24HourHigh
				24HourLow
				24HourChange
				MinOrderSize
				MaxOrderSize
				PricePrecision
				InitialMargin
				MinimumMargin
				Mid
				Bid
				Ask
				LastPrice
				Expiration
				Timestamp
			*/
		}

		public function get_market_summaries() {
			if( isset( $this->market_summaries ) ) {
				return $this->market_summaries;
			}
			$market_summaries = $this->exch->symbols_details();
			$response = array();
			foreach( $market_summaries as $market_summary ) {
				$pubticker = $this->exch->pubticker( $market_summary['pair'] );
				$pubticker['exchange'] = 'bitfinex';
				array_push( $response, array_merge( $market_summary, $pubticker ) );
			}
			return $response;
		}

		public function get_detailed_info() {
			return [];
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
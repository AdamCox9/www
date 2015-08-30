<?PHP

	class BitstampAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel($orderid="1", $opts = array() ) {
			return $this->exch->cancel_order($orderid);
		}

		public function cancel_all() {
			return $this->exch->cancel_all_orders();
		}

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			return $this->exch->buy($amount, $price);
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
			return $this->exch->sell($amount, $price);
		}

		public function get_open_orders( $pair = 'All' ) {
			return $this->exch->open_orders();
		}

		public function get_markets() {
			return array( 'BTC-USD' );
		}

		public function get_balance() {
			return $this->exch->balance();
		}

		public function get_currencies() {
			return array( 'BTC', 'USD' );
		}
		
		public function unconfirmed_btc(){
			return $this->exch->unconfirmed_btc();
		}
		
		public function bitcoin_deposit_address(){
			return $this->exch->bitcoin_deposit_address();
		}

		public function get_ticker($ticker="BTC-LTC") {
			return [];
		}

		public function get_market_summary( $market = "BTC-USD" ) {

			$market_summary = $this->exch->ticker();

			//Set variables:
			$market_summary['pair'] = $market;
			$market_summary['exchange'] = 'bitstamp';
			$market_summary['display_name'] = $market;
			$market_summary['last_price'] = $market_summary['last'];
			$market_summary['mid'] = ( $market_summary['ask'] + $market_summary['bid'] ) / 2;
			$market_summary['result'] = true;
			$market_summary['created'] = null;
			$market_summary['frozen'] = null;
			$market_summary['percent_change'] = null;
			$market_summary['verified_only'] = null;
			$market_summary['expiration'] = null;
			$market_summary['initial_margin'] = null;
			$market_summary['maximum_order_size'] = null;
			$market_summary['minimum_margin'] = null;
			//BUY ORDER: buy the base, sell the quote: BASE-QUOTE => BTC-USD=$232.32
			//SELL ORDER: sell the base, buy the quote: BASE-QUOTE => BTC-USD=$232.32
			$market_summary['minimum_order_size_quote'] = 2000; //minimum amount of quote currency
			$market_summary['minimum_order_size'] = bcdiv( $market_summary['minimum_order_size_quote'], $market_summary['mid'], 8 );
			$market_summary['price_precision'] = 2;
			$market_summary['vwap'] = null;
			$market_summary['base_volume'] = null;
			$market_summary['open_buy_orders'] = null;
			$market_summary['open_sell_orders'] = null;

			unset( $market_summary['last'] );

			return array( $market_summary );
		}

		public function get_market_summaries() {
			return $this->get_market_summary( "BTC-USD" );
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
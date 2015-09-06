<?PHP

	class BitstampAdapter implements CryptoExchange {

		public function __construct( $Exch ) {
			$this->exch = $Exch;
		}

		public function cancel( $orderid="1", $opts = array() ) {
			return $this->exch->cancel_order($orderid);
		}

		public function cancel_all() {
			return $this->exch->cancel_all_orders();
		}

		public function buy( $pair="BTC-LTC", $amount=0, $price=0, $type="LIMIT", $opts=array() ) {
			$price = number_format( $price, 2, ".", "" );
			$amount = number_format( $amount, 4, ".", "" );
			$buy = $this->exch->buy( $amount, $price );
			if( isset( $buy['error'] ) )
				print_r( $buy );
			return $buy;
		}
		
		public function sell( $pair="BTC_LTC", $amount=0, $price=0, $type="LIMIT", $opts=array() ) {
			$price = number_format( $price, 2, ".", "" );
			$amount = number_format( $amount, 4, ".", "" );
			$sell = $this->exch->sell( $amount, $price );
			if( isset( $buy['error'] ) )
				print_r( $sell );
				return $sell;
		}

		public function get_open_orders( $pair = 'All' ) {
			return $this->exch->open_orders();
		}

		public function get_markets() {
			return array( 'BTC-USD' );
		}

		public function get_currencies() {
			return array( 'BTC', 'USD' );
		}
		
		public function deposit_address( $currency = "BTC" ){
			return [];
		}
		
		public function deposit_addresses(){
			return [];
		}

		public function get_balances() {
			$t_balance = $this->get_balance();

			$balances = [];

			$balance['type'] = "exchange";
			$balance['currency'] = "BTC";
			$balance['available'] = $t_balance['btc_available'];
			$balance['total'] = $t_balance['btc_balance'];
			$balance['reserved'] = $t_balance['btc_reserved'];
			$balance['pending'] = 0;
			$balance['btc_value'] = 0;

			array_push( $balances, $balance );

			$balance['type'] = "exchange";
			$balance['currency'] = "USD";
			$balance['available'] = $t_balance['usd_available'];
			$balance['total'] = $t_balance['usd_balance'];
			$balance['reserved'] = $t_balance['usd_reserved'];
			$balance['pending'] = 0;
			$balance['btc_value'] = 0;

			array_push( $balances, $balance );

			return $balances;
		}

		public function get_balance( $currency = "BTC" ) {
			return $this->exch->balance();
		}

		public function get_worth() {
			return Utilities::get_worth( $this->get_balances(), $this->get_market_summaries() );
		}

		public function get_market_summary( $market = "BTC-USD" ) {

			$market_summary = $this->exch->ticker();

			//Set variables:
			$market_summary['pair'] = strtoupper( $market );
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
			$market_summary['minimum_order_size_quote'] = 5;
			$market_summary['minimum_order_size_base'] = null;
			$market_summary['price_precision'] = 4;
			$market_summary['vwap'] = null;
			$market_summary['base_volume'] = $market_summary['volume'];
			$market_summary['quote_volume'] = bcmul( $market_summary['base_volume'], $market_summary['mid'], 32 );
			$market_summary['btc_volume'] = null;
			$market_summary['open_buy_orders'] = null;
			$market_summary['open_sell_orders'] = null;
			$market_summary['market_id'] = null;

			unset( $market_summary['last'] );
			unset( $market_summary['volume'] );

			ksort( $market_summary );

			return array( $market_summary );
		}

		public function get_market_summaries() {
			if( isset( $this->market_summaries ) ) //cache
				return $this->market_summaries;

			$this->market_summaries = $this->get_market_summary( "BTC-USD" );
			return $this->market_summaries;
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
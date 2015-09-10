<?PHP

	class PoloniexAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel( $orderid="1", $opts = array() ) {
			return $this->exch->cancelOrder( $opts['pair'], $orderid );
		}
		
		public function buy($pair='BTC-LTC',$amount=0,$price=0,$type="LIMIT",$opts=array()) {
			$pair = explode( "-", $pair );
			$pair = $pair[1] . "-" . $pair[0];
			$pair = strtolower( $pair );
			$pair = str_replace( "-", "_", $pair );
			$buy = $this->exch->buy($pair,$price,$amount);
			if( isset( $buy['error'] ) )
				print_r( $buy );
			return $buy;
		}
		
		public function sell($pair='BTC-LTC',$amount=0,$price=0,$type="LIMIT",$opts=array()) {
			$pair = explode( "-", $pair );
			$pair = $pair[1] . "-" . $pair[0];
			$pair = strtolower( $pair );
			$pair = str_replace( "-", "_", $pair );
			$sell = $this->exch->sell($pair,$price,$amount);
			if( isset( $sell['error'] ) )
				print_r( $sell );
			return $sell;
		}

		public function get_open_orders() {
			if( isset( $this->open_orders ) )
				return $this->open_orders;
			$this->open_orders = $this->exch->returnOpenOrders( 'All' );
			return $this->open_orders;
		}

		public function get_completed_orders() {
			if( isset( $this->completed_orders ) )
				return $this->completed_orders;
			$this->completed_orders = $this->exch->returnTradeHistory( 'All' );
			return $this->completed_orders;
		}

		//BTC_USD, BTC_LTC, LTC_USD, etc...
		public function get_markets() {
			$markets = array_map( 'strtoupper', array_keys( $this->exch->returnTicker() ) );
			return str_replace('_', '-', $markets );
		}

		//BTC, LTC, USD, etc...
		public function get_currencies() {
			return array_map( 'strtoupper', array_keys( $this->exch->returnCurrencies() ) );
		}
		
		public function deposit_address($currency="BTC"){
			return [];
		}
		
		public function deposit_addresses(){
			$addresses = $this->exch->returnDepositAddresses();
			$currencies = array_diff( $this->get_currencies(), array_keys( $addresses ) );
			foreach( $currencies as $currency ) {
				$this->exch->generateNewAddress( $currency );
			}
			$addresses = $this->exch->returnDepositAddresses();
			return $addresses;
		}

		public function get_balances() {
			$balances = $this->exch->returnCompleteBalances();
			$response = [];

			foreach( $balances as $key => $balance ) {
				$balance['type'] = 'exchange'; //Or, is it all accounts?
				$balance['currency'] = $key;
				$balance['reserved'] = isset( $balance['onOrders'] ) ? $balance['onOrders'] : 0;
				$balance['total'] = $balance['available'] + $balance['reserved'];
				$balance['btc_value'] = $balance['btcValue'];
				$balance['pending'] = 0;

				unset( $balance['onOrders'] );
				unset( $balance['btcValue'] );

				array_push( $response, $balance );
			}

			return $response;
		}

		public function get_balance($currency="BTC") {
			return [];
		}

		public function get_worth() {
			return Utilities::get_worth( $this->get_balances(), $this->get_market_summaries() );
		}

		public function get_market_summary( $market = "BTC-LTC" ) {
			$pair = strtoupper($pair);
			$prices = $this->exch->returnTicker();
			if(isset($prices[$pair]))
				return $prices[$pair];
			else
				return array();
		}

		public function get_market_summaries() {
			$market_summaries = $this->exch->returnTicker();
			$response = [];
			foreach( $market_summaries as $key => $market_summary ) {
				$market_summary['pair'] = strtoupper( str_replace( "_", "-", $key ) );
				$msmn = explode( "-", $market_summary['pair'] );
				$market_summary['pair'] = $msmn[1] . "-" . $msmn[0];
				$market_summary['exchange'] = "poloniex";
				$market_summary['last_price'] = $market_summary['last'];
				$market_summary['ask'] = is_null( $market_summary['lowestAsk'] ) ? 0 : $market_summary['lowestAsk'];
				$market_summary['bid'] = is_null( $market_summary['highestBid'] ) ? 0 : $market_summary['highestBid'];
				$market_summary['quote_volume'] = $market_summary['baseVolume'];
				$market_summary['base_volume'] = $market_summary['quoteVolume'];
				$market_summary['btc_volume'] = null;
				$market_summary['low'] = $market_summary['low24hr'];
				$market_summary['high'] = $market_summary['high24hr'];
				$market_summary['display_name'] = $market_summary['pair'];
				$market_summary['percent_change'] = $market_summary['percentChange'];
				$market_summary['frozen'] = $market_summary['isFrozen'];
				$market_summary['result'] = true;
				$market_summary['created'] = null;
				$market_summary['verified_only'] = null;
				$market_summary['expiration'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['maximum_order_size'] = null;
				$market_summary['mid'] = null;
				$market_summary['minimum_margin'] = null;

				if( strpos( $market_summary['pair'], "-XMR" ) !== FALSE )
					$market_summary['minimum_order_size_quote'] = '0.01000000';
				if( strpos( $market_summary['pair'], "-USDT" ) !== FALSE )
					$market_summary['minimum_order_size_quote'] = '0.01000000';
				if( strpos( $market_summary['pair'], "-BTC" ) !== FALSE )
					$market_summary['minimum_order_size_quote'] = '0.00050000';

				$market_summary['minimum_order_size_base'] = null;
				$market_summary['price_precision'] = 8;
				$market_summary['timestamp'] = null;
				$market_summary['vwap'] = null;
				$market_summary['open_buy_orders'] = null;
				$market_summary['open_sell_orders'] = null;
				$market_summary['market_id'] = null;

				unset( $market_summary['last'] );
				unset( $market_summary['lowestAsk'] );
				unset( $market_summary['highestBid'] );
				unset( $market_summary['baseVolume'] );
				unset( $market_summary['quoteVolume'] );
				unset( $market_summary['low24hr'] );
				unset( $market_summary['high24hr'] );
				unset( $market_summary['percentChange'] );
				unset( $market_summary['isFrozen'] );

				ksort( $market_summary );

				array_push( $response, $market_summary );
			}
			return $response;
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

		//_____Cancel all orders:
		function cancel_all()
		{
			$pairs = $this->get_markets();
			$results = [];
			foreach( $pairs as $key ) {
				$key = str_replace( "-", "_", $key );
				$open_orders = $this->get_open_orders($key);
				if( is_array( $open_orders ) ) {
					foreach( $open_orders as $open_order ) {
						if( isset( $open_order['orderNumber'] ) ) {
							array_push($results, $this->cancel($open_order['orderNumber'], array( 'pair' => $key ) ) );
						}
					}
				}
			}
			return $results;
		}

	}
?>
<?PHP

	class BittrexAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function get_info() {
			return [];
		}

		public function withdraw( $account = "exchange", $currency = "BTC", $address = "1fsdaa...dsadf", $amount = 1 ) {
			return [];
		}

		public function get_currency_summary( $currency = "BTC" ) {
			return [];
		}
		
		public function get_currency_summaries( $currency = "BTC" ) {
			return [];
		}
		
		public function get_order( $orderid = "1" ) {
			return [];
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

		public function buy( $pair="LTC-BTC", $amount=0, $price=0, $type="LIMIT", $opts=array() ) {
			$pair = explode( "-", $pair );
			$pair = $pair[1] . "-" . $pair[0];
			return $this->exch->market_buylimit( array( 'market' => strtoupper($pair), 'quantity' => $amount, 'rate' => $price ) );
		}
		
		public function sell( $pair="LTC-BTC", $amount=0, $price=0, $type="LIMIT", $opts=array() ) {
			$pair = explode( "-", $pair );
			$pair = $pair[1] . "-" . $pair[0];
			return $this->exch->market_selllimit( array( 'market' => strtoupper($pair), 'quantity' => $amount, 'rate' => $price ) );
		}

		public function get_open_orders( $market = "BTC-USD" ) {
			if( isset( $this->open_orders ) )
				return $this->open_orders;
			$this->open_orders = $this->exch->market_getopenorders();
			return $this->open_orders;
		}

		public function get_completed_orders( $market = "BTC-USD" ) {
			if( isset( $this->completed_orders ) )
				return $this->completed_orders;
			$this->completed_orders = $this->exch->account_getorderhistory( array( 'market' => 'BTC-LTC', 'count' => 100 ) );
			return $this->completed_orders;
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

		public function deposit_address( $currency = "BTC" ){
			if( ! isset( $this->cnt ) )
				$this->cnt = 0;
			if( $this->cnt > 5 )
				return false;
			$address = $this->exch->account_getdepositaddress( array( 'currency' => $currency ) );
			if( $address['message'] == 'CURRENCY_OFFLINE' )
				return FALSE;
			if( $address['success'] == 1 ) {
				if( $address['result']['Address'] == "" ) {
					sleep( 5 );
					$this->cnt++;
					return $this->deposit_address( $currency );
				}
				return $address['result'];
			}
			if( $address['message'] == 'ADDRESS_GENERATING' ) {
				sleep( 5 );
				$this->cnt++;
				return $this->deposit_address( $currency );
			}
			return false;
		}
		
		public function deposit_addresses(){
			$currencies = $this->get_currencies();
			$addresses = [];
			foreach( $currencies as $currency ) {
				$address = $this->deposit_address( $currency );
				if( $address ) {
					array_push( $addresses, $address );
				}
			}
			return $addresses;
		}

		public function get_balances() {
			$balances = $this->exch->account_getbalances();
			if( $balances['success'] == 1 )
				$balances = $balances['result'];
			else
				return [];

			$results = [];
			foreach( $balances as $balance ) {
				$balance['type'] = "exchange";
				$balance['currency'] = strtoupper( $balance['Currency'] );
				$balance['total'] = $balance['Balance'];
				$balance['available'] = $balance['Available'];
				$balance['pending'] = $balance['Pending'];
				$balance['reserved'] = $balance['total'] - $balance['available'];
				$balance['btc_value'] = 0;

				unset( $balance['Currency'] );
				unset( $balance['Balance'] );
				unset( $balance['Available'] );
				unset( $balance['Pending'] );
				unset( $balance['CryptoAddress'] );

				array_push( $results, $balance );
			}
			return $results;
		}

		public function get_balance( $currency="BTC" ) {
			return [];
		}

		public function get_worth() {
			return Utilities::get_worth( $this->get_balances(), $this->get_market_summaries() );
		}

		public function get_market_summary( $market="LTC-BTC" ) {
			return $this->exch->getmarketsummary( array('market' => $market ) );
		}

		public function get_market_summaries() {
			if( isset( $this->market_summaries ) ) //cache
				return $this->market_summaries;
			
			$market_summaries = $this->exch->getmarketsummaries();
			$market_summaries = $market_summaries['result'];
			$this->market_summaries = [];
			foreach( $market_summaries as $market_summary ) {
				$market_summary['exchange'] = "bittrex";
				$msmn = explode( "-", $market_summary['MarketName'] );
				$market_summary['pair'] = $msmn[1] . "-" . $msmn[0];
				$market_summary['high'] = $market_summary['High'];
				$market_summary['low'] = $market_summary['Low'];
				$market_summary['base_volume'] = $market_summary['Volume'];
				$market_summary['quote_volume'] = $market_summary['BaseVolume'];
				$market_summary['btc_volume'] = null;
				$market_summary['last_price'] = $market_summary['Last'];
				$market_summary['timestamp'] = $market_summary['TimeStamp'];
				$market_summary['bid'] = is_null( $market_summary['Bid'] ) ? 0 : $market_summary['Bid'];
				$market_summary['ask'] = is_null( $market_summary['Ask'] ) ? 0 : $market_summary['Ask'];
				$market_summary['display_name'] = $market_summary['pair'];
				$market_summary['result'] = true;
				$market_summary['created'] = $market_summary['Created'];
				$market_summary['open_buy_orders'] = $market_summary['OpenBuyOrders'];
				$market_summary['open_sell_orders'] = $market_summary['OpenSellOrders'];
				$market_summary['percent_change'] = null;
				$market_summary['frozen'] = null;
				$market_summary['verified_only'] = null;
				$market_summary['expiration'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['maximum_order_size'] = null;
				$market_summary['mid'] = ( $market_summary['bid'] + $market_summary['ask'] ) / 2;
				$market_summary['minimum_margin'] = null;
				$market_summary['minimum_order_size_quote'] = 0.00050000;
				$market_summary['minimum_order_size_base'] = null;
				$market_summary['price_precision'] = 8;
				$market_summary['vwap'] = null;
				$market_summary['market_id'] = null;

				unset( $market_summary['OpenBuyOrders'] );
				unset( $market_summary['OpenSellOrders'] );
				unset( $market_summary['MarketName'] );
				unset( $market_summary['High'] );
				unset( $market_summary['Low'] );
				unset( $market_summary['Volume'] );
				unset( $market_summary['Last'] );
				unset( $market_summary['BaseVolume'] );
				unset( $market_summary['TimeStamp'] );
				unset( $market_summary['Bid'] );
				unset( $market_summary['Ask'] );
				unset( $market_summary['Created'] );
				unset( $market_summary['PrevDay'] );

				ksort( $market_summary );

				array_push( $this->market_summaries, $market_summary );
			}

			return $this->market_summaries;
		}

		//TODO convert the $time to $count
		public function get_trades( $market = "BTC-USD", $time = 0 ) {
			$result = $this->exch->getmarkethistory( array( 'market' => $market, 'count' => 20 ) );
			return $result;
		}

		public function get_orderbook( $market = "BTC-USD", $depth = 0 ) {
			$result = $this->exch->getorderbook( array( 'market' => $market, 'type' => "both", 'depth' => $depth ) );
			return $result;
		}

	}

?>
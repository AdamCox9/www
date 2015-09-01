<?PHP

	class BterAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel( $orderid="1", $opts = array() ) {
			return $this->exch->cancelorder( array( 'order_id' => $orderid ) );
		}
		
		public function cancel_all() {
			$json = $this->get_open_orders();
			$results = array();
			foreach( $json['orders'] as $order ) {
				$order['detailedInfo'] = $this->exch->cancelorder( array( 'order_id' => $order['id'] ) );
				array_push($results,$order);
			}
			return $results;
		}

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
			if( $pair == 'btcusd' ) {
				$pair = 'ltc_btc';
				$amount = '1';
				$price = '0.01';
			}
			return $this->exch->placeorder( array('pair' => $pair, 'type' => 'BUY', 'rate' => $price, 'amount' => $amount ) );
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
			if( $pair == 'btcusd' ) {
				$pair = 'ltc_btc';
				$amount = '1';
				$price = '0.02';
			}
			return $this->exch->placeorder( array('pair' => $pair, 'type' => 'SELL', 'rate' => $price, 'amount' => $amount ) );
		}

		public function get_open_orders( $pair = 'btc_usd' )
		{
			$json = $this->exch->orderlist();

			/*$orders = array();
			foreach( $json['orders'] as $order ) {
				$order['detailedInfo'] = $this->exch->getorder($order['id']);
				array_push($orders,$order);
			}*/

			return $json;
		}

		public function get_markets() {
			$markets = $this->exch->pairs();
			$markets = str_replace('_', '-', $markets );
			return array_map( 'strtoupper', $markets );
		}

		public function get_currencies() {
			$currencies = $this->exch->marketlist();
			$response = [];
			foreach( $currencies['data'] as $currency ) {
				array_push( $response, $currency['symbol'] );
			}
			return array_map('strtoupper',$response);
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
			$market = explode( "-", strtolower( $market ) );
			return $this->exch->ticker( $market[0], $market[1] );;
		}

		public function get_market_summaries() {
			$tickers = $this->exch->tickers();
			$response = [];

			$market_info = $this->exch->marketinfo();
			$market_info = $market_info['pairs'];
			$markets = [];
			foreach( $market_info as $market ) {
				$key = array_keys( $market );
				$key = $key[0];
				$markets[$key] = $market[$key];
			}

			foreach( $tickers as $key => $market_summary ) {
				$market_summary['pair'] = $key;
				$market_summary['exchange'] = "bter";
				$market_summary = array_merge( $market_summary, $markets[$key] );
				$curs = explode( "_", $key );
				$cur1 = $curs[0];
				$cur2 = $curs[1];
				$market_summary['mid'] = $market_summary['avg'];
				$market_summary['bid'] = $market_summary['buy'];
				$market_summary['ask'] = $market_summary['sell'];
				$market_summary['last_price'] = $market_summary['last'];
				$market_summary['display_name'] = $market_summary['pair'];
				$market_summary['percent_change'] = $market_summary['rate_change_percentage'];
				$market_summary['quote_volume'] = $market_summary['vol_'.$cur1];
				$market_summary['base_volume'] = $market_summary['vol_'.$cur2];
				$market_summary['created'] = null;
				$market_summary['open_buy_orders'] = null;
				$market_summary['open_sell_orders'] = null;
				$market_summary['vwap'] = null;
				$market_summary['frozen'] = null;
				$market_summary['expiration'] = null;
				$market_summary['verified_only'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['maximum_order_size'] = null;
				$market_summary['minimum_margin'] = null;
				//BUY ORDER: buy the base, sell the quote: BASE-QUOTE => BTC-USD=$232.32
				//SELL ORDER: sell the base, buy the quote: BASE-QUOTE => BTC-USD=$232.32
				$market_summary['minimum_order_size_base'] = $market_summary['min_amount'];
				$market_summary['minimum_order_size_quote'] = bcmul( $market_summary['minimum_order_size_base'], $market_summary['mid'], 32 );;
				$market_summary['price_precision'] = $market_summary['decimal_places'];
				$market_summary['timestamp'] = null;
				$market_summary['vwap'] = null;
				$market_summary['market_id'] = null;

				unset( $market_summary['fee'] );
				unset( $market_summary['min_amount'] );
				unset( $market_summary['avg'] );
				unset( $market_summary['decimal_places'] );
				unset( $market_summary['buy'] );
				unset( $market_summary['sell'] );
				unset( $market_summary['last'] );
				unset( $market_summary['rate_change_percentage'] );
				unset( $market_summary['vol_'.$cur1] );
				unset( $market_summary['vol_'.$cur2] );

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

	}
?>
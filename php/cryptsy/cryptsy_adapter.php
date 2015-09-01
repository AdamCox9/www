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

		public function buy( $pair=null, $amount="0", $price="0", $type="LIMIT", $opts=array() ) {
			$pair = str_replace( "-", "_", strtolower( $pair ) );
			return $this->exch->create_order( array( 'marketid' => $opts['market_id'], 'ordertype' => 'buy', 'quantity' => (float) $amount, 'price' => (float) $price ) );
		}
		
		public function sell( $pair=null, $amount="0", $price="0", $type="LIMIT", $opts=array() ) {
			$pair = str_replace( "-", "_", strtolower( $pair ) );
			return $this->exch->create_order( array( 'marketid' => $opts['market_id'], 'ordertype' => 'sell', 'quantity' => (float) $amount, 'price' => (float) $price ) );
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

			$tickers = $this->exch->markets_ticker();
			$m_tickers = [];
			foreach( $tickers['data'] as $ticker ) {
				$m_tickers[$ticker['id']] = array( "bid" => $ticker['bid'], "ask" => $ticker['ask'] );
			}

			$response = [];
			foreach( $market_summaries as $market_summary ) {

				$market_summary['exchange'] = "cryptsy";
				$market_summary = array_merge( $market_summary, $m_tickers[$market_summary['id']] );
				$market_summary['pair'] = strtoupper( str_replace( "/", "-", $market_summary['label'] ) );
				$market_summary['quote_volume'] = $market_summary['24hr']['volume'];
				$market_summary['base_volume'] = $market_summary['24hr']['volume_btc'];
				$market_summary['low'] = $market_summary['24hr']['price_low'];
				$market_summary['high'] = $market_summary['24hr']['price_high'];
				$market_summary['timestamp'] = $market_summary['last_trade']['timestamp'];
				$market_summary['mid'] = ( $market_summary['ask'] + $market_summary['bid'] ) / 2;
				$market_summary['last_price'] = $market_summary['last_trade']['price'];
				$market_summary['frozen'] = $market_summary['maintenance_mode'];
				$market_summary['verified_only'] = $market_summary['verifiedonly'];
				$market_summary['display_name'] = $market_summary['label'];
				$market_summary['result'] = true;
				$market_summary['created'] = null;
				$market_summary['percent_change'] = null;
				$market_summary['expiration'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['minimum_margin'] = null;
				$market_summary['vwap'] = null;
				$market_summary['price_precision'] = 8;
				$market_summary['maximum_order_size'] = null;
				//BUY ORDER: buy the base, sell the quote: BASE-QUOTE => BTC-USD=$232.32
				//SELL ORDER: sell the base, buy the quote: BASE-QUOTE => BTC-USD=$232.32
				$market_summary['minimum_order_size_base'] = 0.00050000;
				$market_summary['minimum_order_size_quote'] = bcmul( $market_summary['minimum_order_size_base'], $market_summary['mid'], 32 );
				$market_summary['open_buy_orders'] = null;
				$market_summary['open_sell_orders'] = null;
				$market_summary['market_id'] = $market_summary['id'];

				unset( $market_summary['24hr'] );
				unset( $market_summary['label'] );
				unset( $market_summary['last_trade'] );
				unset( $market_summary['verifiedonly'] );
				unset( $market_summary['maintenance_mode'] );
				unset( $market_summary['id'] );
				unset( $market_summary['market_currency_id'] );
				unset( $market_summary['coin_currency_id'] );

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
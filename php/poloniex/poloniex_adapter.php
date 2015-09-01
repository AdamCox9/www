<?PHP

	class PoloniexAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel( $orderid="1", $opts = array() ) {
			return $this->exch->cancelOrder( $opts['pair'], $orderid );
		}
		
		public function buy($pair='BTC-LTC',$amount=0,$price=0,$type="LIMIT",$opts=array()) {
			$pair = strtolower( $pair );
			$pair = str_replace( "-", "_", $pair );
			return $this->exch->buy($pair,$price,$amount);
		}
		
		public function sell($pair='BTC-LTC',$amount=0,$price=0,$type="LIMIT",$opts=array()) {
			$pair = strtolower( $pair );
			$pair = str_replace( "-", "_", $pair );
			return $this->exch->sell($pair,$price,$amount);
		}

		public function get_open_orders( $pair = 'All' ) {
			return $this->exch->returnOpenOrders( $pair );
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
		
		public function unconfirmed_btc(){
			return [];
		}
		
		public function bitcoin_deposit_address(){
			return [];
		}

		public function get_ticker($pair = "ALL") {
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
				$market_summary['pair'] = $key;
				$market_summary['exchange'] = "poloniex";
				$market_summary['last_price'] = $market_summary['last'];
				$market_summary['ask'] = $market_summary['lowestAsk'];
				$market_summary['bid'] = $market_summary['highestBid'];
				$market_summary['base_volume'] = $market_summary['baseVolume'];
				$market_summary['quote_volume'] = $market_summary['quoteVolume'];
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
				//BUY ORDER: buy the base, sell the quote: BASE-QUOTE => BTC-USD=$232.32
				//SELL ORDER: sell the base, buy the quote: BASE-QUOTE => BTC-USD=$232.32
				if( strpos( $market_summary['pair'], "XMR_" ) !== FALSE )
					$market_summary['minimum_order_size_base'] = '0.01000000';
				if( strpos( $market_summary['pair'], "USDT_" ) !== FALSE )
					$market_summary['minimum_order_size_base'] = '0.01000000';
				if( strpos( $market_summary['pair'], "BTC_" ) !== FALSE )
					$market_summary['minimum_order_size_base'] = '0.00050000';

				$market_summary['minimum_order_size_quote'] = bcmul( $market_summary['minimum_order_size_base'], $market_summary['mid'], 32 );
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

		public function get_total_btc_balance() {
			$balances = $this->get_balances();
			$prices = $this->get_ticker();
			
			$tot_btc = 0;
			
			foreach($balances as $coin => $amount){
				$pair = "BTC_".strtoupper($coin);
			
				// convert coin balances to btc value
				if($amount > 0){
					if($coin != "BTC"){
						if( isset( $prices[$pair] ) )
							$tot_btc += $amount * $prices[$pair]['last'];
					}else{
						$tot_btc += $amount;
					}
				}

				// process open orders as well
				if($coin != "BTC"){
					$open_orders = $this->get_open_orders($pair);
					if( is_array( $open_orders ) && ! isset( $open_orders['error'] ) ) {
						foreach($open_orders as $order){
							if($order['type'] == 'buy'){
								$tot_btc += $order['total'];
							}elseif($order['type'] == 'sell'){
								$tot_btc += $order['amount'] * $prices[$pair]['last'];
							}
						}
					}
				}
			}

			return $tot_btc;
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
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
			foreach( $tickers as $key => $market_summary ) {
				$market_summary['pair'] = $key;
				$market_summary['exchange'] = "bter";

				//TODO test these
				$market_summary['mid'] = $market_summary['avg'];
				unset( $market_summary['avg'] );
				$market_summary['bid'] = $market_summary['buy'];
				unset( $market_summary['buy'] );
				$market_summary['ask'] = $market_summary['sell'];
				unset( $market_summary['sell'] );
				$market_summary['last_price'] = $market_summary['last'];
				unset( $market_summary['last'] );

				//TODO find these
				$market_summary['volume'] = null;
				$market_summary['base_volume'] = null;
				$market_summary['vwap'] = null;

				//TODO translate and/or add these
				unset( $market_summary['result'] );
				unset( $market_summary['rate_change_percentage'] );

				//TODO won't these change?
				unset( $market_summary['vol_dice'] );
				unset( $market_summary['vol_nxt'] );

				//TODO generate these
				$market_summary['expiration'] = null;
				$market_summary['initial_margin'] = null;
				$market_summary['maximum_order_size'] = null;
				$market_summary['minimum_margin'] = null;
				$market_summary['minimum_order_size'] = null;
				$market_summary['price_precision'] = null;
				$market_summary['timestamp'] = null;
				$market_summary['vwap'] = null;

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

		/*public function make_buy_orders()
		{
			try {

				$json = file_get_contents('http://data.bter.com/api/1/marketinfo');
				$json = json_decode( $json );
				
				foreach( $json->pairs as $pairs ) {
					print_r( $pairs );
					foreach( $pairs as $key => $value ) {
						$pair = $key;
						$key = explode("_",$key);
						$buying = $key[0];
						$buyingWith = $key[1];
						$type = 'buy';
						$min_amount = $value->min_amount;

						if( $buyingWith === "btc" ) {
							$min_amount = "0.0001";
						} else if( $buyingWith === "ltc" ) {
							$min_amount = "0.1";
							continue;
						} else if( $buyingWith === "cny" ) {
							$min_amount = "0.5";
							continue;
						} else if( $buyingWith === "usd" ) {
							continue;
						} else if( $buyingWith === "nxt" ) {
							$min_amount = "10";
							continue;
						} else {
							echo "Unrecognized currency to buy with $buyingWith";
							continue;
						}

						$decimal_places = 32;//$value->decimal_places;
						$rate = '0.00000008';//bcmul(get_top_rate($pair, $type),"1.01",$decimal_places);
						$amount = '4000';//bcmul(bcdiv($min_amount,$rate,$decimal_places),"12",$decimal_places);

						//$amount = 

						$go = true;
						while( $go ) {
							$total = bcmul($amount,$rate,$decimal_places);
							echo "Buying $amount $buying with $buyingWith for $rate with total of $total.\n";
							//_____make buy order
							$response =	$this->query('1/private/placeorder', 
														array(
															'pair' => "$pair",
															'type' => "$type",
															'rate' => "$rate",
															'amount' => "$amount",
														)
													);
							print_r( $response );
							if( $response['code'] == 21 || $response['code'] == 19 || $response['code'] == 12 ){
								$go = false;
							}
							if( $response['code'] == 0 ) {
								var_dump(query('1/private/getorder', array('order_id' => $response['order_id'])));
								$go = false;
							}
							if( $response['code'] == 20 ) {
								echo "Increasing amount.\n";
								$amount = $amount * 10;
								$go = false;
							}
						}
						//sleep(2);

					}
				}

			} catch (Exception $e) {
				echo "Error:".$e->getMessage();
			}
		}

		public function make_sell_orders()
		{


			try {

				$funds = get_funds();
				$funds = $funds['available_funds'];

				$json = file_get_contents('http://data.bter.com/api/1/marketlist');
				$json = json_decode( $json );
				
				foreach( $json->data as $pair ) {
					$key = $pair->pair;
					$key = explode("_",$key);
					$selling = $key[0];
					$sellingWith = $key[1];
					$type = 'sell';
					if( isset( $funds[strtoupper($selling)] ) )
						$amount = $funds[strtoupper($selling)];
					else
						$amount = 0;

					$decimal_places = 32;//$value->decimal_places;
					$rate = 3 * $pair->rate;//bcmul(get_top_rate($pair, $type),"1.01",$decimal_places);

					$total = bcmul($amount,$rate,$decimal_places);
					if( $amount > 0 ) {
						echo "Selling $amount $selling with $sellingWith for $rate with total of $total.\n";
						
						$response =	$this->query('1/private/placeorder', 
													array(
														'pair' => $pair->pair,
														'type' => "$type",
														'rate' => "$rate",
														'amount' => "$amount",
													)
												);

					}
				}

			} catch (Exception $e) {
				echo "Error:".$e->getMessage();
			}
		}*/

	}
?>
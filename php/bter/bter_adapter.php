<?PHP

	class BterAdapter implements CryptoExchange {

		public function __construct($Exch) {
			$this->exch = $Exch;
		}

		public function cancel($orderid="1") {
			$this->exch->cancelorder($orderid);
		}
		
		public function cancel_all() {
			$json = $this->exch->orderlist();
			$results = array();
			foreach( $json['orders'] as $order ) {
				$order['detailedInfo'] = $this->exch->cancel($order['id']);
				array_push($results,$order);
			}
			return $results;
		}

		public function buy($pair='BTC_LTC',$amount="1",$price="0.01",$type="LIMIT",$opts=array()) {
		}
		
		public function sell($pair='BTC_LTC',$amount="0.01",$price="500",$type="LIMIT",$opts=array()) {
		}

		public function get_open_orders()
		{
			$json = $this->exch->orderlist();

			$orders = array();
			foreach( $json['orders'] as $order ) {
				$order['detailedInfo'] = $this->exch->getorder($order['id']);
				array_push($orders,$order);
			}
			return $orders;
		}

		public function get_markets() {
		
		}

		public function get_currencies() {
		
		}

		public function unconfirmed_btc(){
		
		}
		
		public function bitcoin_deposit_address(){

		}

		public function get_ticker($ticker="BTC-LTC") {

		}

		public function get_market_summary( $market = "BTC-LTC" ) {

		}

		public function get_market_summaries() {

		}

		public function make_buy_orders()
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
		}


	}
?>
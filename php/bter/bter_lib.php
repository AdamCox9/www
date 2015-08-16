<?PHP

	class bter {
		protected $api_key;
		protected $api_secret;
		protected $trading_url = "https://bter.com/api/";

		public function __construct($api_key, $api_secret) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
		}
	
	
		private function query($path, array $req = array()) {
			// API settings, add your Key and Secret at here
			$key = $this->api_key;
			$secret = $this->api_secret;
		 
			// generate a nonce to avoid problems with 32bits systems
			$mt = explode(' ', microtime());
			$req['nonce'] = $mt[1].substr($mt[0], 2, 6);
		 
			// generate the POST data string
			$post_data = http_build_query($req, '', '&');
			$sign = hash_hmac('sha512', $post_data, $secret);
		 
			// generate the extra headers
			$headers = array(
				'KEY: '.$key,
				'SIGN: '.$sign,
			);

			//!!! please set Content-Type to application/x-www-form-urlencoded if it's not the default value

			// curl handle (initialize if required)
			static $ch = null;
			if (is_null($ch)) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 
					'Mozilla/4.0 (compatible; Bter PHP bot; '.php_uname('a').'; PHP/'.phpversion().')'
					);
			}
			curl_setopt($ch, CURLOPT_URL, $this->trading_url.$path);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			// run the query
			$res = curl_exec($ch);

			if ($res === false) throw new Exception('Curl error: '.curl_error($ch));
			//echo $res;
			$dec = json_decode($res, true);
			if (!$dec) throw new Exception('Invalid data: '.$res);
			return $dec;
		}

		 public function get_top_rate($pair, $type='BUY') {
			$rate = 0;

			// our curl handle (initialize if required)
			static $ch = null;
			if (is_null($ch)) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 
					'Mozilla/4.0 (compatible; Bter PHP bot; '.php_uname('a').'; PHP/'.phpversion().')'
					);
			}
			curl_setopt($ch, CURLOPT_URL, 'https://bter.com/api/1/depth/'.$pair);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			// run the query
			$res = curl_exec($ch);
			if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
			//echo $res;
			$dec = json_decode($res, true);
			if (!$dec) throw new Exception('Invalid data: '.$res);
			
			if (strtoupper($type) == 'BUY') {
				$r =  $dec['bids'][0];
				$rate = $r[0];
			} else  {
				$r = end($dec['asks']);
				$rate = $r[0];
			}

			return $rate;
		}

		public function cancel_all_orders()
		{
			// example 4: get order status
			//var_dump($this->query('1/private/getorder', array('order_id' => 15088)));

			//example 5: list all open orders
			$json = $this->query('1/private/orderlist');

			//print_r( $json );
			//die ('test');

			// cancel an order
			foreach( $json['orders'] as $order ) {
				print_r( $order );
				echo "canceling {$order['id']}\n";
				var_dump($this->query('1/private/cancelorder', array('order_id' => $order['id'])));
			}
		}

		public function list_all_orders()
		{
			//example 5: list all open orders
			$json = $this->query('1/private/orderlist');

			print_r( $json );
			die ('test');

			// cancel an order
			foreach( $json['orders'] as $order ) {
				print_r( $order );
				echo "listing order {$order['id']}\n";
				var_dump($this->query('1/private/getorder', array('order_id' => $order['id'])));
			}
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

		public function get_funds()
		{
			return $this->query('1/private/getfunds');
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
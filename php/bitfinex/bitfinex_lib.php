<?php
	
	//implements https://www.bitfinex.com/pages/api

	class bitfinex {
		protected $api_key;
		protected $api_secret;
		protected $trading_url = "https://api.bitfinex.com/v1";
		
		public function __construct($api_key, $api_secret) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
		}
			
		private function query($path, array $req = array()) {
			// API settings
			$key = $this->api_key;
			$secret = $this->api_secret;
			$mt = explode(' ', microtime());
			$req['nonce'] = $mt[1].substr($mt[0], 2, 6);
			$req['request'] = "/v1".$path;		 
			// generate the POST data string
			$post_data = base64_encode(json_encode($req));
			$sign = hash_hmac('sha384', $post_data, $secret);
		 
			// generate the extra headers
			$headers = array(
				'X-BFX-APIKEY: '.$key,
				'X-BFX-PAYLOAD: '.$post_data,
				'X-BFX-SIGNATURE: '.$sign,
			);

			// curl handle (initialize if required)
			static $ch = null;
			if (is_null($ch)) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 
					'Mozilla/4.0 (compatible; Bitfinex PHP bot; '.php_uname('a').'; PHP/'.phpversion().')'
				);
			}
			curl_setopt($ch, CURLOPT_URL, $this->trading_url . $path);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			// run the query
			$res = curl_exec($ch);

			if ($res === false) 
				throw new Exception('Curl error: '.curl_error($ch));
			$dec = json_decode($res, true);
			if (!$dec)
				throw new Exception('Invalid data: '.$res);
			return $dec;
		}
		
		protected function retrieveJSON($URL) {
			$opts = array('http' =>
				array(
					'method'  => 'GET',
					'timeout' => 10 
				)
			);
			$context = stream_context_create($opts);
			$feed = file_get_contents($URL, false, $context);
			$json = json_decode($feed, true);
			return $json;
		}

		//Unauthenticated Calls

		public function get_pubticker($symbol="btcusd") {
			return file_get_contents( $this->trading_url . "/pubticker/" . $symbol );
		}
		public function get_stats($symbol="btcusd") {
			return file_get_contents( $this->trading_url . "/stats/" . $symbol );
		}
		public function get_lendbook($currency="btc") {
			return file_get_contents( $this->trading_url . "/lendbook/" . $currency );
		}
		public function get_book($symbol="btcusd") {
			return file_get_contents( $this->trading_url . "/book/" . $symbol );
		}
		public function get_trades($symbol="btcusd") {
			return file_get_contents( $this->trading_url . "/trades/" . $symbol );
		}
		public function get_lends($currency="btc") {
			return file_get_contents( $this->trading_url . "/lends/" . $currency );
		}
		public function get_symbols() {
			return file_get_contents( $this->trading_url . "/symbols" );
		}
		public function get_symbols_details() {
			return file_get_contents( $this->trading_url . "/symbols_details" );
		}

		//Authenticated Calls

		public function deposit_new($method="bitcoin",$wallet_name="exchange",$renew=0) {
			return $this->query( "/deposit/new", array( "method" => $method, "wallet_name" => $wallet_name, "renew" => $renew ) );
		}
		public function order_new($symbol="ltcbtc",$amount="0.1",$price="0.01",$exchange="bitfinex",$side="buy",$type="limit",$is_hidden=true) {
			return $this->query( "/order/new", array( "symbol" => $symbol, "amount" => $amount, "price" => $price, "exchange" => $exchange, "side" => $side, "type" => $type, "is_hidden" => $is_hidden ) );
		}
		public function order_new_multi( $orders = array( array( 'symbol'=>"ltcbtc",'amount'=>"0.1",'price'=>"0.01",'exchange'=>"bitfinex",'side'=>"buy",'type'=>"limit",'is_hidden'=>true) ) ) {
			return $this->query( "/order/new/multi", array( 'orders' => $orders ) );
		}



	}
?>
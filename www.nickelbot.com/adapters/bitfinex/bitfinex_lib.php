<?php
	
	//implements https://www.bitfinex.com/pages/api

	class bitfinex {
		protected $api_key;
		protected $api_secret;
		protected $trading_url = "https://api.bitfinex.com/v1";
		
		public function __construct( $api_key, $api_secret ) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
		}
			
		private function query( $path, array $req = array() ) {
			// API settings
			$key = $this->api_key;
			$secret = $this->api_secret;
			$mt = explode( ' ', microtime() );
			$req['nonce'] = $mt[1] . substr( $mt[0], 2, 6 );
			$req['request'] = "/v1" . $path;		 
			// generate the POST data string
			$post_data = base64_encode( json_encode( $req ) );
			$sign = hash_hmac( 'sha384', $post_data, $secret );
		 
			// generate the extra headers
			$headers = array(
				'X-BFX-APIKEY: ' . $key,
				'X-BFX-PAYLOAD: ' . $post_data,
				'X-BFX-SIGNATURE: ' . $sign,
			);

			// curl handle (initialize if required)
			static $ch = null;
			if( is_null( $ch ) ) {
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_USERAGENT, 
					'Mozilla/4.0 (compatible; Bitfinex PHP bot; ' . php_uname( 'a' ) . '; PHP/' . phpversion() . ')'
				);
			}
			curl_setopt( $ch, CURLOPT_URL, $this->trading_url . $path );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

			// run the query
			$res = curl_exec( $ch );

			if( $res === false ) 
				throw new Exception( 'Curl error: ' . curl_error( $ch ) );
			$dec = json_decode( $res, true );

			return $dec;
		}

		//Public Functions:

		public function pubticker( $symbol = "btcusd" ) {
			return json_decode( file_get_contents( $this->trading_url . "/pubticker/" . $symbol ), true );
		}
		
		public function stats( $symbol = "btcusd" ) {
			return json_decode( file_get_contents( $this->trading_url . "/stats/" . $symbol ), true );
		}
		
		public function lendbook( $currency = "btc" ) {
			return json_decode( file_get_contents( $this->trading_url . "/lendbook/" . $currency ), true );
		}
		
		public function book( $symbol = "btcusd" ) {
			return json_decode( file_get_contents( $this->trading_url . "/book/" . $symbol ), true );
		}
		
		public function trades( $symbol = "btcusd" ) {
			return json_decode( file_get_contents( $this->trading_url . "/trades/" . $symbol ), true );
		}
		
		public function lends( $currency = "btc" ) {
			return json_decode( file_get_contents( $this->trading_url . "/lends/" . $currency ), true );
		}
		
		public function symbols() {
			return json_decode( file_get_contents( $this->trading_url . "/symbols" ), true );
		}
		
		public function symbols_details() {
			return json_decode( file_get_contents( $this->trading_url . "/symbols_details" ), true );
		}

		//Authenticated Functions:

		public function deposit_new( $method = "bitcoin", $wallet_name = "exchange", $renew = 0 ) {
			return $this->query( "/deposit/new", array( "method" => $method, "wallet_name" => $wallet_name, "renew" => $renew ) );
		}

		public function order_new( $symbol = "ltcbtc", $amount = "0.1", $price = "0.01", $exchange = "bitfinex", $side = "buy", $type = "limit", $is_hidden = true ) {
			return $this->query( "/order/new", array( "symbol" => $symbol, "amount" => $amount, "price" => $price, "exchange" => $exchange, "side" => $side, "type" => $type, "is_hidden" => $is_hidden ) );
		}
		
		public function order_new_multi( $orders = array( array( 'symbol' => "ltcbtc", 'amount' => "0.1", 'price' => "0.01", 'exchange' => "bitfinex", 'side' => "buy", 'type' => "limit", 'is_hidden' => true ) ) ) {
			return $this->query( "/order/new/multi", array( 'orders' => $orders ) );
		}

		public function order_cancel( $order_id ) {
			return $this->query( "/order/cancel", array( 'order_id' => $order_id ) );
		}

		public function order_cancel_multi() {
			return array('error'=>'NOT_IMPLEMENTED');
		}

		public function order_cancel_all() {
			return $this->query( "/order/cancel/all" );
		}

		public function order_cancel_replace() {
			return array( 'error'=>'NOT_IMPLEMENTED' );
		}

		public function order_status( $order_id ) {
			return $this->query( "/order/cancel", array( 'order_id' => $order_id ) );
		}

		public function orders() {
			return $this->query( "/orders" );
		}

		public function positions() {
			return $this->query( "/positions" );
		}

		public function position_claim() {
			return array( 'error' => 'NOT_IMPLEMENTED' );
		}

		public function history() {
			return $this->query( "/history" );
		}

		public function history_movements() {
			return $this->query( "/history/movements" );
		}

		public function mytrades( $arr = array() ) {
			return $this->query( "/mytrades", $arr );
		}

		public function offer_new() {
			return array( 'error' => 'NOT_IMPLEMENTED' );
		}

		public function cancel_offer() {
			return array( 'error' => 'NOT_IMPLEMENTED' );
		}

		public function offer_status() {
			return array( 'error' => 'NOT_IMPLEMENTED' );
		}

		public function offers() {
			return array( 'error' => 'NOT_IMPLEMENTED' );
		}

		public function credits() {
			return array( 'error' => 'NOT_IMPLEMENTED' );
		}

		public function taken_swaps() {
			return array( 'error' => 'NOT_IMPLEMENTED' );
		}

		public function total_taken_swaps() {
			return array( 'error' => 'NOT_IMPLEMENTED' );
		}

		public function close_swap() {
			return array( 'error' => 'NOT_IMPLEMENTED' );
		}

		public function balances() {
			return $this->query( "/balances" );
		}

		public function account_infos() {
			return $this->query( "/account_infos" );
		}

		public function margin_infos() {
			return $this->query( "/margin_infos" );
		}

	}
?>
<?php

	//implements https://docs.exchange.coinbase.com/#introduction

	class coinbase {
		protected $api_key;
		protected $api_secret;
		protected $nonce;
		protected $trading_url = "https://api.coinbase.com/v2/";
		
		public function __construct($api_key, $api_secret,$passphrase) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
			$this->passphrase = $passphrase;
			$this->nonce = time();
		}
			
		public function query($path="/accounts") {
			$key = $this->api_key;
			$secret = $this->api_secret;
			$passphrase = $this->passphrase;

			$time = time();
			$url = "https://api.exchange.coinbase.com" . $path;

			$data = $time."GET".$path;

			$sign = base64_encode(hash_hmac("sha256", $data, base64_decode($secret), true));                

			$headers = array(                
				'CB-ACCESS-KEY: '.$key,
				'CB-ACCESS-SIGN: '.$sign,
				'CB-ACCESS-TIMESTAMP: '.$time,
				'CB-ACCESS-PASSPHRASE: '.$passphrase,
				'Content-Type: application/json'
			);

			static $ch = null;

			if (is_null($ch)) {
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
			}
			curl_setopt($ch, CURLOPT_URL, $url);
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

	}
?>
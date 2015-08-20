<?PHP

	//immplements https://bittrex.com/Home/Api

	class bittrex {
		protected $api_key;
		protected $api_secret;
		protected $nonce;
		protected $trading_url = "https://bittrex.com/api/v1.1";

		public function __construct($api_key, $api_secret) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
			$this->nonce = time();
		}
	
		private function query($path, array $req = array()) {
			$key = $this->api_key;
			$secret = $this->api_secret;

			$req['apikey'] = $key;
			$req['nonce'] = $this->nonce++;
			
			$queryString = http_build_query($req, '', '&');
			$requestUrl = $this->trading_url . $path . '?' . $queryString;	
			$sign = hash_hmac('sha512', $requestUrl, $secret);
			
			static $ch = null;
			
			if (is_null($ch)) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Bittrex PHP bot; '.php_uname('a').'; PHP/'.phpversion().')');
			}
			curl_setopt($ch, CURLOPT_HTTPGET, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'.$sign));
			curl_setopt($ch, CURLOPT_URL, $requestUrl);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
			
			// run the query
			$res = curl_exec($ch);
			
			if ($res === false)
				throw new Exception('Could not get reply: ' . curl_error($ch));
			
			$dec = json_decode($res, true);
			if (!$dec)
				throw new Exception('Invalid data: '.$res);
			
			return $dec;
		}
		public function get_markets() {
			return $this->query("/public/getmarkets");
		}
		public function get_currencies() {
			return $this->query("/public/getcurrencies");
		}
		public function get_ticker( $arr=array("market"=>"BTC-LTC") ) {
			return $this->query("/public/getticker",$arr);
		}
		public function get_market_summaries() {
			return $this->query("/public/getmarketsummaries");
		}
		public function get_market_summary( $arr=array("market"=>"BTC-LTC") ) {
			return $this->query("/public/getmarketsummary",$arr);
		}
		public function get_open_orders() {
			return $this->query("/market/getopenorders");
		}
		public function cancel_order($arr = array("uuid" => '123' )) {
			return $this->query("/market/cancel", $arr);
		}
	}
?>
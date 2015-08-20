<?PHP

	//immplements https://btc-e.com/api/documentation

	class btce {
		protected $api_key;
		protected $api_secret;
		protected $trading_url = "https://btc-e.com/tapi/";
		protected $x;
		
		public function __construct($api_key, $api_secret) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
			$this->x = 0;
		}

		private function query($method, array $req = array()) {
			// API settings
			$key = $this->api_key;
			$secret = $this->api_secret;

			$req['method'] = $method;
			$mt = explode(' ', microtime());
			$req['nonce'] = $mt[1] + $this->x++;
		   
			// generate the POST data string
			$post_data = http_build_query($req, '', '&');

			$sign = hash_hmac('sha512', $post_data, $secret);

			// generate the extra headers
			$headers = array(
				'Sign: '.$sign,
				'Key: '.$key,
			);

			// our curl handle (initialize if required)
			static $ch = null;
			if (is_null($ch)) {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_USERAGENT, 
						'Mozilla/4.0 (compatible; BTCE PHP client; '.php_uname('s').'; PHP/'.phpversion().')'
					);
			}
			curl_setopt($ch, CURLOPT_URL, $this->trading_url);
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

		public function list_info() {
			return $this->query('getInfo');
		}

		public function Trade() {
			return $this->query('Trade', array('pair' => 'btc_usd', 'type' => 'buy', 'amount' => 1, 'rate' => 10)); //buy 1 BTC @ 10 USD
		}
		
		public function TransHistory() {
			return $this->query('TransHistory');
		}

		public function TradeHistory() {
			return $this->query('TradeHistory');
		}

	}

?>
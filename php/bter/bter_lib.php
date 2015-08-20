<?PHP

	//implements https://bter.com/api

	class bter {
		protected $api_key;
		protected $api_secret;
		protected $trading_url = "https://bter.com/api/";
		protected $nonce;

		public function __construct($api_key, $api_secret) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
			$this->nonce = time();
		}
	
	
		private function query($path, array $req = array()) {
			$key = $this->api_key;
			$secret = $this->api_secret;
		 
			$req['nonce'] = $this->nonce++;
		 
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
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Bter PHP bot; '.php_uname('a').'; PHP/'.phpversion().')');
			}
			curl_setopt($ch, CURLOPT_URL, $this->trading_url.$path);
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

		public function orderlist()
		{
			return $this->query('1/private/orderlist');
		}

		public function getorder($orderid)
		{
			return $this->query('1/private/getorder', array('order_id' => $orderid));
		}

		public function cancelorder($orderid)
		{
			return $this->query('1/private/cancelorder', array('order_id' => $orderid));
		}

		public function get_funds()
		{
			return $this->query('1/private/getfunds');
		}


	}
?>
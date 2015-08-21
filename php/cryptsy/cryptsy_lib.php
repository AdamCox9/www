<?PHP

	//implements https://www.cryptsy.com/pages/apiv2

	class cryptsy {
		protected $api_key;
		protected $api_secret;
		protected $trading_url = "https://bter.com/api/";

		public function __construct($api_key, $api_secret) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
		}
	
		public function query($method, array $req = array()) {
			// API settings
			$key = $this->api_key; // your API-key
			$secret = $this->api_secret; // your Secret-key
	 
			$req['method'] = $method;
			$mt = explode(' ', microtime());
			$req['nonce'] = $mt[1];
		   
			// generate the POST data string
			$post_data = http_build_query($req, '', '&');

			$sign = hash_hmac("sha512", $post_data, $secret);
	 
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
					curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Cryptsy API PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
			}
			curl_setopt($ch, CURLOPT_URL, 'https://api.cryptsy.com/api');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	 
			// run the query
			$res = curl_exec($ch);

			if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
			$dec = json_decode($res, true);
			if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists');
			return $dec;
		}
	 
		public function getmarkets() {
			return $this->query("getmarkets");
		}

		public function mytransactions() {
			return $this->query("mytransactions");
		}

		public function markettrades() {
			return $this->query("markettrades", array("marketid" => 26));
		}

		public function marketorders() {
			return $this->query("marketorders", array("marketid" => 26));
		}

		public function mytrades() {
			return $this->query("mytrades", array("marketid" => 26, "limit" => 1000));
		}

		public function allmytrades() {
			return $this->query("allmytrades");
		}

		public function myorders() {
			return $this->query("myorders", array("marketid" => 26));
		}

		public function allmyorders() {
			return $this->query("allmyorders");
		}

		public function cancelallorders() {
			return $this->query("cancelallorders");
		}

		public function createorder() {
			return $this->query("createorder", array("marketid" => 26, "ordertype" => "Sell", "quantity" => 1000, "price" => 0.00031000));
		}

		public function cancelorder() {
			return $this->query("cancelorder", array("orderid" => 139567));
		}

		public function calculatefees() {
			return $this->query("calculatefees", array("ordertype" => 'Buy', 'quantity' => 1000, 'price' => '0.005'));
		}

	}
?>
<?PHP

	function bter_query($path, array $req = array()) {
		// API settings, add your Key and Secret at here
		$key = 'EAD04357-77CE-4A75-9B7D-CFAD0B481D09';
		$secret = '1dd9b32a6a0a92ab70bc2944ad567db9b133593eee8c597d80e9d4392235f11b';
	 
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
		curl_setopt($ch, CURLOPT_URL, 'https://bter.com/api/'.$path);
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

	 function get_top_rate($pair, $type='BUY') {
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

	function cancel_all_orders()
	{
		// example 4: get order status
		//var_dump(bter_query('1/private/getorder', array('order_id' => 15088)));

		//example 5: list all open orders
		$json = bter_query('1/private/orderlist');

		//print_r( $json );
		//die ('test');

		// cancel an order
		foreach( $json['orders'] as $order ) {
			print_r( $order );
			echo "canceling {$order['id']}\n";
			var_dump(bter_query('1/private/cancelorder', array('order_id' => $order['id'])));
		}
	}

	function list_all_orders()
	{
		//example 5: list all open orders
		$json = bter_query('1/private/orderlist');

		print_r( $json );
		die ('test');

		// cancel an order
		foreach( $json['orders'] as $order ) {
			print_r( $order );
			echo "listing order {$order['id']}\n";
			var_dump(bter_query('1/private/getorder', array('order_id' => $order['id'])));
		}
	}

?>
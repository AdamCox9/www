<?php

	//implements https://poloniex.com/support/api

	class poloniex {
		protected $api_key;
		protected $api_secret;
		protected $trading_url = "https://poloniex.com/tradingApi";
		protected $public_url = "https://poloniex.com/public";
		
		public function __construct($api_key, $api_secret) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
			$this->nonce = 9872591439987259 + time();
		}
			
		private function query(array $req = array()) {
			// API settings
			$key = $this->api_key;
			$secret = $this->api_secret;
			$req['nonce'] = $this->nonce++;
		 
			// generate the POST data string
			$post_data = http_build_query($req, '', '&');
			$sign = hash_hmac('sha512', $post_data, $secret);
		 
			// generate the extra headers
			$headers = array(
				'Key: '.$key,
				'Sign: '.$sign,
			);

			// curl handle (initialize if required)
			static $ch = null;
			if (is_null($ch)) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 
					'Mozilla/4.0 (compatible; Poloniex PHP bot; '.php_uname('a').'; PHP/'.phpversion().')'
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
			//if (!$dec)
				//throw new Exception('Invalid data: '.$res);
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
		
		public function get_balances() {
			if( ! isset( $this->balances ) )
				$this->balances = $this->query( 
					array(
						'command' => 'returnBalances'
					)
				);
			return $this->balances;
		}
		
		public function get_open_orders($pair) {		
			return $this->query( 
				array(
					'command' => 'returnOpenOrders',
					'currencyPair' => strtoupper($pair)
				)
			);
		}
		
		public function get_my_trade_history($pair) {
			return $this->query(
				array(
					'command' => 'returnTradeHistory',
					'currencyPair' => strtoupper($pair)
				)
			);
		}
		
		public function buy($pair, $rate, $amount) {
			return $this->query( 
				array(
					'command' => 'buy',	
					'currencyPair' => strtoupper($pair),
					'rate' => $rate,
					'amount' => $amount
				)
			);
		}
		
		public function sell($pair, $rate, $amount) {
			return $this->query( 
				array(
					'command' => 'sell',	
					'currencyPair' => strtoupper($pair),
					'rate' => $rate,
					'amount' => $amount
				)
			);
		}
		
		public function cancel_order($pair, $order_number) {
			return $this->query( 
				array(
					'command' => 'cancelOrder',	
					'currencyPair' => strtoupper($pair),
					'orderNumber' => $order_number
				)
			);
		}
		
		public function withdraw($currency, $amount, $address) {
			return $this->query( 
				array(
					'command' => 'withdraw',	
					'currency' => strtoupper($currency),				
					'amount' => $amount,
					'address' => $address
				)
			);
		}
		
		public function get_trade_history($pair) {
			$trades = $this->retrieveJSON($this->public_url.'?command=returnTradeHistory&currencyPair='.strtoupper($pair));
			return $trades;
		}
		
		public function get_order_book($pair) {
			$orders = $this->retrieveJSON($this->public_url.'?command=returnOrderBook&currencyPair='.strtoupper($pair));
			return $orders;
		}
		
		public function get_volume() {
			$volume = $this->retrieveJSON($this->public_url.'?command=return24hVolume');
			return $volume;
		}
	
		public function get_ticker($pair = "ALL") {
			$pair = strtoupper($pair);
			$prices = $this->retrieveJSON($this->public_url.'?command=returnTicker');
			if($pair == "ALL"){
				return $prices;
			}else{
				$pair = strtoupper($pair);
				if(isset($prices[$pair])){
					return $prices[$pair];
				}else{
					return array();
				}
			}
		}
		
		public function get_trading_pairs() {
			$tickers = $this->retrieveJSON($this->public_url.'?command=returnTicker');
			return array_keys($tickers);
		}
		
		public function get_total_btc_balance() {
			$balances = $this->get_balances();
			$prices = $this->get_ticker();
			
			$tot_btc = 0;
			
			foreach($balances as $coin => $amount){
				$pair = "BTC_".strtoupper($coin);
			
				// convert coin balances to btc value
				if($amount > 0){
					if($coin != "BTC"){
						if( isset( $prices[$pair] ) )
							$tot_btc += $amount * $prices[$pair]['last'];
					}else{
						$tot_btc += $amount;
					}
				}

				// process open orders as well
				if($coin != "BTC"){
					$open_orders = $this->get_open_orders($pair);
					if( is_array( $open_orders ) && ! isset( $open_orders['error'] ) ) {
						foreach($open_orders as $order){
							if($order['type'] == 'buy'){
								$tot_btc += $order['total'];
							}elseif($order['type'] == 'sell'){
								$tot_btc += $order['amount'] * $prices[$pair]['last'];
							}
						}
					}
				}
			}

			return $tot_btc;
		}

		//_____List Detailed Info:
		function list_detailed_info()
		{
			$total_volume = 0;

			$tickers = $this->get_ticker();
			$balances = $this->get_balances();

			foreach( $tickers as $pair => $ticker ) {
				$open_orders = $this->get_open_orders($pair);
				print_r( $open_orders );

				$my_trade_history = $this->get_my_trade_history($pair);
				print_r( $my_trade_history );

				$last = $ticker['last'];
				$lowestAsk = $ticker['lowestAsk'];
				$highestBid = $ticker['highestBid'];
				$percentChange = $ticker['percentChange'];
				$baseVolume = $ticker['baseVolume'];
				$quoteVolume = $ticker['quoteVolume'];
				$isFrozen = $ticker['isFrozen'];
				$high24hr = $ticker['high24hr'];
				$low24hr = $ticker['low24hr'];

				$total_volume += $baseVolume;

				$currency = explode("_", $pair);
				$currency0 = $currency[0];
				$currency1 = $currency[1];
				$balance = $balances[$currency1];

				echo "last $last\n";
				echo "lowestAsk $lowestAsk\n";
				echo "highestBid $highestBid\n";
				echo "percentChange $percentChange\n";
				echo "baseVolume $baseVolume\n";
				echo "quoteVolume $quoteVolume\n";
				echo "isFrozen $isFrozen\n";
				echo "high24hr $high24hr\n";
				echo "low24hr $low24hr\n";
				echo "pair $pair\n";
				echo "currency0 $currency0\n";
				echo "currency1 $currency1\n";
				echo "balance $balance\n";
				echo "******************\n";

			}

			echo "******************\n";
			echo "Poloniex Total BTC Volume $total_volume\n";
			echo "\n\n";

		}

		//_____Cancel all orders:
		function cancel_all_orders()
		{
			$get_ticker = $this->get_ticker();

			foreach( $get_ticker as $key => $ticker ) {
				$get_open_orders = $this->get_open_orders($key);
				if( is_array( $get_open_orders ) ) {
					foreach( $get_open_orders as $open_order ) {
						$orderNumber = $open_order['orderNumber'];
						if( ! is_null( $orderNumber ) ) {
							echo "Cancelling order number $orderNumber with pair $key\n";
							$this->cancel_order($key, $orderNumber);
						}
					}
				}
			}
		}

		//_____Make decreasing buy orders from highest bid placed:
		function make_buy_orders()
		{
			$get_ticker = $this->get_ticker();
			global $total_volume;

			$btc_cost = 0;
			foreach( $get_ticker as $key => $ticker ) {
				$highestBid = $ticker['highestBid'];
				$baseVolume = $ticker['baseVolume'];
				$percentChange = $ticker['percentChange'];
				$percentVolume = $baseVolume / $total_volume;

				echo "\n\n$key has $percentVolume% $baseVolume/$total_volume\n";

				$x = 100;
				while( $x > 80 ) {
					if( $highestBid > 0 ) {
						$rate = bcmul($highestBid,$x/100,32);
						$amount = bcdiv((100-$x)*0.0001,$rate,32);
						if( $percentVolume > 0.01 ) {
							$amount = bcmul($amount, $percentVolume, 32);
							$amount = bcmul($amount, 150, 32);
						}
						echo "\n\n$percentChange**************\n\n";
						if( $percentChange < -0.20 ) {
							$amount = $amount * 10 * $percentChange * -1;
						}
						$total = $rate * $amount;
						if( $total > 0.0001 ) {
							echo "buying $key for $rate with a total of $amount\n";
							$btc_cost = $btc_cost + $total;
							print_r( $Poloniex->buy($key,$rate,$amount) );
						}
					}
					$x = $x - 3;
				}
				$rate = '0.00000001'; //maybe set to 1% or 5%?
				$amount = '50000';
				$total = bcmul( $rate, $amount, 32 );
				$btc_cost = $btc_cost + $total;
				echo "\nmin buy $key for $rate with an amount of $amount and total $total\n";
				print_r( $this->buy($key,$rate,$amount) );

			}
			echo "\n\nThe total cost is $btc_cost bitcoins.\n\n";
		}

		//_____Make increasing sell orders from lowest ask placed:
		public function make_sell_orders()
		{
			$get_ticker = $this->get_ticker();
			$get_balances = $this->get_balances();

			$btc_gain = 0;
			$xmr_gain = 0;
			$usdt_gain = 0;
			foreach( $get_ticker as $key => $ticker ) {
				$lowestAsk = $ticker['lowestAsk'];
				$currency = explode("_", $key);
				$balance = $get_balances[$currency[1]];
				$percentChange = $ticker['percentChange'];

				echo "Total Balance: $balance\n";

				$potential_sale = 0;
				$x = 1;
				while( $x < 50 ) {
					if( $lowestAsk > 0 ) {
						$rate = bcmul($lowestAsk, 1+$x/100, 32);
						$amount = bcmul($balance,$x/100,32);
						$potential_sale = $potential_sale + $amount;
						$total = bcmul( $rate, $amount, 32 );
						if( $percentChange > 0.1 ) {
							$amount = $amount * 10 * $percentChange;
						}

						if( $amount > 0 && $rate > 0 && $total > 0.0001 ) {
							if( $currency[0] == 'BTC' )
								$btc_gain = $btc_gain + $total;
							if( $currency[0] == 'XMR' ) {
								$xmr_gain = $xmr_gain + $total;
								$x = $x + 2;
								continue;
							}
							if( $currency[0] == 'USDT' ) {
								$usdt_gain = $usdt_gain + $total;
								$x = $x + 2;
								continue;
							}
							echo "selling $key for $rate with an amount of $amount and total $total \n";
							print_r( $this->sell($key,$rate,$amount) );
						}
					}
					$x = $x + 4;
				}
				echo "Potential Sale: $potential_sale\n";

			}
			echo "\n\nThe potential gain is $btc_gain bitcoin $xmr_gain monero $usdt_gain usdt.\n\n";
		}
	}

?>
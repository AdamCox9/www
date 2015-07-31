<?php

	require_once( "../php/bter/bter_lib.php" );

	try {

		//list_all_orders();
		//die('test');

		//cancel_all_orders();


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
					$response =	bter_query('1/private/placeorder', 
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
						var_dump(bter_query('1/private/getorder', array('order_id' => $response['order_id'])));
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

		//_____list all open orders
		var_dump(bter_query('1/private/orderlist'));


	} catch (Exception $e) {
		echo "Error:".$e->getMessage();
	}

?> 
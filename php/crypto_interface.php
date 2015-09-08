<?php
	interface CryptoExchange
	{
		//Cancel one or many orders: TODO accept array of orderid's
		public function cancel( $orderid="1", $opts = array() );

		//Cancel all orders:
		public function cancel_all();

		//Make a buy order - TODO accept an array of orders
		//$type: limit, market, stop, margin, trigger, etc...
		//merge with sell to make order(...)
		public function buy( $pair='BTC-LTC', $amount="0.01", $price="0.01", $type="LIMIT", $opts=array() );

		//Make a sell order - TODO accept an array of orders
		//$type: limit, market, stop, margin, trigger, etc...
		//merge with buy to make order(...)
		public function sell( $pair='BTC-LTC', $amount="0.01", $price="0.01", $type="LIMIT", $opts=array() );
		
		//An array of every pair BTC-LTC, BTC-USD, etc...
		public function get_markets();

		//An array of every currency BTC, LTC, USD, etc...
		public function get_currencies();

		//Get all open orders: merge these 3?
		public function get_open_orders();
		public function get_completed_orders();
		//public function get_order( $order_id );

		//Get a deposit address for a currency: merge?
		public function deposit_address( $currency="BTC" );
		public function deposit_addresses();

		//Get specific market summary: merge and accept an array of markets to get
		public function get_market_summary( $market="BTC-LTC" );
		public function get_market_summaries();

		//Balances, confirmed, reserved, available & total... for each currency...
		//merge and accept an array of currencies
		public function get_balance( $currency="BTC" );
		public function get_balances();

		//Stuff for margin trading...
		//Stay away for now!!!
		public function get_lendbook();
		public function get_book();
		public function get_lends();

		//Should return worth on exchange in USD/BTC/XML/LTC/ETC...
		public function get_worth();

/*
		//This could have exchange info such as name, url, location, total volume, etc???
		public function get_info();

		//This will return a link to the graph page on the exchange
		public function get_market_url( $market = "BTC-LTC" );

		//This will return a link to currency info page if available
		public function get_currency_url( $market = "LTC" );

		//Get trades for a specific $market since $time
		public function get_trades( $market = "BTC-USD", $time = 0 );

		//Withdraw funds to $address
		public function withdraw( $account = "exchange", $currency = "BTC", $address = "1fsdaa...dsadf", $amount = 1 );

*/

	}
?>
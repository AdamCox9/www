<?php
	interface CryptoExchange
	{
		//Cancel one order:
		public function cancel($orderid="1", $opts = array() );

		//Cancel all orders:
		public function cancel_all();

		//Make a buy order:
		public function buy($pair='BTC-LTC',$amount="0.01",$price="0.01",$type="LIMIT",$opts=array());

		//Make a sell order
		public function sell($pair='BTC-LTC',$amount="0.01",$price="0.01",$type="LIMIT",$opts=array());
		
		//An array of every pair BTC-LTC, BTC-USD, etc...
		public function get_markets();

		//An array of every currency BTC, LTC, USD, etc...
		public function get_currencies();

		//Get all open orders:
		public function get_open_orders( $pair = 'All' );

		//Get a deposit address: (todo, accept $currency as parameter):
		public function bitcoin_deposit_address();

		//Return unconfirmed BTC balance:
		public function unconfirmed_btc();

		//This will convert BTC-USD, BTC-LTC to correct format btcusd, btcltc, etc...
		public function get_ticker($ticker="BTC-LTC");

		//Get specific market summary:
		public function get_market_summary( $market="BTC-LTC" );

		//Return array of each market summary:
		public function get_market_summaries();
		
		public function get_lendbook();
		public function get_book();
		public function get_lends();
		/*

		//This will return a link to the graph page on the exchange
		public function get_market_url($market="BTC-LTC");
		public function get_currency_url($market="BTC-LTC");
		public function get_trades($market="BTC-LTC");
		public function get_order($order_id);
		public function get_balances();
		public function get_balance($currency="BTC");
		public function unit_test(); //This will verify each function in native api works as it should!!!

		*/
	}
?>
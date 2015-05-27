
/**
 **/

var key = '96f5aece4b51a56d71520a6f8f55f5d3';
var b64secret = 'RU2oFVSOVXJRUEtLSWt7cUoN1WMY2Q8COidj8e65yr+1G5YmDev3ezRqkGEjLt+96ypxrRpHmZ8QmKhqi33Vpw==';
passphrase = 'dd6bf0643ebe8959960a246f8085695a74d064d856e6b03791d65d9fd878a95c';

var CoinbaseExchange = require( 'coinbase-exchange' );
var publicClient = new CoinbaseExchange.PublicClient();
var authedClient = new CoinbaseExchange.AuthenticatedClient( key, b64secret, passphrase );


var bid = '',
	ask = '',
	spread = '';

var callback = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n\n" );
};

var BuyLowSellHighCB = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n\n" );
	
	if( data ) {

		bid = data['bids'][0][0];
		ask = data['asks'][0][0];
		spread = ask - bid;
	
		//console.log( "\n\n******************************" );
		//console.log( "Spread: " + spread );
		//console.log( "Bid: " + bid );
		//console.log( "Ask: " + ask );
		//console.log( "***\n" );

		if( spread < 0.02 ) {
			bid = parseFloat(bid) - 0.05;
			ask = parseFloat(ask) + 0.05;

			var buyParams = { 'price': bid, 'size': '0.01', 'product_id': 'BTC-USD', };
			authedClient.buy(buyParams, callback);
			 
			var sellParams = { 'price': ask, 'size': '0.01', 'product_id': 'BTC-USD', };
			authedClient.sell(sellParams, callback);
		} else {

			var buyParams = { 'price': bid, 'size': '0.01', 'product_id': 'BTC-USD', };
			authedClient.buy(buyParams, callback);
			 
			var sellParams = { 'price': ask, 'size': '0.01', 'product_id': 'BTC-USD', };
			authedClient.sell(sellParams, callback);
		}

		if( spread > 0.1 ) {
			bid = parseFloat(bid) + 0.01;
			ask = parseFloat(ask) - 0.01;

			var buyParams = { 'price': bid, 'size': '0.02', 'product_id': 'BTC-USD', };
			authedClient.buy(buyParams, callback);
			 
			var sellParams = { 'price': ask, 'size': '0.02', 'product_id': 'BTC-USD', };
			authedClient.sell(sellParams, callback);

			//console.log( "\n***" );
			//console.log( "placed bid for " + (bid + 0.01) );
			//console.log( "placed ask for " + (ask - 0.01) );
			//console.log( "***\n" );
		
		}

		//console.log( "\n***" );
		//console.log( "placed bid for " + bid );
		//console.log( "placed ask for " + ask );
		//console.log( "******************************\n" );


	}

};


var RunAll = function(err, response, data) {
	publicClient.getProductOrderBook(BuyLowSellHighCB);
};

setInterval(RunAll, 10000);
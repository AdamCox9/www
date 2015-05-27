
/**
 **/

var key = '96f5aece4b51a56d71520a6f8f55f5d3';
var b64secret = 'RU2oFVSOVXJRUEtLSWt7cUoN1WMY2Q8COidj8e65yr+1G5YmDev3ezRqkGEjLt+96ypxrRpHmZ8QmKhqi33Vpw==';
passphrase = 'dd6bf0643ebe8959960a246f8085695a74d064d856e6b03791d65d9fd878a95c';

var CoinbaseExchange = require( 'coinbase-exchange' );
var publicClient = new CoinbaseExchange.PublicClient();
var authedClient = new CoinbaseExchange.AuthenticatedClient( key, b64secret, passphrase );


var bid = '',
	ask = '';

var callback = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n\n" );
};

var BuyLowSellHighCB = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n\n" );
	
	if( data ) {

		bid = data['bids'][0][0];
		ask = data['asks'][0][0];

		//console.log( "Bid: " + bid + "\n\n" );
		//console.log( "Ask: " + ask + "\n\n" );

		// Buy 0.01 BTC @ 100 USD 
		var buyParams = { 'price': bid, 'size': '0.01010101', 'product_id': 'BTC-USD', };
		authedClient.buy(buyParams, callback);
		 
		// Sell 0.01 BTC @ 300 USD 
		var sellParams = { 'price': ask, 'size': '0.01010101', 'product_id': 'BTC-USD', };
		authedClient.sell(sellParams, callback);

		//console.log( "placed bid for " + bid + "\n" );
		//console.log( "placed ask for " + ask + "\n\n" );
		//console.log( "***\n\n" );

	}

};


var RunAll = function(err, response, data) {
	publicClient.getProductOrderBook(BuyLowSellHighCB);
};

setInterval(RunAll, 15000);
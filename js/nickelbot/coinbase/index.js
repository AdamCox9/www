
/**
 **/

var key = '96f5aece4b51a56d71520a6f8f55f5d3';
var b64secret = 'RU2oFVSOVXJRUEtLSWt7cUoN1WMY2Q8COidj8e65yr+1G5YmDev3ezRqkGEjLt+96ypxrRpHmZ8QmKhqi33Vpw==';
passphrase = 'dd6bf0643ebe8959960a246f8085695a74d064d856e6b03791d65d9fd878a95c';

var CoinbaseExchange = require( 'coinbase-exchange' ),
	publicClient = new CoinbaseExchange.PublicClient(),
	authedClient = new CoinbaseExchange.AuthenticatedClient( key, b64secret, passphrase ),
	bid = '',
	ask = '',
	spread = '',
	buyParams = '',
	sellParams = '',
	USDAvailable = '',
	BTCAvailable = '';

var callback = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n\n" );
};

var BuyLowSellHighCB = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n\n" );
	
	if( data ) {

		bid = data['bids'][0][0];
		ask = data['asks'][0][0];
		spread = ask - bid;
		size = '0.01';
		minSpread = spread;
		newBid = bid;
		newAsk = ask;

		while( minSpread <= 0.2 ) {
			newBid = parseFloat(newBid) - 0.01;
			newAsk = parseFloat(newAsk) + 0.01;
			minSpread = newAsk - newBid;
		}

		/*if( spread >= 0.1 )
			size = '0.04';*/
	
		//console.log( "\n\n******************************" );
		//console.log( "Spread: " + spread );
		//console.log( "Bid: " + bid );
		//console.log( "Ask: " + ask );
		//console.log( "***\n" );

		buyParams = { 'price': bid, 'size': size, 'product_id': 'BTC-USD', };
		authedClient.buy(buyParams, callback);

		sellParams = { 'price': ask, 'size': size, 'product_id': 'BTC-USD', };
		authedClient.sell(sellParams, callback);

		buyParams = { 'price': newBid, 'size': size, 'product_id': 'BTC-USD', };
		authedClient.buy(buyParams, callback);

		sellParams = { 'price': newAsk, 'size': size, 'product_id': 'BTC-USD', };
		authedClient.sell(sellParams, callback);

		if( spread >= 0.07 ) {
			buyParams = { 'price': parseFloat(bid) + 0.01, 'size': size, 'product_id': 'BTC-USD', };
			authedClient.buy(buyParams, callback);

			sellParams = { 'price': parseFloat(ask) - 0.01, 'size': size, 'product_id': 'BTC-USD', };
			authedClient.sell(sellParams, callback);

			buyParams = { 'price': parseFloat(bid) + 0.02, 'size': size, 'product_id': 'BTC-USD', };
			authedClient.buy(buyParams, callback);

			sellParams = { 'price': parseFloat(ask) - 0.02, 'size': size, 'product_id': 'BTC-USD', };
			authedClient.sell(sellParams, callback);

			buyParams = { 'price': parseFloat(bid) + 0.03, 'size': size, 'product_id': 'BTC-USD', };
			authedClient.buy(buyParams, callback);

			sellParams = { 'price': parseFloat(ask) - 0.03, 'size': size, 'product_id': 'BTC-USD', };
			authedClient.sell(sellParams, callback);
		}
	}

};

var GetProductOrderBook = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n\n" );

	if( data && data[0] )
		if( data[0]['currency'] === 'USD' ) {
			BTCData = data[1];
			USDData = data[0];
		} else {
			BTCData = data[0];
			USDData = data[1];
		}

	USDAvailable = USDData['available'];
	BTCAvailable = BTCData['available'];

//	if( parseFloat(USDAvailable) > 50 || parseFloat(BTCAvailable) > 0.25 )
		publicClient.getProductOrderBook(BuyLowSellHighCB);
};

var StartChain = function(err, response, data) {
	authedClient.getAccounts(GetProductOrderBook);
};

setInterval(StartChain, 5000);
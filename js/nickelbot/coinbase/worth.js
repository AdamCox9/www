/*****

	This script should calculate worth in USD, BTC, and include open ORDERS

	Show total for each.

	It should periodically refresh.

	USDWorth = BTCTotal * BTCPrice + USDTotal

 *****/

var key = '96f5aece4b51a56d71520a6f8f55f5d3';
var b64secret = 'RU2oFVSOVXJRUEtLSWt7cUoN1WMY2Q8COidj8e65yr+1G5YmDev3ezRqkGEjLt+96ypxrRpHmZ8QmKhqi33Vpw==';
passphrase = 'dd6bf0643ebe8959960a246f8085695a74d064d856e6b03791d65d9fd878a95c';

var CoinbaseExchange = require( 'coinbase-exchange' );
var publicClient = new CoinbaseExchange.PublicClient();
var authedClient = new CoinbaseExchange.AuthenticatedClient( key, b64secret, passphrase );

var callback = function(err, response, data) {
	console.log( JSON.stringify( data ) + "\n\n" );
};

var cbGetProductOrderBook = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n\n" );

	bid = data['bids'][0][0];
	ask = data['asks'][0][0];
	spread = ask - bid;
	BTCPrice = ( parseFloat(ask) + parseFloat(bid) ) / 2;

	authedClient.getAccounts(cbGetAccounts);

};
publicClient.getProductOrderBook(cbGetProductOrderBook);

var cbGetAccounts = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n\n" );

	if( data[0]['currency'] === 'USD' ) {
		BTCData = data[1];
		USDData = data[0];
	} else {
		BTCData = data[0];
		USDData = data[1];
	}

	USDBalance = USDData['balance'];
	USDHold = USDData['hold'];
	USDAvailable = USDData['available'];

	BTCBalance = BTCData['balance'];
	BTCHold = BTCData['hold'];
	BTCAvailable = BTCData['available'];

	USDWorth = parseFloat(USDBalance) + parseFloat(BTCBalance) * BTCPrice;
	BTCWorth = parseFloat(BTCBalance) + parseFloat(USDBalance) / BTCPrice;

	console.log( "BTC Price " + BTCPrice + "\n" );
	
	console.log( "USD Balance " + USDBalance );
	console.log( "USD Hold " + USDHold );
	console.log( "USD Available " + USDAvailable );
	console.log( "USD Worth " + USDWorth );
	console.log( "USD Worth in BTC " + USDWorth / BTCPrice );
	console.log( "" );
	console.log( "BTC Balance " + BTCBalance );
	console.log( "BTC Hold " + BTCHold );
	console.log( "BTC Available " + BTCAvailable );
	console.log( "BTC Worth " + BTCWorth );
	console.log( "BTC Worth in USD " + BTCWorth * BTCPrice );
	

};

//authedClient.getAccount(accountBTC, callback);
//authedClient.getAccountHistory(accountUSD, callback);
//authedClient.getAccountHolds(accountUSD, callback);

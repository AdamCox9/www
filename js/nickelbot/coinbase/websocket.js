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
	console.log( JSON.stringify( data ) + "\n" );
};

var CoinbaseExchange = require('coinbase-exchange');
var orderbookSync = new CoinbaseExchange.OrderbookSync();

var poller = function(err, response, data) {
	console.log( JSON.stringify( orderbookSync.book.state() ) );
};

setInterval(poller, 5000);
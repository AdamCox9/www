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

var GetProductHistoricRates = function(err, response, data) {
	console.log( JSON.stringify( data ) + "\n" );

	publicClient.getProductOrderBook({'level': 2},cbLevel2GetProductOrderBook);

}

var cbLevel3GetProductOrderBook = function(err, response, data) {
	console.log( JSON.stringify( data ) + "\n" );

	publicClient.getProductOrderBook({'level': 2},cbLevel2GetProductOrderBook);

}

var cbLevel2GetProductOrderBook = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n" );

	bid = data['bids'][0][0];
	bidVolume = data['bids'][0][1];
	ask = data['asks'][0][0];
	askVolume = data['asks'][0][1];
	spread = ask - bid;
	BTCPrice = ( parseFloat(ask) + parseFloat(bid) ) / 2;
	TotalBid = 0;
	TotalBidVolume = 0;
	TotalAsk = 0;
	TotalAskVolume = 0;

	data['bids'].sort(function(a, b){
		return b[1]-a[1];
	});

	data['asks'].sort(function(a, b){
		return b[1]-a[1];
	});

	for( i = 0; i < data['bids'].length; i++ ){
		//console.log( "bid: " + data['bids'][i][0] + " size " + data['bids'][i][1] + " count " + data['bids'][i][2] );
		TotalBid = parseFloat(TotalBid) + parseFloat(data['bids'][i][1]);
		TotalBidVolume = parseFloat(TotalBidVolume) + parseFloat(data['bids'][i][2]);
	}

	for( i = 0; i < data['asks'].length; i++ ){
		//console.log( "ask: " + data['asks'][i][0] + " size " + data['asks'][i][1] + " count " + data['bids'][i][2] );
		TotalAsk = parseFloat(TotalAsk) + parseFloat(data['asks'][i][1]);
		TotalAskVolume = parseFloat(TotalAskVolume) + parseFloat(data['asks'][i][2]);
	}

	console.log( "Avg Price " + BTCPrice );
	console.log( "Total Bid: " + TotalBid + " Vol: " + TotalBidVolume + " Val: " + TotalBid * TotalBidVolume );
	console.log( "Total Ask: " + TotalAsk + " Vol: " + TotalAskVolume + " Val: " + TotalAsk * TotalAskVolume );

	console.log( "Biggest Bid: " + data['bids'][0][0] + " Size: " + data['bids'][0][1] );
	console.log( "Biggest Ask: " + data['asks'][0][0] + " Size: " + data['asks'][0][1] );
	console.log( "Second Biggest Bid: " + data['bids'][1][0] + " Size: " + data['bids'][1][1] );
	console.log( "Second Biggest Ask: " + data['asks'][1][0] + " Size: " + data['asks'][1][1] );

	console.log( "Bid: " + bid + " vol: " + bidVolume );
	console.log( "Ask: " + ask + " vol: " + askVolume );
	console.log( "Spread: " + spread );
	console.log( "Time " + getDateTime() + "\n");

	publicClient.getProduct24HrStats(cbGetProduct24HrStats);

};

var cbGetAccounts = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n" );

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
	console.log( "BTC Worth in USD " + BTCWorth * BTCPrice + "\n" );

};

var cbGetProduct24HrStats = function(err, response, data) {
	//console.log( JSON.stringify( data ) + "\n" );

	console.log( "Open: " + data['open'] );
	console.log( "High: " + data['high'] );
	console.log( "Low: " + data['low'] );
	console.log( "Volume: " + data['volume'] + "\n" );

	authedClient.getAccounts(cbGetAccounts);

};

function getDateTime() {

    var date = new Date();

    var hour = date.getHours();
    hour = (hour < 10 ? "0" : "") + hour;

    var min  = date.getMinutes();
    min = (min < 10 ? "0" : "") + min;

    var sec  = date.getSeconds();
    sec = (sec < 10 ? "0" : "") + sec;

    var year = date.getFullYear();

    var month = date.getMonth() + 1;
    month = (month < 10 ? "0" : "") + month;

    var day  = date.getDate();
    day = (day < 10 ? "0" : "") + day;

    return year + ":" + month + ":" + day + ":" + hour + ":" + min + ":" + sec;

}

publicClient.getProductHistoricRates({'granularity': 100}, GetProductHistoricRates);

//authedClient.getAccount(accountBTC, callback);
//authedClient.getAccountHistory(accountUSD, callback);
//authedClient.getAccountHolds(accountUSD, callback);

The idea of this platform is to expose all of the functionality from each of the crypto trading exchanges. 
This allows for developers to quickly write a bot that works on all of the exchanges instead of learning each API and writing a bot for each exchange.
Each adapter should return the same output as any other adapter for the other exchanges. There should be unit tests that test each adapter and test the native API for each exchange.
It is important to maintain the full functionality offered by each exchange and make sure exchanges that don't support some functionality to respond appropriately.
For example, only some exchanges offer margin trading. Adapters that don't support margin trading functionality shall respond with appropriate error messages.
It should not be hard to port an existing bot designed for a specific exchange to this platform and have it work across all the exchanges.
There should be plenty of example bots to get the beginner started in no time. The advanced user should have all functionality available
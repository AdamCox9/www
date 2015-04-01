	/*

		This file will have the "game logic" and it will utilize the "commands" found in controls.js

		It should abstract all the lower level logic which is the controls file...

		THIS FILE WILL NOT DIRECTLY INTERACT WITH THE OBJECTS ON THE CANVAS!
		THE DIRECT MANIPULATION OF THE OBJECTS SHOULD BE DONE IN CONTROLS.JS!
		DRAW.JS IS THE INITIAL DRAWING OF THE GRAPHICS ON THE CANVAS!

		These functions should be reusable for any of the stageX.js files to use!

		No functions in here should be directly called through an onclick handler...for consistency, etc...

		There should be a function in the levelX.js file that will route the function call to here.

	*/

	/*

		Start the eye looping - this will be triggered on load or by clicking a link...
		It will loop around updating the child as needed...

	*/

	var gameState = 0;			//0 - game has not started or has ended, 1 - game has started
	var charState = 0;			//0 - character looking at user, 1 - character looking away
	var counter = 0;			//How long has the game been in progress - no official time unit though
	var eyeState = 0;			//0 - looking forward, 1 - looking to the left, 2 - looking to the left & up
	var loopLock = 0;			//Don't want to interrupt anything in a bad state...
	var giveCookieState = 0;	//Is ethan eating a cookie?
	var timerState = true;			//Continue to update the clock?

	/*

		This will Begin the "game".

	*/

	function Begin()
	{
		gameState = 1;
		Start();
		SetText('message_box', "Present your command with a cookie.");
		time = 0;
		timerState = true;
		InitiateTimer();
	}

	/*
		
		Recursively calls itself to animate the character...

	*/

	function MakeAlive()
	{
		setTimeout( "SetArmsOnHead()", 5000 );
		setTimeout( "UndoSetArmsOnHead()", 6000 );
		setTimeout( "SetArmsOnWaist()", 10000 );
		setTimeout( "UndoSetArmsOnWaist()", 11000 );
		setTimeout( "MakeAlive()", 15000 );
	}

	/*

		This will Finish the "game".

	*/

	function Finish()
	{
		gameState = 0;
		End();
		SetText('message_box', "Hello, this is Ethan! Press start to begin.");
		timerState = false;
		ResetLevel();
		ResetStage();
	}

	/*

		Put character into not ready position and have him looking around but not straightforward...

	*/

	function Start()
	{
		if( charState == 0 && giveCookieState == 0 ) {		//Make sure the game isn't already started...
			loopLock = true;								//Stop user from ending prematurely...
			charState = 1;									//Game is started...
			counter = 0;									//Reset counter...
			SetCharacterNotReady();							//Default starting state...
			eyeState = 1;									//SetCharacterNotReady puts eyes in this state...
			Loop();											//This will recursively call itself...
		}
	}

	/*
		Let's put the eye's straight forward and stop the Loop...
	*/

	function End()
	{
		if( charState == 1 ) {	//Make sure game is started before we end it...
			charState = 0; //Game ended...

			while( loopLock ) {
				//Wait
			}

			//Put eyeState == 1 if it isn't already...
			if( eyeState == 2 ) {
				AnimateEyes(0.1, 0.1, 0.1);
			}
			
			//Default when game is not in started state:
			SetCharacterReady();
			
			//SetCharacterReady puts eyeState == 0; Eye Movement (Loop()) will end until started again...
			eyeState = 0;
		}
	}

	/*
	
		Recursively call self as long as game was not ended:

	*/

	function Loop()
	{
		loopLock = true;
		if( eyeState != 0 ) {
			if( counter++ % 2 == 0 ) {
				//alert( 'top' + counter );
				AnimateEyes(-0.1, -0.1, -0.1);
				eyeState = 2;
			} else {
				//alert( 'bottom' + counter );
				AnimateEyes(0.1, 0.1, 0.1);
				eyeState = 1;
			}
		}
		loopLock = false;

		//Let's call myself:
		if( charState == 1 ) {
			setTimeout( "Loop()", 800);
		}
	}

	/*

		This sets up the stuff for Ethan to get praised

	*/

	function AcceptPraise(func,w1,w2)
	{
		lockAcceptWindow = false;
		setTimeout(func, 450); //func should be SetEyeState(timeInMs-for eye contact)
		setTimeout("AcceptWindowOpen()", w1+450);	//Open the accept window
		setTimeout("AcceptWindowClose()", w2+450);	//Close the accept window
	}

	/*

		This opens the window where a user can succesfully give praise to Ethan

	*/

	function AcceptWindowOpen()
	{
		if( ! lockAcceptWindow ) {
			document.getElementById('ready_for_praise_message_box').style.background = "green";
			acceptWindow = true;
		} else {
			lockAcceptWindow = false;
		}
	}

	/*

		This closes the window where a user can succesfully give praise to Ethan

	*/

	function AcceptWindowClose()
	{
		document.getElementById('ready_for_praise_message_box').style.background = "red";
		acceptWindow = false;
	}

	/*
	
		Put eyes in "Ready State" for timeInMs milliseconds...

	*/

	function SetEyeState(timeInMs)
	{
		if( charState == 1 ) { //Make sure character is in default state...
			End();
			setTimeout( "Start()", timeInMs );
		}
	}

	/*
		Interrupt the eye state at all costs...
	*/

	function EyeStateInterruption()
	{
		Start();
	}

	/*

		Give Ethan a cookie..

	*/

	function GiveCookie()
	{
		//Ethan looks forward when you start to give him a cookie...
		End();

		setGiveCookieState(1);

		//Hide arm with cookie:
		HideCookie();

		//Move the cookie forward: cookie image and arm with cookie image are two separate images
		setTimeout( "ShowGiveCookie()", 750);

		//Make head & eyes bigger:
		setTimeout( "MakeHeadEatCookie()", 500);

		//Return everything to correct position:
		setTimeout( "ReverseGiveCookie()", 1000);
	}

	/*

		Let's just give ethan one quick bite...

	*/

	function ReverseGiveCookie()
	{
		//Move the cookie forward: cookie image and arm with cookie image are two separate images
		setTimeout( "HideGiveCookie()", 750);

		//Let's make an eating sound:
		PlayAudio('eatcookie');

		//Make head & eyes bigger:
		setTimeout( "UndoMakeHeadEatCookie()", 500);

		//Done giving cookie: release lock after ethan is done eating the cookie...
		setTimeout("setGiveCookieState(0)", 1000 );

	}

	/*	

		Lock to make sure nothing else happens while ethan is eating cookie...

	*/

	function setGiveCookieState(x)
	{
		giveCookieState = x;
	}


	/*

		Let's keep those functions reusable!

	*/

	function ShowCookieWithVerbalCommand()
	{
		ShowCookie(); //The cookie will only move in ShowCookie() if cookieState equals 0
		//if( cookieState == 0 ) { //Still say look at me if cookie is showing...
			PlayAudio('ethanlookatme');
		//}
	}

	/*

	
		
	*/

	function GiveCookieWithVerbalPraise()
	{
		GiveCookie();
		PlayAudio('goodjobethan');
	}
/*

	This file should do all the steps in Level One.

	It should not modify anything low level such as the controls.js and logic.js file does.

	Once Level 1 is complete, Level 2 will be activated.

	Level 1 should end in a good state for Level 2 to start.

*/

var level = 0;					//The step for the LEVEL Progress Bar: currently it is 5 steps but can be configured (see script.js::IncrementStage() & DecrementStage()
var ProbEyeState1 = 1;			//Probability that command will result in Eye Contact
var stage = 0;					//This is the stage
var acceptWindow = false;		//Ethan will only accept a "verbal praise" when this is true: this is not relavent for "verbal praise w/ cookie"
var stageRevertCount = 0;		//For stage 2+, if this is 3 or more, then move back a stage...
var trigger10Sec = 0;			//Will trigger a negative if input not recieved within 10 seconds...
var responseLock = false;		//If user tries to give the child multiple commands in a row without praise...
var lockAcceptWindow = false;	//This will prevent the accept window from opening if user praises character before they should...

/*

	Clicks from the UI should go here so they don't access the lower level graphic layers...

*/

function StageOneInput(input)
{
	if( gameState == 0 ) {
		alert( "Please press start" );
		return;
	}

	//Let's make sure the message box text is normal so it can be bolded if the user does something that they shouldn't...
	document.getElementById('message_box').style.fontWeight = "normal";

	/*
	
		User hit the "Verbal Command" button...

	*/

	if( input == 'verbal_command' ) {
		if( stage > 0 ) {

			DisableClick('show_cookie_with_verbal_command_link');
			DisableClick('verbal_command_link');

			if( responseLock ) {
				ResetLevel();
				SetText( 'probability_message_box', "Probability of Eye Contact is 50%" );
				ProbEyeState1 = 0; //Next time user will have only 50% chance of Ethan making eye contact...
				if( stage >= 2 ) {
					stageRevertCount++;
				}
			}
			responseLock = true;

			//But, Ethan might not respond...
			switch (stage)
			{
				//These specify the windows where a correct praise will be accepted:
				case 0: w1 = 0; w2 = 1500; dur = 1500; break;			
				case 1: w1 = 0; w2 = 1500; dur = 1500; break;			
				case 2: w1 = 2000; w2 = 3500; dur = 3500; break;			
				case 3: w1 = 3000; w2 = 4500; dur = 4500; break;			
				case 4: w1 = 4000; w2 = 5500; dur = 5500; break;			
				case 5: w1 = 5000; w2 = 6500; dur = 6500; break;			
				default: w1 = 0; w2 = 1500; break;	
			}
			if( ProbEyeState1 == 1 ) {
				setTimeout( "AcceptPraise('SetEyeState(" + dur + ")'," + w1 + "," + w2 + ")", 1000); //Eye contact will be based on stage...what a bloody mess!
			} else {
				//Make it so there is only 50% chance that Ethan makes eye contact
				if( HalfChance() ) {
					setTimeout( "AcceptPraise('SetEyeState(" + dur + ")'," + w1 + "," + w2 + ")", 1000); //Eye contact will be based on stage...what a bloody mess!
				}
			}						

			//Play verbal command:
			PlayAudio('ethanlookatme');

			//If user don't respond within 10 seconds, there are negative consequences...
			trigger10Sec++;
			setTimeout( "Check10SecTrigger("+trigger10Sec+")", 10000 );
			setTimeout( "EnableClicks()", dur );
		} else {
			/*
				Let's hightlight the instructions if the user is lost...
			*/
			document.getElementById('message_box').style.fontWeight = "bold";
		}
	}

	/*
	
		User hit the "Show Cookie With Verbal Command" button...

	*/

	if( input == 'show_cookie_with_verbal_command' ) {

		if( stage == 0 ) {
			DisableClick('show_cookie_with_verbal_command_link');

			if( responseLock ) {
				ResetLevel();
				SetText( 'probability_message_box', "Probability of Eye Contact is 50%" );
				ProbEyeState1 = 0; //Next time user will have only 50% chance of Ethan making eye contact...
			}
			responseLock = true;

			//But, Ethan might not respond...
			if( ProbEyeState1 == 1 ) {
				//function, windowOpens, windowCloses...
				setTimeout( 'AcceptPraise("SetEyeState(1500)",0,1500)', 1000); //Eye contact will be 1 seconds for now...
			} else {
				//Make it so there is only 50% chance that Ethan makes eye contact
				if( HalfChance() ) {
					setTimeout( 'AcceptPraise("SetEyeState(1500)",0,1500)', 1000); //Eye contact will be 1 second for now...
				}
			}

			//Show cookie with verbal command and then hide it in a couple of seconds...
			ShowCookieWithVerbalCommand();
			setTimeout("HideCookie()", 2000);

			//If user don't respond within 10 seconds, there are negative consequences...
			trigger10Sec++;
			setTimeout( "Check10SecTrigger("+trigger10Sec+")", 10000 );
			setTimeout( "EnableClicks()", 3000 );
		} else {
			/*
				Let's hightlight the instructions if the user is lost...
			*/
			document.getElementById('message_box').style.fontWeight = "bold";
		}
	}

	/*

		User hit the "Give Cookie With Verbal Praise" button...

	*/

	if( input == 'give_cookie_with_verbal_praise' ) {
		if( stage == 0 ) {

			DisableClicks();

			//User put input, so let's unset the trigger:
			trigger10Sec++;

			//User can go ahead and safely give another command...
			responseLock = false;

			if (readyState == 1) {
				if( acceptWindow ) {
					IncrementLevel();
					SetText( 'probability_message_box', "Probability of Eye Contact is 100%" );
					ProbEyeState1 = 1;
				} else {
					lockAcceptWindow = true; //This will prevent the accept window from opening on this turn...
					ResetLevel();
					SetText( 'probability_message_box', "Probability of Eye Contact is 50%" );
					ProbEyeState1 = 0; //Next time user will have only 50% chance of Ethan making eye contact...
				}
			} else { //This is negative territory:
				ResetLevel();
				SetText( 'probability_message_box', "Probability of Eye Contact is 50%" );
				ProbEyeState1 = 0; //Next time user will have only 50% chance of Ethan making eye contact...
			}

			GiveCookieWithVerbalPraise();

			setTimeout("EnableClicks()", 3000);
		} else {
			/*
				Let's hightlight the instructions if the user is lost...
			*/
			document.getElementById('message_box').style.fontWeight = "bold";
		}
	}

	/*

		User hit the "Verbal Praise" button...

	*/

	if( input == 'verbal_praise' ) {
		if( stage > 0 ) {
			DisableClicks();

			//User put input, so let's unset the trigger:
			trigger10Sec++;

			//User can go ahead and safely give another command...
			responseLock = false;

			if( stage >= 1 ) {
				if (readyState == 1) {
					if( acceptWindow ) {
						IncrementLevel();
						SetText( 'probability_message_box', "Probability of Eye Contact is 100%" );
						ProbEyeState1 = 1;
					} else {
						lockAcceptWindow = true; //This will prevent the accept window from opening on this turn...
						ResetLevel();
						SetText( 'probability_message_box', "Probability of Eye Contact is 50%" );
						ProbEyeState1 = 0; //Next time user will have only 50% chance of Ethan making eye contact...
						if( stage >= 2 ) {
							stageRevertCount++;
						}
					}
				} else { //This is negative territory:
					ResetLevel();
					SetText( 'probability_message_box', "Probability of Eye Contact is 50%" );
					ProbEyeState1 = 0; //Next time user will have only 50% chance of Ethan making eye contact...
					if( stage >= 2 ) {
						stageRevertCount++;
					}
				}
			}
			PlayAudio('goodjobethan');

			setTimeout( "EnableClicks()", 1500);
		} else {
			/*
				Let's hightlight the instructions if the user is lost...
			*/
			document.getElementById('message_box').style.fontWeight = "bold";
		}
	}

	/*

		User hit the "" button...

	*/

	if( input == '' || input == null ) {
		alert( "BUG" );
	}

	/*

		User beat a stage by going through 5 levels which are 0-4

	*/

	if( level >= 5 ) {
		IncrementStage();
		ResetLevel();
		stageRevertCount = 0;
	}

	/*

		User is a winner!

	*/

	if( stage >= 5 ) {
		alert( "Congratulations, you have succesfully shaped Ethan! You may start again if you would like." );
		ResetLevel();
		stageRevertCount = 0;
		Finish();	//Not End()...
		return;
	}

	/*

		If user screws up 3 or more times in a stage, do bad thingz...

	*/

	if( stageRevertCount >= 3 ) {
		DecrementStage();
		ResetLevel();
		stageRevertCount = 0;
	}

}

/*

	The user must do something within 10 seconds and this is how we reinforce that...

*/

function Check10SecTrigger(t10S)
{
	//It should have changed by now...
	if( trigger10Sec == t10S ) {
		ResetLevel();
		SetText( 'probability_message_box', "Probability of Eye Contact is 50%" );
		ProbEyeState1 = 0; //Next time user will have only 50% chance of Ethan making eye contact...
		if( stage >= 2 ) {
			stageRevertCount++;
		}
	}
}

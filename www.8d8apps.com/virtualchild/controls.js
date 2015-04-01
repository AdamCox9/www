	/*****

		This section will be the controls to move the character
		See //http://code.google.com/p/glmatrix/wiki/Usage for glMatrix usage

		Every function in here should directly manipulate a graphic in the canvas otherwise it should be in logic.js!!!!!!

		This is not the initial drawing of the objects on the canvas. It only moves them around.

		The graphics are initially drawn on the canvas in the draw.js file.

	*****/

	var readyState = 1;			//Is character looking forward or away?
	var armsOnHeadState = 0;	//Are arms on head?
	var armsOnWaistState = 0;	//Are arms on waist?
	var armsOnSideState = 1;	//Are arms on side?
	var cookieState = 1;		//Is the arm with the cookie in the process of being shown?

	var w = 10;
	function MakeHeadEatCookie()
	{
		if (w > 0)
		{
			w--;
		} else {
			w = 10;
			return;
		}

		var newRotationMatrix;

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Bring the right eye forward:
		mat4.translate(newRotationMatrix, [-.1, -.02, .05]);
		mat4.multiply(newRotationMatrix, rightEyeRotationMatrix, rightEyeRotationMatrix);

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Bring the left eye forward:
		mat4.translate(newRotationMatrix, [-.1, -.02, .05]);
		mat4.multiply(newRotationMatrix, leftEyeRotationMatrix, leftEyeRotationMatrix);

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Bring the head forward:
		mat4.translate(newRotationMatrix, [0, -.1, .4]);
		mat4.multiply(newRotationMatrix, headRotationMatrix, headRotationMatrix);

		setTimeout( "MakeHeadEatCookie()", 75);

	}

	var u = 10;
	function UndoMakeHeadEatCookie()
	{
		if (u > 0)
		{
			u--;
		} else {
			u = 10;
			return;
		}

		var newRotationMatrix;

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Bring the right eye forward:
		mat4.translate(newRotationMatrix, [.1, .02, -.05]);
		mat4.multiply(newRotationMatrix, rightEyeRotationMatrix, rightEyeRotationMatrix);

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Bring the left eye forward:
		mat4.translate(newRotationMatrix, [.1, .02, -.05]);
		mat4.multiply(newRotationMatrix, leftEyeRotationMatrix, leftEyeRotationMatrix);

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Bring the head forward:
		mat4.translate(newRotationMatrix, [0, .1, -.4]);
		mat4.multiply(newRotationMatrix, headRotationMatrix, headRotationMatrix);

		setTimeout( "UndoMakeHeadEatCookie()", 75);

	}


	/*

		This is the big cookie that ethan will take a "bite" out of

	*/

	var z = 10;
	function ShowGiveCookie()
	{
		if (z > 0)
		{
			z--;
		} else {
			z = 10;
			return;
		}

		var newRotationMatrix;

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Translate the cookie:
		mat4.translate(newRotationMatrix, [0, .275, .1]);
		mat4.multiply(newRotationMatrix, giveCookieRotationMatrix, giveCookieRotationMatrix);

		setTimeout( "ShowGiveCookie()", 75);

	}

	/*

		This is the big cookie that ethan will take a "bite" out of

	*/

	var t = 10;
	function HideGiveCookie()
	{
		if (t > 0)
		{
			t--;
		} else {
			t = 10;
			EyeStateInterruption(); //Let's put Ethan back into looking away state...
			return;
		}

		var newRotationMatrix;

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Translate the cookie:
		mat4.translate(newRotationMatrix, [0, -.275, -.1]);
		mat4.multiply(newRotationMatrix, giveCookieRotationMatrix, giveCookieRotationMatrix);

		setTimeout( "HideGiveCookie()", 75);

	}

	/*

		This is the arm holding the cookie

	*/

	var x = 10;
	function ShowCookie()
	{
		if (x > 0)
		{
			x--;
		}
		else
		{
			cookieState = 1;
			x = 10;
			return;
		}
		if( cookieState == 0 ) {
			var newRotationMatrix;

			//Reset the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Translate the cookie:
			mat4.translate(newRotationMatrix, [0, .2, 0]);
			mat4.multiply(newRotationMatrix, cookieRotationMatrix, cookieRotationMatrix);
		}

		setTimeout( "ShowCookie()", 75);

	}

	/*

		This is the arm holding the cookie

	*/

	var y = 10;
	function HideCookie()
	{
		if (y > 0)
		{
			y--;
		}
		else
		{
			cookieState = 0;
			y = 10;
			return;
		}
		if( cookieState == 1 ) {
			var newRotationMatrix;

			//Reset the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Translate the left eye:
			mat4.translate(newRotationMatrix, [0, -.2, 0]);
			mat4.multiply(newRotationMatrix, cookieRotationMatrix, cookieRotationMatrix);
		}
	
		setTimeout( "HideCookie()", 75);

	}

	function SetCharacterReady(event) {

		//Needs to be in not ready state to translate to ready state...
		if( readyState == 0 ) {

			var newRotationMatrix;
			
			//Set the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Rotate the head:
			mat4.rotate(newRotationMatrix, degToRad(-40 / 10), [1, 0, 0]);
			mat4.rotate(newRotationMatrix, degToRad(-300 / 10), [0, 1, 0]);
			mat4.multiply(newRotationMatrix, headRotationMatrix, headRotationMatrix);

			//Set the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Rotate the right eye:
			mat4.rotate(newRotationMatrix, degToRad(-150/10), [0, 1, 0]);
			mat4.multiply(newRotationMatrix, rightEyeRotationMatrix, rightEyeRotationMatrix);

			//Reset the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Rotate the left eye:
			mat4.rotate(newRotationMatrix, degToRad(-150/10), [0, 1, 0]);
			mat4.multiply(newRotationMatrix, leftEyeRotationMatrix, leftEyeRotationMatrix);

			//Reset the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Translate the left eye:
			mat4.translate(newRotationMatrix, [0, 0, -.05]);
			mat4.multiply(newRotationMatrix, leftEyeRotationMatrix, leftEyeRotationMatrix);

			//Reset the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Translate the right eye:
			mat4.translate(newRotationMatrix, [0, 0, -.05]);
			mat4.multiply(newRotationMatrix, rightEyeRotationMatrix, rightEyeRotationMatrix);


		}

		//Set state to ready...
		readyState = 1;

	}

	/*

		Need to rotate then translate instead of translate then rotate...undoing getting character ready...

	*/

	function SetCharacterNotReady(event) {

		//Needs to be in ready state to translate to not ready state...
		if( readyState == 1 ) {

			var newRotationMatrix;
			
			//Set the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Rotate the head:
			mat4.rotate(newRotationMatrix, degToRad(300 / 10), [0, 1, 0]);
			mat4.rotate(newRotationMatrix, degToRad(40 / 10), [1, 0, 0]);
			mat4.multiply(newRotationMatrix, headRotationMatrix, headRotationMatrix);

			//Reset the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Translate the left eye:
			mat4.translate(newRotationMatrix, [0, 0, .05]);
			mat4.multiply(newRotationMatrix, leftEyeRotationMatrix, leftEyeRotationMatrix);

			//Reset the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Translate the right eye:
			mat4.translate(newRotationMatrix, [0, 0, .05]);
			mat4.multiply(newRotationMatrix, rightEyeRotationMatrix, rightEyeRotationMatrix);

			//Reset the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Rotate the right eye:
			mat4.rotate(newRotationMatrix, degToRad(150/10), [0, 1, 0]);
			mat4.multiply(newRotationMatrix, rightEyeRotationMatrix, rightEyeRotationMatrix);

			//Reset the newRotationMatrix variable:
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);

			//Rotate the left eye:
			mat4.rotate(newRotationMatrix, degToRad(150/10), [0, 1, 0]);
			mat4.multiply(newRotationMatrix, leftEyeRotationMatrix, leftEyeRotationMatrix);

		}

		readyState = 0;

	}


	function MoveEyes(x, y, z)
	{
		var newRotationMatrix;

		//Set the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Move the left eye:
		mat4.translate(newRotationMatrix, [x, y, z]);
		mat4.multiply(newRotationMatrix, leftEyeRotationMatrix, leftEyeRotationMatrix);

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		//Move the right eye:
		mat4.translate(newRotationMatrix, [x, y, z]);
		mat4.multiply(newRotationMatrix, rightEyeRotationMatrix, rightEyeRotationMatrix);

	}

	function RotateEyes(x, y, z)
	{
		var newRotationMatrix;

		//Set the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		mat4.rotate(newRotationMatrix, degToRad(100/10), [x, y, z]);
		mat4.multiply(newRotationMatrix, rightEyeRotationMatrix, rightEyeRotationMatrix);

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		mat4.rotate(newRotationMatrix, degToRad(100/10), [-x, -y, -z]);
		mat4.multiply(newRotationMatrix, leftEyeRotationMatrix, leftEyeRotationMatrix);

	}

	/*

		Let's rotate the eyes in the same direction based on x, y, z parameters...

	*/

	function AnimateEyes(x, y, z)
	{
		var newRotationMatrix;

		//Set the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		mat4.rotate(newRotationMatrix, degToRad(100/10), [x, y, z]);
		mat4.multiply(newRotationMatrix, rightEyeRotationMatrix, rightEyeRotationMatrix);

		//Reset the newRotationMatrix variable:
		newRotationMatrix = mat4.create();
		mat4.identity(newRotationMatrix);

		mat4.rotate(newRotationMatrix, degToRad(100/10), [x, y, z]);
		mat4.multiply(newRotationMatrix, leftEyeRotationMatrix, leftEyeRotationMatrix);

	}


	//This will make the characters arms be on his head...
	function SetArmsOnHead()
	{
		var delta;
		var newRotationMatrix;

		if( armsOnSideState == 1 ) {

			/*
			
				Left Fore Arm...

			*/

			//First, Move Left Fore Arm Up & Over...
			newRotationMatrix = mat4.create(); //Not really rotating here...just moving...
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [.6, .7, 0]);
			mat4.multiply(newRotationMatrix, leftForeArmRotationMatrix, leftForeArmRotationMatrix);

			//Then, Rotate Left Fore Arm...
			delta = 400;
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(delta/10), [0, 1, 1]);
			mat4.multiply(newRotationMatrix, leftForeArmRotationMatrix, leftForeArmRotationMatrix);

			/*
			
				Right Fore Arm...

			*/

			//First, Move Right Fore Arm Up...
			//Same as Left Fore Arm but negative on x-axis...
			newRotationMatrix = mat4.create(); //Not really rotating here...just moving...
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [-.6, .7, 0]);
			mat4.multiply(newRotationMatrix, rightForeArmRotationMatrix, rightForeArmRotationMatrix);

			//Then, Rotate Right Fore Arm...
			delta = -400; //Same as Left Fore Arm but negative...
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(delta/10), [0, 1, 1]);
			mat4.multiply(newRotationMatrix, rightForeArmRotationMatrix, rightForeArmRotationMatrix);

			/*
			
				Left Upper Arm...

			*/

			//First, Move Left Upper Arm Up...
			newRotationMatrix = mat4.create(); //Not really rotating here...just moving...
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [-.05, .125, 0]);
			mat4.multiply(newRotationMatrix, leftUpperArmRotationMatrix, leftUpperArmRotationMatrix);

			//Then, Rotate Left Upper Arm...
			delta = -800;
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(delta/10), [0, 1, 1]);
			mat4.multiply(newRotationMatrix, leftUpperArmRotationMatrix, leftUpperArmRotationMatrix);

			/*
			
				Right Upper Arm...

			*/

			//First, Move Right Upper Arm up...
			//Same as Left Upper Arm but positive on x-axis...
			newRotationMatrix = mat4.create(); //Not really rotating here...just moving...
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [.05, .125, 0]);
			mat4.multiply(newRotationMatrix, rightUpperArmRotationMatrix, rightUpperArmRotationMatrix);

			//Then, Rotate Right Upper Arm...
			delta = 800;	//Same as Left Upper Arm but positive...
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(delta/10), [0, 1, 1]);
			mat4.multiply(newRotationMatrix, rightUpperArmRotationMatrix, rightUpperArmRotationMatrix);

			//Let's update the states:
			armsOnSideState = 0;
			armsOnHeadState = 1;
		
		}

	}



	//This will make the characters arms be on his head...
	function UndoSetArmsOnHead()
	{
		var delta;
		var newRotationMatrix;

		if( armsOnSideState == 0 && armsOnHeadState == 1 ) {

			/*
			
				Left Fore Arm...

			*/

			//Then, Rotate Left Fore Arm...
			delta = -400;
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(delta/10), [0, 1, 1]);
			mat4.multiply(newRotationMatrix, leftForeArmRotationMatrix, leftForeArmRotationMatrix);

			//First, Move Left Fore Arm Up & Over...
			newRotationMatrix = mat4.create(); //Not really rotating here...just moving...
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [-.6, -.7, 0]);
			mat4.multiply(newRotationMatrix, leftForeArmRotationMatrix, leftForeArmRotationMatrix);

			/*
			
				Right Fore Arm...

			*/

			//Then, Rotate Right Fore Arm...
			delta = 400; //Same as Left Fore Arm but negative...
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(delta/10), [0, 1, 1]);
			mat4.multiply(newRotationMatrix, rightForeArmRotationMatrix, rightForeArmRotationMatrix);

			//First, Move Right Fore Arm Up...
			//Same as Left Fore Arm but negative on x-axis...
			newRotationMatrix = mat4.create(); //Not really rotating here...just moving...
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [.6, -.7, 0]);
			mat4.multiply(newRotationMatrix, rightForeArmRotationMatrix, rightForeArmRotationMatrix);

			/*
			
				Left Upper Arm...

			*/

			//Then, Rotate Left Upper Arm...
			delta = 800;
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(delta/10), [0, 1, 1]);
			mat4.multiply(newRotationMatrix, leftUpperArmRotationMatrix, leftUpperArmRotationMatrix);

			//First, Move Left Upper Arm Up...
			newRotationMatrix = mat4.create(); //Not really rotating here...just moving...
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [.05, -.125, 0]);
			mat4.multiply(newRotationMatrix, leftUpperArmRotationMatrix, leftUpperArmRotationMatrix);

			/*
			
				Right Upper Arm...

			*/

			//Then, Rotate Right Upper Arm...
			delta = -800;	//Same as Left Upper Arm but positive...
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(delta/10), [0, 1, 1]);
			mat4.multiply(newRotationMatrix, rightUpperArmRotationMatrix, rightUpperArmRotationMatrix);

			//First, Move Right Upper Arm up...
			//Same as Left Upper Arm but positive on x-axis...
			newRotationMatrix = mat4.create(); //Not really rotating here...just moving...
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [-.05, -.125, 0]);
			mat4.multiply(newRotationMatrix, rightUpperArmRotationMatrix, rightUpperArmRotationMatrix);

			//Let's update the states:
			armsOnSideState = 1;
			armsOnHeadState = 0;

		}

	}


	//This will make the characters arms be on his waist...
	function SetArmsOnWaist()
	{
		var delta;
		var newRotationMatrix;
		
		if( armsOnSideState == 1 ) {

			//Left Fore Arm
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(-400/10), [0, 0, 1]);
			mat4.translate(newRotationMatrix, [0, .11, 0]);
			mat4.multiply(newRotationMatrix, leftForeArmRotationMatrix, leftForeArmRotationMatrix);

			//Right Fore Arm
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(400/10), [0, 0, 1]);
			mat4.translate(newRotationMatrix, [0, .11, 0]);
			mat4.multiply(newRotationMatrix, rightForeArmRotationMatrix, rightForeArmRotationMatrix);

			//Left Upper Arm
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(400/10), [0, 0, 1]);
			mat4.translate(newRotationMatrix, [0, 0, .4]);
			mat4.multiply(newRotationMatrix, leftUpperArmRotationMatrix, leftUpperArmRotationMatrix);

			//Right Upper Arm
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.rotate(newRotationMatrix, degToRad(-400/10), [0, 0, 1]);
			mat4.translate(newRotationMatrix, [0, 0, .4]);
			mat4.multiply(newRotationMatrix, rightUpperArmRotationMatrix, rightUpperArmRotationMatrix);

			//Let's update the states:
			armsOnSideState = 0;
			armsOnWaistState = 1;

		}

	}
	
	//This will make the characters arms be back on side from the waist...
	function UndoSetArmsOnWaist()
	{
		var newRotationMatrix;
		
		if( armsOnWaistState == 1 && armsOnSideState == 0 ) {

			//Left Fore Arm
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [0, -.11, 0]);
			mat4.rotate(newRotationMatrix, degToRad(400/10), [0, 0, 1]);
			mat4.multiply(newRotationMatrix, leftForeArmRotationMatrix, leftForeArmRotationMatrix);

			//Right Fore Arm
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [0, -.11, 0]);
			mat4.rotate(newRotationMatrix, degToRad(-400/10), [0, 0, 1]);
			mat4.multiply(newRotationMatrix, rightForeArmRotationMatrix, rightForeArmRotationMatrix);

			//Left Upper Arm
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [0, 0, -.4]);
			mat4.rotate(newRotationMatrix, degToRad(-400/10), [0, 0, 1]);
			mat4.multiply(newRotationMatrix, leftUpperArmRotationMatrix, leftUpperArmRotationMatrix);

			//Right Upper Arm
			newRotationMatrix = mat4.create();
			mat4.identity(newRotationMatrix);
			mat4.translate(newRotationMatrix, [0, 0, -.4]);
			mat4.rotate(newRotationMatrix, degToRad(400/10), [0, 0, 1]);
			mat4.multiply(newRotationMatrix, rightUpperArmRotationMatrix, rightUpperArmRotationMatrix);

			//Let's update the states:
			armsOnWaistState = 0;
			armsOnSideState = 1;

		}

	}
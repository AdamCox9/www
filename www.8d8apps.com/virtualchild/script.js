	/*

		This file is mostly utility type functions that can be used throughout.

		It also has the initial call from the onload function

		It has some global variables and the tick function which does the animation

	*/

	var gl;
	var mvMatrix = mat4.create();
	var pMatrix = mat4.create();

	/*

		Initialize the gl with respect to the canvas context element

	*/

	function initGL(canvas) {
		try {
			gl = canvas.getContext("experimental-webgl");
			gl.viewportWidth = canvas.width;
			gl.viewportHeight = canvas.height;
		} catch (e) {
		}
		if (!gl) {
			alert("Could not initialise WebGL, sorry :-(");
		}
	}

	/*

		I have no idea

	*/

	function setMatrixUniforms() {
		gl.uniformMatrix4fv(shaderProgram.pMatrixUniform, false, pMatrix);
		gl.uniformMatrix4fv(shaderProgram.mvMatrixUniform, false, mvMatrix);

		var normalMatrix = mat3.create();
		mat4.toInverseMat3(mvMatrix, normalMatrix);
		mat3.transpose(normalMatrix);
		gl.uniformMatrix3fv(shaderProgram.nMatrixUniform, false, normalMatrix);
	}

	/*

		Translate degrees to radians

	*/

	function degToRad(degrees) {
		return degrees * Math.PI / 180;
	}

	/*

		This function get's called initially onload to webGLStart() to tick()

	*/

	function tick() {
		//This makes it animate so screen is updated every X milliseconds...
		requestAnimFrame(tick);

		//Draw each body part...
		drawScene();
	}

	/*

		Called from onload in html body element

	*/

	function webGLStart() {
		var canvas = document.getElementById("canvas");
		initGL(canvas);

		//Initialize all of the shaders in shaders.js
		initShaders();

		//Initialize all of the buffers in buffers.js
		initBuffers();

		//Initialize all of the textures in textures.js...
		initTextures();

		//Set-up the canvas...don't need to happen more than once so don't put it in tick()...
		gl.clearColor(0.0, 0.0, 0.0, 1.0);
		gl.enable(gl.DEPTH_TEST);

		//Tick tock...
		tick();
	}

	/*

		Convenience reusable utility function to make code a little bit cleaner

	*/

	function SetText(id,text)
	{
		document.getElementById(id).innerHTML = text;
	}

	/*

		Increment the current stage on the UI

	*/

	function IncrementStage()
	{
		if( document.getElementById('progress_bar').offsetWidth < 268 ) {
			document.getElementById('progress_bar').style.width = (document.getElementById('progress_bar').offsetWidth + 54) + 'px';
		}
		stage++;

		SetStageText();

	}

	/*

		Decrement the current stage on the UI

	*/

	function DecrementStage()
	{
		if( document.getElementById('progress_bar').offsetWidth >= 54 ) {
			document.getElementById('progress_bar').style.width = (document.getElementById('progress_bar').offsetWidth - 54) + 'px';
			stage--;
		}
		SetStageText()
	}

	/*

		This will update the text in the message box accordingly.

	*/

	function SetStageText()
	{
		if( stage != 6 ) {
			SetText( 'progress_bar_message_box', "STAGE Progress Bar (Stage " + stage + ")" );
		}

		switch (stage)
		{
			case 0:
				SetText( "message_box", "Present your command with a cookie!" );
				break;
			case 1:
				SetText( "message_box", "Don't use the cookie anymore!" );
				break;
			case 2:
				SetText( "message_box", "Try for 2 seconds of looking!" );
				break;
			case 3:
				SetText( "message_box", "Try for 3 seconds of looking!" );
				break;
			case 4:
				SetText( "message_box", "Try for 4 seconds of looking!" );
				break;
			case 5:
				SetText( "message_box", "Try for 5 seconds of looking!" );
				break;	
		}
	}

	/*

		Increment the current level on the UI

	*/

	function IncrementLevel()
	{
		if( document.getElementById('level_progress_bar').offsetWidth < 268 ) {
			document.getElementById('level_progress_bar').style.width = (document.getElementById('level_progress_bar').offsetWidth + 54) + 'px';
			level++;
		}
		SetText( 'level_progress_bar_message_box', "LEVEL Progress Bar (Level " + level + ")" );
	}

	/*

		Decrement the current level on the UI

	*/

	function DecrementLevel()
	{
		if( document.getElementById('level_progress_bar').offsetWidth >= 54 ) {
			document.getElementById('level_progress_bar').style.width = (document.getElementById('level_progress_bar').offsetWidth - 54) + 'px';
			level--;
		}
		SetText( 'level_progress_bar_message_box', "LEVEL Progress Bar (Level " + level + ")" );
	}

	function ResetStage()
	{
		document.getElementById('progress_bar').style.width = '0px';
		stage = 0;
		SetText( 'progress_bar_message_box', "LEVEL Progress Bar (Stage " + stage + ")" );
	}

	function ResetLevel()
	{
		document.getElementById('level_progress_bar').style.width = '0px';
		level = 0;
		SetText( 'level_progress_bar_message_box', "LEVEL Progress Bar (Level " + level + ")" );
	}

	/*

		Let's display the time since the user pressed start and update it every second...
		This will help with user responding in the appropriate timeframe also...

	*/

	var time = 0;
	function InitiateTimer()
	{
		if( timerState ) {
			SetText('timer_box', "Time: " + ++time);
			setTimeout( "InitiateTimer()", 1000 );
		}
	}

	function showBox(id1,id2)
	{
		document.getElementById(id1).style.display = 'block';
		document.getElementById('hidden_' + id1).style.display = 'none';
		if( id2 != null ) {
			document.getElementById(id2).style.display = 'block';
		}
	}

	function hideBox(id1,id2)
	{
		document.getElementById(id1).style.display = 'none';
		document.getElementById('hidden_' + id1).style.display = 'block';
		if( id2 != null ) {
			document.getElementById(id2).style.display = 'none';
		}
	}

	/*

	Disable a click on a specific link...

	*/

	function DisableClick(id)
	{
		document.getElementById(id).onclick=function(e){
		 if (e && e.stopPropagation) //if stopPropagation method supported
		  e.stopPropagation()
		 else
		  event.cancelBubble=true
		}
	}

	/*

		Disable all the clicks to the UI controls...

	*/

	function DisableClicks()
	{
		DisableClick('verbal_command_link');
		DisableClick('show_cookie_with_verbal_command_link');
		DisableClick('verbal_praise_link');
		DisableClick('give_cookie_with_verbal_praise_link');
	}

	/*

		Re-route the click events back to where they should to go...

	*/

	function EnableClicks()
	{
		document.getElementById('verbal_command_link').onclick = function(){ StageOneInput('verbal_command'); };
		document.getElementById('show_cookie_with_verbal_command_link').onclick = function(){ StageOneInput('show_cookie_with_verbal_command'); };
		document.getElementById('verbal_praise_link').onclick = function(){ StageOneInput('verbal_praise'); };
		document.getElementById('give_cookie_with_verbal_praise_link').onclick = function(){ StageOneInput('give_cookie_with_verbal_praise'); };
	}

	/*

		Should return true about half of the time and false the other half of the time...

	*/

	function HalfChance()
	{
		if( Math.floor((Math.random()*10)) <= 5 )
			return true;
		return false;
	}


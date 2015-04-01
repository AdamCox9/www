	/*

		This file is the initial drawing of the objects on the screen.

		They get called repeatedly but the positions of the objects are updated in the controls.js file.
		

		First, Let's make the variables for the rotation matrices used in the functions below and used in the controls located in the controls.js file

	*/

	//Head...
	var headRotationMatrix = mat4.create();
	mat4.identity(headRotationMatrix);

	//Cookie...
	var cookieRotationMatrix = mat4.create();
	mat4.identity(cookieRotationMatrix);

	//Give Cookie...
	var giveCookieRotationMatrix = mat4.create();
	mat4.identity(giveCookieRotationMatrix);

	//Left Eye...
	var leftEyeRotationMatrix = mat4.create();
	mat4.identity(leftEyeRotationMatrix);

	//Right Eye...
	var rightEyeRotationMatrix = mat4.create();
	mat4.identity(rightEyeRotationMatrix);

	//Left Fore Arm...
	var leftForeArmRotationMatrix = mat4.create();
	mat4.identity(leftForeArmRotationMatrix);

	//Right Fore Arm...
	var rightForeArmRotationMatrix = mat4.create();
	mat4.identity(rightForeArmRotationMatrix);

	//Left Upper Arm...
	var leftUpperArmRotationMatrix = mat4.create();
	mat4.identity(leftUpperArmRotationMatrix);

	//Right Upper Arm...
	var rightUpperArmRotationMatrix = mat4.create();
	mat4.identity(rightUpperArmRotationMatrix);

	/*

		The drawScene() function is Called from tick()
		Draw all the body parts: calls each function below it...

	*/

	function drawScene()
	{
		//Get ready to draw
		prepareForDrawing();

		drawBodyScene();
		drawHeadScene();
		drawCookieScene();
		drawGiveCookieScene();
		drawLeftEyeScene();
		drawRightEyeScene();
		drawLeftForeArmScene();
		drawLeftUpperArmScene();
		drawRightForeArmScene();
		drawRightUpperArmScene();
	}

	/*

		This function sets up the canvas for drawing

	*/

	function prepareForDrawing()
	{
		//x, y specify the lower left corner of the viewport rectangle, in pixels
		gl.viewport(0, 0, gl.viewportWidth, gl.viewportHeight);

		//Clear canvas to black
		gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

		//Lighting?
		gl.uniform1i(shaderProgram.useLightingUniform, false);

		//Set-up the perspective
		mat4.perspective(45, gl.viewportWidth / gl.viewportHeight, 0.1, 100.0, pMatrix);
	}

	/*

		This will draw the head.
		It is actually animated, but it draws the same thing every time.

	*/

	function drawHeadScene() {

		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [-.05, 1.3, -6]);
		mat4.multiply(mvMatrix, headRotationMatrix);

		//Set-up the texture
		bindTexture(headTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(headVertexPositionBuffer, headVertexTextureCoordBuffer, headVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, headVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}

	/*

		This will draw the body
		It is actually animated, but it draws the same thing every time.

	*/

	function drawBodyScene() {

		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [0.0, 0.0, -7]);

		//Set-up the texture
		bindTexture(bodyTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(bodyVertexPositionBuffer, bodyVertexTextureCoordBuffer, bodyVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, bodyVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}

	/*

		This will draw the arm holding the cookie
		It is actually animated, but it draws the same thing every time.

	*/

	function drawCookieScene() {

		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [.75, -1.75, -6]);
		mat4.multiply(mvMatrix, cookieRotationMatrix);

		//Set-up the texture
		bindTexture(cookieTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(cookieVertexPositionBuffer, cookieVertexTextureCoordBuffer, cookieVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, cookieVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}

	/*

		This will draw the cookie going to the childs mouth
		It is actually animated, but it draws the same thing every time.

	*/

	function drawGiveCookieScene() {

		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [0, -4, -6]);
		mat4.multiply(mvMatrix, giveCookieRotationMatrix);

		//Set-up the texture
		bindTexture(giveCookieTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(giveCookieVertexPositionBuffer, giveCookieVertexTextureCoordBuffer, giveCookieVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, giveCookieVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}

	/*

		This will draw the left fore arm.
		It is actually animated, but it draws the same thing every time.

	*/

	function drawLeftForeArmScene() {

		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [0.25, 0, -5]);
		mat4.multiply(mvMatrix, leftForeArmRotationMatrix);

		//Set-up the texture
		bindTexture(leftForeArmTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(leftForeArmVertexPositionBuffer, leftForeArmVertexTextureCoordBuffer, leftForeArmVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, leftForeArmVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}

	/*

		This will draw the left upper arm.
		It is actually animated, but it draws the same thing every time.

	*/

	function drawLeftUpperArmScene()
	{
		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [0.2, 0.3, -4.25]);
		mat4.multiply(mvMatrix, leftUpperArmRotationMatrix);

		//Set-up the texture
		bindTexture(leftUpperArmTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(leftUpperArmVertexPositionBuffer, leftUpperArmVertexTextureCoordBuffer, leftUpperArmVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, leftUpperArmVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}

	/*

		This will draw the right fore arm.
		It is actually animated, but it draws the same thing every time.

	*/

	function drawRightForeArmScene()
	{
		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [-0.3, 0, -5]);
		mat4.multiply(mvMatrix, rightForeArmRotationMatrix);

		//Set-up the texture
		bindTexture(rightForeArmTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(rightForeArmVertexPositionBuffer, rightForeArmVertexTextureCoordBuffer, rightForeArmVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, rightForeArmVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}

	/*

		This will draw the right upper arm.
		It is actually animated, but it draws the same thing every time.

	*/

	function drawRightUpperArmScene() 
	{
		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [-0.25, 0.3, -4.25]);
		mat4.multiply(mvMatrix, rightUpperArmRotationMatrix);

		//Set-up the texture
		bindTexture(rightUpperArmTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(rightUpperArmVertexPositionBuffer, rightUpperArmVertexTextureCoordBuffer, rightUpperArmVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, rightUpperArmVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}

	/*

		This will draw the left eye.
		It is actually animated, but it draws the same thing every time.

	*/

	function drawLeftEyeScene()
	{
		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [0, .9, -4]);
		mat4.multiply(mvMatrix, leftEyeRotationMatrix);

		//This rotates the object around in the x, y, z coordinates
		mat4.rotate(mvMatrix, degToRad(900/10), [0, 1, 0]);
		mat4.multiply(mvMatrix, leftEyeRotationMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [0, 0, .125]);
		mat4.multiply(mvMatrix, leftEyeRotationMatrix);

		//Set-up the texture
		bindTexture(leftEyeTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(leftEyeVertexPositionBuffer, leftEyeVertexTextureCoordBuffer, leftEyeVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, leftEyeVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}
	
	/*

		This will draw the left eye.
		It is actually animated, but it draws the same thing every time.

	*/

	function drawRightEyeScene()
	{
		//Reset as Identity Matix
		mat4.identity(mvMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [0, .9, -4]);
		mat4.multiply(mvMatrix, rightEyeRotationMatrix);

		//This rotates the object around in the x, y, z coordinates
		mat4.rotate(mvMatrix, degToRad(900/10), [0, 1, 0]);
		mat4.multiply(mvMatrix, rightEyeRotationMatrix);

		//This moves the object around in the x, y, z coordinates
		mat4.translate(mvMatrix, [0, 0, -.125]);
		mat4.multiply(mvMatrix, leftEyeRotationMatrix);

		//Set-up the texture
		bindTexture(rightEyeTexture);

		//Bind the buffers - see bindBuffers(...) function below:
		bindBuffers(rightEyeVertexPositionBuffer, rightEyeVertexTextureCoordBuffer, rightEyeVertexIndexBuffer)

		//In script.js
		setMatrixUniforms();

		//Draw the elements on the canvas
		gl.drawElements(gl.TRIANGLES, rightEyeVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
	}

	/*

		Binds the Buffers based on the parameters - reused throughout this page

	*/

	function bindBuffers(VertexPositionBuffer, VertexTextureCoordBuffer, VertexIndexBuffer) 
	{
		//Right Eye Vertex Position Buffer
		gl.bindBuffer(gl.ARRAY_BUFFER, VertexPositionBuffer);
		gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, VertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

		//Right Eye Vertex Texture Coordinate Buffer
		gl.bindBuffer(gl.ARRAY_BUFFER, VertexTextureCoordBuffer);
		gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, VertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);

		//Right Eye Vertex Index Buffer
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, VertexIndexBuffer);
	}

	/*

		Binds the texture based on the parameter - reused throughout this page

	*/

	function bindTexture(texture)
	{
		gl.activeTexture(gl.TEXTURE0);
		gl.bindTexture(gl.TEXTURE_2D, texture);
		gl.uniform1i(shaderProgram.samplerUniform, 0);
	}
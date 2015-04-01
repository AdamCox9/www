	/*

		Initialize buffers for each body part

	*/

	function initBuffers() {
		initHeadBuffers();
		initBodyBuffers();
		initCookieBuffers();
		initGiveCookieBuffers();
		initLeftEyeBuffers();
		initRightEyeBuffers();
		initLeftForeArmBuffers();
		initLeftUpperArmBuffers();
		initRightForeArmBuffers();
		initRightUpperArmBuffers();
	}

	/*

		This will draw the head

	*/

	var headVertexPositionBuffer;
	var headVertexNormalBuffer;
	var headVertexTextureCoordBuffer;
	var headVertexIndexBuffer;

	function initHeadBuffers() {

		//Some array object type variables...
		var radius = .5;
		var vertexPositionData = [];
		var textureCoordData = [];
		var indexData = [];

		//Let's pass values in by reference...
		generateSphere(vertexPositionData, textureCoordData, indexData, radius);

		//Set-up the Head Vertex Texture Coordinate Buffer...
		headVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, headVertexTextureCoordBuffer);
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoordData), gl.STATIC_DRAW);
		headVertexTextureCoordBuffer.itemSize = 2;
		headVertexTextureCoordBuffer.numItems = textureCoordData.length / 2;

		//Set-up the Head Vertex Position Buffer...
		headVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, headVertexPositionBuffer);
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexPositionData), gl.STATIC_DRAW);
		headVertexPositionBuffer.itemSize = 3;
		headVertexPositionBuffer.numItems = vertexPositionData.length / 3;

		//Set-up the Head Vertex Index Buffer...
		headVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, headVertexIndexBuffer);
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indexData), gl.STATIC_DRAW);
		headVertexIndexBuffer.itemSize = 1;
		headVertexIndexBuffer.numItems = indexData.length;
	}


	var bodyVertexPositionBuffer;
	var bodyVertexTextureCoordBuffer;
	var bodyVertexIndexBuffer;

	function initBodyBuffers() {
		bodyVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, bodyVertexPositionBuffer);
		vertices = [
			// Front face
			-1.0, -1.0,  1.0,
			 1.0, -1.0,  1.0,
			 1.0,  1.0,  1.0,
			-1.0,  1.0,  1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
		bodyVertexPositionBuffer.itemSize = 3;
		bodyVertexPositionBuffer.numItems = 4;

		bodyVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, bodyVertexTextureCoordBuffer);
		var textureCoords = [
		  // Front face
		  0.0, 0.0,
		  1.0, 0.0,
		  1.0, 1.0,
		  0.0, 1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoords), gl.STATIC_DRAW);
		bodyVertexTextureCoordBuffer.itemSize = 2;
		bodyVertexTextureCoordBuffer.numItems = 4;

		bodyVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, bodyVertexIndexBuffer);
		var bodyVertexIndices = [
			0, 1, 2,      0, 2, 3 // Front Face
		];
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(bodyVertexIndices), gl.STATIC_DRAW);
		bodyVertexIndexBuffer.itemSize = 1;
		bodyVertexIndexBuffer.numItems = 6;
	}


	var cookieVertexPositionBuffer;
	var cookieVertexTextureCoordBuffer;
	var cookieVertexIndexBuffer;

	function initCookieBuffers() {
		cookieVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, cookieVertexPositionBuffer);
		vertices = [
			// Front face
			-1.0, -1.0,  1.0,
			 1.0, -1.0,  1.0,
			 1.0,  1.0,  1.0,
			-1.0,  1.0,  1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
		cookieVertexPositionBuffer.itemSize = 3;
		cookieVertexPositionBuffer.numItems = 4;

		cookieVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, cookieVertexTextureCoordBuffer);
		var textureCoords = [
		  // Front face
		  0.0, 0.0,
		  1.0, 0.0,
		  1.0, 1.0,
		  0.0, 1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoords), gl.STATIC_DRAW);
		cookieVertexTextureCoordBuffer.itemSize = 2;
		cookieVertexTextureCoordBuffer.numItems = 4;

		cookieVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cookieVertexIndexBuffer);
		var cookieVertexIndices = [
			0, 1, 2,      0, 2, 3 // Front Face
		];
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(cookieVertexIndices), gl.STATIC_DRAW);
		cookieVertexIndexBuffer.itemSize = 1;
		cookieVertexIndexBuffer.numItems = 6;
	}


	var giveCookieVertexPositionBuffer;
	var giveCookieVertexTextureCoordBuffer;
	var giveCookieVertexIndexBuffer;

	function initGiveCookieBuffers() {
		giveCookieVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, giveCookieVertexPositionBuffer);
		vertices = [
			// Front face
			-1.0, -1.0,  1.0,
			 1.0, -1.0,  1.0,
			 1.0,  1.0,  1.0,
			-1.0,  1.0,  1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
		giveCookieVertexPositionBuffer.itemSize = 3;
		giveCookieVertexPositionBuffer.numItems = 4;

		giveCookieVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, giveCookieVertexTextureCoordBuffer);
		var textureCoords = [
		  // Front face
		  0.0, 0.0,
		  1.0, 0.0,
		  1.0, 1.0,
		  0.0, 1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoords), gl.STATIC_DRAW);
		giveCookieVertexTextureCoordBuffer.itemSize = 2;
		giveCookieVertexTextureCoordBuffer.numItems = 4;

		giveCookieVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, giveCookieVertexIndexBuffer);
		var giveCookieVertexIndices = [
			0, 1, 2,      0, 2, 3 // Front Face
		];
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(giveCookieVertexIndices), gl.STATIC_DRAW);
		giveCookieVertexIndexBuffer.itemSize = 1;
		giveCookieVertexIndexBuffer.numItems = 6;
	}


	var leftForeArmVertexPositionBuffer;
	var leftForeArmVertexTextureCoordBuffer;
	var leftForeArmVertexIndexBuffer;

	function initLeftForeArmBuffers() {
		leftForeArmVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, leftForeArmVertexPositionBuffer);

		//x, y, z points (i. e., vertices)
		/*
			x1, y1, z1
			x2, y2, z2
			x3, y3, z3
			x4, y4, z4
		*/
		vertices = [
			// Front face
			-.08, -.2,  .09,
			 .08, -.2,  .09,
			 .08,  .2,  .09,
			-.08,  .2,  .09
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
		leftForeArmVertexPositionBuffer.itemSize = 3;
		leftForeArmVertexPositionBuffer.numItems = 4;

		leftForeArmVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, leftForeArmVertexTextureCoordBuffer);
		var textureCoords = [
		  // Front face
		  0.0, 0.0,
		  1.0, 0.0,
		  1.0, 1.0,
		  0.0, 1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoords), gl.STATIC_DRAW);
		leftForeArmVertexTextureCoordBuffer.itemSize = 2;
		leftForeArmVertexTextureCoordBuffer.numItems = 4;

		leftForeArmVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, leftForeArmVertexIndexBuffer);
		var leftForeArmVertexIndices = [
			0, 1, 2,      0, 2, 3 // Front Face
		];
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(leftForeArmVertexIndices), gl.STATIC_DRAW);
		leftForeArmVertexIndexBuffer.itemSize = 1;
		leftForeArmVertexIndexBuffer.numItems = 6;
	}

	var leftUpperArmVertexPositionBuffer;
	var leftUpperArmVertexTextureCoordBuffer;
	var leftUpperArmVertexIndexBuffer;

	function initLeftUpperArmBuffers() {
		leftUpperArmVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, leftUpperArmVertexPositionBuffer);

		//x, y, z points (i. e., vertices)
		/*
			x1, y1, z1
			x2, y2, z2
			x3, y3, z3
			x4, y4, z4
		*/
		vertices = [
			// Front face
			-.08, -.2,  .09,
			 .08, -.2,  .09,
			 .08,  .2,  .09,
			-.08,  .2,  .09
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
		leftUpperArmVertexPositionBuffer.itemSize = 3;
		leftUpperArmVertexPositionBuffer.numItems = 4;

		leftUpperArmVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, leftUpperArmVertexTextureCoordBuffer);
		var textureCoords = [
		  // Front face
		  0.0, 0.0,
		  1.0, 0.0,
		  1.0, 1.0,
		  0.0, 1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoords), gl.STATIC_DRAW);
		leftUpperArmVertexTextureCoordBuffer.itemSize = 2;
		leftUpperArmVertexTextureCoordBuffer.numItems = 4;

		leftUpperArmVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, leftUpperArmVertexIndexBuffer);
		var leftUpperArmVertexIndices = [
			0, 1, 2,      0, 2, 3 // Front Face
		];
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(leftUpperArmVertexIndices), gl.STATIC_DRAW);
		leftUpperArmVertexIndexBuffer.itemSize = 1;
		leftUpperArmVertexIndexBuffer.numItems = 6;
	}


	var rightForeArmVertexPositionBuffer;
	var rightForeArmVertexTextureCoordBuffer;
	var rightForeArmVertexIndexBuffer;

	function initRightForeArmBuffers() {
		rightForeArmVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, rightForeArmVertexPositionBuffer);

		//x, y, z points (i. e., vertices)
		/*
			x1, y1, z1
			x2, y2, z2
			x3, y3, z3
			x4, y4, z4
		*/
		vertices = [
			// Front face
			-.08, -.2,  .09,
			 .08, -.2,  .09,
			 .08,  .2,  .09,
			-.08,  .2,  .09
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
		rightForeArmVertexPositionBuffer.itemSize = 3;
		rightForeArmVertexPositionBuffer.numItems = 4;

		rightForeArmVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, rightForeArmVertexTextureCoordBuffer);
		var textureCoords = [
		  // Front face
		  0.0, 0.0,
		  1.0, 0.0,
		  1.0, 1.0,
		  0.0, 1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoords), gl.STATIC_DRAW);
		rightForeArmVertexTextureCoordBuffer.itemSize = 2;
		rightForeArmVertexTextureCoordBuffer.numItems = 4;

		rightForeArmVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, rightForeArmVertexIndexBuffer);
		var rightForeArmVertexIndices = [
			0, 1, 2,      0, 2, 3 // Front Face
		];
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(rightForeArmVertexIndices), gl.STATIC_DRAW);
		rightForeArmVertexIndexBuffer.itemSize = 1;
		rightForeArmVertexIndexBuffer.numItems = 6;
	}

	var rightUpperArmVertexPositionBuffer;
	var rightUpperArmVertexTextureCoordBuffer;
	var rightUpperArmVertexIndexBuffer;

	function initRightUpperArmBuffers() {
		rightUpperArmVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, rightUpperArmVertexPositionBuffer);

		//x, y, z points (i. e., vertices)
		/*
			x1, y1, z1
			x2, y2, z2
			x3, y3, z3
			x4, y4, z4
		*/
		vertices = [
			// Front face
			-.08, -.2,  .09,
			 .08, -.2,  .09,
			 .08,  .2,  .09,
			-.08,  .2,  .09
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
		rightUpperArmVertexPositionBuffer.itemSize = 3;
		rightUpperArmVertexPositionBuffer.numItems = 4;

		rightUpperArmVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, rightUpperArmVertexTextureCoordBuffer);
		var textureCoords = [
		  // Front face
		  0.0, 0.0,
		  1.0, 0.0,
		  1.0, 1.0,
		  0.0, 1.0
		];
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoords), gl.STATIC_DRAW);
		rightUpperArmVertexTextureCoordBuffer.itemSize = 2;
		rightUpperArmVertexTextureCoordBuffer.numItems = 4;

		rightUpperArmVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, rightUpperArmVertexIndexBuffer);
		var rightUpperArmVertexIndices = [
			0, 1, 2,      0, 2, 3 // Front Face
		];
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(rightUpperArmVertexIndices), gl.STATIC_DRAW);
		rightUpperArmVertexIndexBuffer.itemSize = 1;
		rightUpperArmVertexIndexBuffer.numItems = 6;
	}

	/*

		This will draw the left eye...

	*/

	var leftEyeVertexPositionBuffer;
	var leftEyeVertexNormalBuffer;
	var leftEyeVertexTextureCoordBuffer;
	var leftEyeVertexIndexBuffer;

	function initLeftEyeBuffers() {

		//Some array object type variables...
		var radius = .125;
		var vertexPositionData = [];
		var textureCoordData = [];
		var indexData = [];

		//Let's pass values in by reference...
		generateSphere(vertexPositionData, textureCoordData, indexData, radius);

		//Set-up the Left Eye Vertex Texture Coordinate Buffer...
		leftEyeVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, leftEyeVertexTextureCoordBuffer);
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoordData), gl.STATIC_DRAW);
		leftEyeVertexTextureCoordBuffer.itemSize = 2;
		leftEyeVertexTextureCoordBuffer.numItems = textureCoordData.length / 2;

		//Set-up the Left Eye Vertex Position Buffer...
		leftEyeVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, leftEyeVertexPositionBuffer);
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexPositionData), gl.STATIC_DRAW);
		leftEyeVertexPositionBuffer.itemSize = 3;
		leftEyeVertexPositionBuffer.numItems = vertexPositionData.length / 3;

		//Set-up the Left Eye Vertex Index Buffer...
		leftEyeVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, leftEyeVertexIndexBuffer);
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indexData), gl.STATIC_DRAW);
		leftEyeVertexIndexBuffer.itemSize = 1;
		leftEyeVertexIndexBuffer.numItems = indexData.length;
	}

	/*

		This will draw the right eye...

	*/

	var rightEyeVertexPositionBuffer;
	var rightEyeVertexNormalBuffer;
	var rightEyeVertexTextureCoordBuffer;
	var rightEyeVertexIndexBuffer;

	function initRightEyeBuffers() {
		
		//Some array object type variables...
		var radius = .125;
		var vertexPositionData = [];
		var textureCoordData = [];
		var indexData = [];

		//Let's pass values in by reference...
		generateSphere(vertexPositionData, textureCoordData, indexData, radius);

		//Set-up the Right Eye Vertex Texture Coordinate Buffer...
		rightEyeVertexTextureCoordBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, rightEyeVertexTextureCoordBuffer);
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoordData), gl.STATIC_DRAW);
		rightEyeVertexTextureCoordBuffer.itemSize = 2;
		rightEyeVertexTextureCoordBuffer.numItems = textureCoordData.length / 2;

		//Set-up the Right Eye Vertex Position Buffer...
		rightEyeVertexPositionBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ARRAY_BUFFER, rightEyeVertexPositionBuffer);
		gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexPositionData), gl.STATIC_DRAW);
		rightEyeVertexPositionBuffer.itemSize = 3;
		rightEyeVertexPositionBuffer.numItems = vertexPositionData.length / 3;

		//Set-up the Right Eye Vertex Index Buffer...
		rightEyeVertexIndexBuffer = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, rightEyeVertexIndexBuffer);
		gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indexData), gl.STATIC_DRAW);
		rightEyeVertexIndexBuffer.itemSize = 1;
		rightEyeVertexIndexBuffer.numItems = indexData.length;
	}

	/*

		Reusable function to create a sphere

	*/

	function generateSphere(vertexPositionData, textureCoordData, indexData, radius)
	{
		var latitudeBands = 30;
		var longitudeBands = 30;

		for (var latNumber=0; latNumber <= latitudeBands; latNumber++) {
			var theta = latNumber * Math.PI / latitudeBands;
			var sinTheta = Math.sin(theta);
			var cosTheta = Math.cos(theta);

			for (var longNumber=0; longNumber <= longitudeBands; longNumber++) {
				var phi = longNumber * 2 * Math.PI / longitudeBands;
				var sinPhi = Math.sin(phi);
				var cosPhi = Math.cos(phi);

				var x = cosPhi * sinTheta;
				var y = cosTheta;
				var z = sinPhi * sinTheta;
				var u = 1 - (longNumber / longitudeBands);
				var v = 1 - (latNumber / latitudeBands);

				textureCoordData.push(u);
				textureCoordData.push(v);

				/*
					move the position of the head in the x, y, z dimensions
				*/

				vertexPositionData.push(radius * x);
				vertexPositionData.push(radius * y);
				vertexPositionData.push(radius * z);
			}
		}

		for (var latNumber=0; latNumber < latitudeBands; latNumber++) {
			for (var longNumber=0; longNumber < longitudeBands; longNumber++) {
				var first = (latNumber * (longitudeBands + 1)) + longNumber;
				var second = first + longitudeBands + 1;
				indexData.push(first);
				indexData.push(second);
				indexData.push(first + 1);

				indexData.push(second);
				indexData.push(second + 1);
				indexData.push(first + 1);
			}
		}

	}
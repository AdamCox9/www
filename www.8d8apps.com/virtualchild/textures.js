	/*

		Global Variables for Textures...

	*/

	var headTexture;
	var bodyTexture;
	var cookieTexture;
	var leftForeArmTexture;
	var leftUpperArmTexture;
	var rightForeArmTexture;
	var rightUpperArmTexture;
	var leftEyeTexture;
	var rightEyeTexture;

	/*

		Set up the texture that gets passed into the function...

	*/

	function handleLoadedTexture(texture) {
		gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
		gl.bindTexture(gl.TEXTURE_2D, texture);
		gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, texture.image);
		gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
		gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR_MIPMAP_NEAREST);
		gl.generateMipmap(gl.TEXTURE_2D);
		gl.bindTexture(gl.TEXTURE_2D, null);
	}

	/*
	
		This function initializes each texture and sends them to the handleLoadedTexture function above...

	*/

	function initTextures() {

		//_____This is the texture of the head:

		headTexture = gl.createTexture();
		headTexture.image = new Image();
		headTexture.image.onload = function () {
			handleLoadedTexture(headTexture)
		}
		headTexture.image.src = "http://www.8d8apps.com/virtualchild/images/face-texture.gif";

		//_____This is the texture of the body:

		bodyTexture = gl.createTexture();
		bodyTexture.image = new Image();
		bodyTexture.image.onload = function () {
			handleLoadedTexture(bodyTexture)
		}
		bodyTexture.image.src = "http://www.8d8apps.com/virtualchild/images/virtual-child-body.gif";

		//_____This is the texture of the arm with cookie:

		cookieTexture = gl.createTexture();
		cookieTexture.image = new Image();
		cookieTexture.image.onload = function () {
			handleLoadedTexture(cookieTexture)
		}
		cookieTexture.image.src = "http://www.8d8apps.com/virtualchild/images/arm_with_cookie.gif";

		//_____This is the texture of the cookie for the child to eat:

		giveCookieTexture = gl.createTexture();
		giveCookieTexture.image = new Image();
		giveCookieTexture.image.onload = function () {
			handleLoadedTexture(giveCookieTexture)
		}
		giveCookieTexture.image.src = "http://www.8d8apps.com/virtualchild/images/cookie.gif";

		//_____This is the texture of the left forearm:

		leftForeArmTexture = gl.createTexture();
		leftForeArmTexture.image = new Image();
		leftForeArmTexture.image.onload = function () {
			handleLoadedTexture(leftForeArmTexture)
		}
		leftForeArmTexture.image.src = "http://www.8d8apps.com/virtualchild/images/forearm.gif";

		//_____This is the texture of the left upper arm:

		leftUpperArmTexture = gl.createTexture();
		leftUpperArmTexture.image = new Image();
		leftUpperArmTexture.image.onload = function () {
			handleLoadedTexture(leftUpperArmTexture)
		}
		leftUpperArmTexture.image.src = "http://www.8d8apps.com/virtualchild/images/upperarm.gif";

		//_____This is the texture of the right forearm:

		rightForeArmTexture = gl.createTexture();
		rightForeArmTexture.image = new Image();
		rightForeArmTexture.image.onload = function () {
			handleLoadedTexture(rightForeArmTexture)
		}
		rightForeArmTexture.image.src = "http://www.8d8apps.com/virtualchild/images/forearm.gif";

		//_____This is the texture of the right upper arm:

		rightUpperArmTexture = gl.createTexture();
		rightUpperArmTexture.image = new Image();
		rightUpperArmTexture.image.onload = function () {
			handleLoadedTexture(rightUpperArmTexture)
		}
		rightUpperArmTexture.image.src = "http://www.8d8apps.com/virtualchild/images/upperarm.gif";

		//_____This is the texture of the right eye:
		//To Do: add an eyelid to the top and bottom of the graphic
		rightEyeTexture = gl.createTexture();
		rightEyeTexture.image = new Image();
		rightEyeTexture.image.onload = function () {
			handleLoadedTexture(rightEyeTexture)
		}
		rightEyeTexture.image.src = "http://www.8d8apps.com/virtualchild/images/eyeball.gif";

		//_____This is the texture of the left eye:

		leftEyeTexture = gl.createTexture();
		leftEyeTexture.image = new Image();
		leftEyeTexture.image.onload = function () {
			handleLoadedTexture(leftEyeTexture)
		}
		leftEyeTexture.image.src = "http://www.8d8apps.com/virtualchild/images/eyeball.gif";

	}
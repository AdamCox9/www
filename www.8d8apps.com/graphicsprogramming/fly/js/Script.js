if ( ! Detector.webgl )
	Detector.addGetWebGLMessage();

var radius = 371;
var tilt = 0.41;
var rotationSpeed = 0.02;

var cloudsScale = 1.005;
var moonScale = 0.23;

var MARGIN = 0;
var SCREEN_HEIGHT = window.innerHeight - MARGIN * 2;
var SCREEN_WIDTH = window.innerWidth;

var container, stats;
var camera, controls, scene, sceneCube, renderer;
var geometry, meshPlanet, meshClouds, meshMoon, meshHorse, meshFlamingo, meshMonster;
var dirLight, pointLight, ambientLight;

var d, dPlanet, dMoon, dMoonVec = new THREE.Vector3();

var clock = new THREE.Clock();

function init() {

	container = document.createElement( 'div' );
	document.body.appendChild( container );

	camera = new THREE.PerspectiveCamera( 25, SCREEN_WIDTH / SCREEN_HEIGHT, 50, 1e7 );
	camera.position.z = radius * 5;

	scene = new THREE.Scene();
	scene.fog = new THREE.FogExp2( 0x000000, 0.00000025 );

	controls = new THREE.FlyControls( camera );

	controls.movementSpeed = 1000;
	controls.domElement = container;
	controls.rollSpeed = Math.PI / 24;
	controls.autoForward = false;
	controls.dragToLook = false;

	dirLight = new THREE.DirectionalLight( 0xffffff );
	dirLight.position.set( -1, 0, 1 ).normalize();
	scene.add( dirLight );

	ambientLight = new THREE.AmbientLight( 0x111111 );
	scene.add( ambientLight );

	pointLight = new THREE.PointLight( 0xffffff, 5, 25 );
	pointLight.position.x = 10;
	pointLight.position.y = 10;
	pointLight.position.z = 10;
	scene.add( pointLight );

	var planetTexture = THREE.ImageUtils.loadTexture( "textures/earth_atmos_2048.jpg" );
	var cloudsTexture = THREE.ImageUtils.loadTexture( "textures/earth_clouds_1024.png" );
	var normalTexture = THREE.ImageUtils.loadTexture( "textures/earth_normal_2048.jpg" );
	var specularTexture = THREE.ImageUtils.loadTexture( "textures/earth_specular_2048.jpg" );

	var moonTexture = THREE.ImageUtils.loadTexture( "textures/moon_1024.jpg" );
	var landTexture = THREE.ImageUtils.loadTexture( "textures/Land.png" );

	var shader = THREE.ShaderUtils.lib[ "normal" ];
	var uniforms = THREE.UniformsUtils.clone( shader.uniforms );

	uniforms[ "tNormal" ].value = normalTexture;
	uniforms[ "uNormalScale" ].value.set( 0.85, 0.85 );

	uniforms[ "tDiffuse" ].value = planetTexture;
	uniforms[ "tSpecular" ].value = specularTexture;

	uniforms[ "enableAO" ].value = false;
	uniforms[ "enableDiffuse" ].value = true;
	uniforms[ "enableSpecular" ].value = true;

	uniforms[ "uDiffuseColor" ].value.setHex( 0xffffff );
	uniforms[ "uSpecularColor" ].value.setHex( 0x333333 );
	uniforms[ "uAmbientColor" ].value.setHex( 0x000000 );

	uniforms[ "uShininess" ].value = 15;

	var parameters = {
		fragmentShader: shader.fragmentShader,
		vertexShader: shader.vertexShader,
		uniforms: uniforms,
		lights: true,
		fog: true
	};

	var materialNormalMap = new THREE.ShaderMaterial( parameters );


	// horse

	var horseLoader = new THREE.JSONLoader( true );
	horseLoader.load( "js/model/horse.js", function( geometry ) {

		meshHorse = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial( { color: 0x606060, morphTargets: true } ) );
		meshHorse.scale.set( .1, .1, .1 );
		meshHorse.position.x = 1;
		meshHorse.position.y = 1;
		scene.add( meshHorse );

	} );


	// flamingo

	var flamingoLoader = new THREE.JSONLoader( true );
	flamingoLoader.load( "js/model/flamingo.js", function( geometry ) {

		meshFlamingo = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial( { color: 0x606060, morphTargets: true } ) );
		meshFlamingo.scale.set( .3, .3, .3 );
		meshFlamingo.position.x = 10;
		meshFlamingo.position.y = 20;
		meshFlamingo.position.z = 30;
		scene.add( meshFlamingo );

	} );


	// Monster

	var monsterLoader = new THREE.JSONLoader( true );
	monsterLoader.load( "js/model/monster.js", function( geometry ) {

		meshMonster = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial( { color: 0x606060, morphTargets: true } ) );
		meshMonster.scale.set( .01, .01, .01 );
		meshMonster.position.x = 50;
		scene.add( meshMonster );

	} );


	// planet

	geometry = new THREE.SphereGeometry( radius, 100, 50 );
	geometry.computeTangents();

	meshPlanet = new THREE.Mesh( geometry, materialNormalMap );
	meshPlanet.rotation.y = 0;
	meshPlanet.rotation.z = tilt;
	//scene.add( meshPlanet );

	// clouds

	var materialClouds = new THREE.MeshLambertMaterial( { color: 0xffffff, map: cloudsTexture, transparent: true } );

	meshClouds = new THREE.Mesh( geometry, materialClouds );
	meshClouds.scale.set( cloudsScale, cloudsScale, cloudsScale );
	meshClouds.rotation.z = tilt;
	//scene.add( meshClouds );

	// moon

	var materialMoon = new THREE.MeshPhongMaterial( { color: 0xffffff, map: moonTexture } );

	meshMoon = new THREE.Mesh( geometry, materialMoon );
	meshMoon.position.set( radius * 5, radius * 5, 0 );
	meshMoon.scale.set( moonScale, moonScale, moonScale );
	scene.add( meshMoon );

	// stars

	var i, r = radius, starsGeometry = [ new THREE.Geometry(), new THREE.Geometry() ];

	for ( i = 0; i < 250; i ++ ) {
		var vertex = new THREE.Vector3();
		vertex.x = Math.random() * 2 - 1;
		vertex.y = Math.random() * 2 - 1;
		vertex.z = Math.random() * 2 - 1;
		vertex.multiplyScalar( r );

		starsGeometry[ 0 ].vertices.push( vertex );
	}

	for ( i = 0; i < 1500; i ++ ) {
		var vertex = new THREE.Vector3();
		vertex.x = Math.random() * 2 - 1;
		vertex.y = Math.random() * 2 - 1;
		vertex.z = Math.random() * 2 - 1;
		vertex.multiplyScalar( r );

		starsGeometry[ 1 ].vertices.push( vertex );
	}

	var stars;
	var starsMaterials = [
		new THREE.ParticleBasicMaterial( { color: 0x555555, size: 1, sizeAttenuation: false } ),
		new THREE.ParticleBasicMaterial( { color: 0x555555, size: 2, sizeAttenuation: false } ),
		new THREE.ParticleBasicMaterial( { color: 0x333333, size: 3, sizeAttenuation: false } ),
		new THREE.ParticleBasicMaterial( { color: 0x3a3a3a, size: 2, sizeAttenuation: false } ),
		new THREE.ParticleBasicMaterial( { color: 0x1a1a1a, size: 2, sizeAttenuation: false } ),
		new THREE.ParticleBasicMaterial( { color: 0x1a1a1a, size: 4, sizeAttenuation: false } )
	];

	for ( i = 10; i < 30; i ++ ) {
		stars = new THREE.ParticleSystem( starsGeometry[ i % 2 ], starsMaterials[ i % 6 ] );

		stars.rotation.x = Math.random() * 6;
		stars.rotation.y = Math.random() * 6;
		stars.rotation.z = Math.random() * 6;

		s = i * 10;
		stars.scale.set( s, s, s );

		stars.matrixAutoUpdate = false;
		stars.updateMatrix();

		scene.add( stars );
	}

	// plane
	var materialLand = new THREE.MeshPhongMaterial( { color: 0xffffff, map: landTexture } );

	var plane = new THREE.Mesh(new THREE.PlaneGeometry(1024, 1024), materialLand);
	plane.overdraw = true;
	plane.rotation.x = 250;
	scene.add(plane);

	renderer = new THREE.WebGLRenderer( { clearColor: 0x000000, clearAlpha: 1 } );
	renderer.setSize( SCREEN_WIDTH, SCREEN_HEIGHT );
	renderer.sortObjects = false;

	renderer.autoClear = false;

	container.appendChild( renderer.domElement );

	stats = new Stats();
	stats.domElement.style.position = 'absolute';
	stats.domElement.style.top = '0px';
	stats.domElement.style.zIndex = 100;
	container.appendChild( stats.domElement );

	window.addEventListener( 'resize', onWindowResize, false );

	// postprocessing

	var renderModel = new THREE.RenderPass( scene, camera );
	var effectFilm = new THREE.FilmPass( 0.35, 0.75, 2048, false );

	effectFilm.renderToScreen = true;

	composer = new THREE.EffectComposer( renderer );

	composer.addPass( renderModel );
	composer.addPass( effectFilm );

};

function onWindowResize( event ) {

	SCREEN_HEIGHT = window.innerHeight;
	SCREEN_WIDTH = window.innerWidth;

	renderer.setSize( SCREEN_WIDTH, SCREEN_HEIGHT );

	camera.aspect = SCREEN_WIDTH / SCREEN_HEIGHT;
	camera.updateProjectionMatrix();

	composer.reset();

};

function animate() {

	requestAnimationFrame( animate );

	render();
	stats.update();

};

      var radius = 100;
      var theta = 0;

      var duration = 1000;
      var keyframes = 15, interpolation = duration / keyframes;
      var lastKeyframe = 0, currentKeyframe = 0;

function render() {

	// rotate the planet and clouds

	var delta = clock.getDelta();

	meshHorse.position.y = meshHorse.position.x + .05;
	meshFlamingo.position.x = meshFlamingo.position.z + .025;

	meshPlanet.rotation.y += rotationSpeed * delta;
	meshClouds.rotation.y += 1.25 * rotationSpeed * delta;


	if ( pointLight.position.x > 512) {
		pointLight.position.x = -512;
	} else {
		pointLight.position.x = pointLight.position.x + 1;
	}
	// slow down as we approach the surface

	dPlanet = camera.position.length();

	dMoonVec.sub( camera.position, meshMoon.position );
	dMoon = dMoonVec.length();

	if ( dMoon < dPlanet ) {

	d = ( dMoon - radius * moonScale * 1.01 );

	} else {

	d = ( dPlanet - radius * 1.01 );

	}

	if ( meshHorse ) {

          // Alternate morph targets

		var time = Date.now() % duration;

		var keyframe = Math.floor( time / interpolation );

		if ( keyframe != currentKeyframe ) {

			meshHorse.morphTargetInfluences[ lastKeyframe ] = 0;
			meshHorse.morphTargetInfluences[ currentKeyframe ] = 1;
			meshHorse.morphTargetInfluences[ keyframe ] = 0;

			lastKeyframe = currentKeyframe;
			currentKeyframe = keyframe;

			// console.log( mesh.morphTargetInfluences );

		}

		meshHorse.morphTargetInfluences[ keyframe ] = ( time % interpolation ) / interpolation;
		meshHorse.morphTargetInfluences[ lastKeyframe ] = 1 - meshHorse.morphTargetInfluences[ keyframe ];

    }
        

	controls.movementSpeed = 0.33 * d;
	controls.update( delta );

	renderer.clear();
	composer.render( delta );

};
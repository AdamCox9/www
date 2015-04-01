	var audFile = new Audio("/graphicsprogramming/version2/js/horseFile.wav"); // buffers automatically when created

	/*
		Send in the type that should be played.
		The sound file will be preloaded so there is no delay
	*/

	function PlayAudio()
	{
		audFile.play();
	}

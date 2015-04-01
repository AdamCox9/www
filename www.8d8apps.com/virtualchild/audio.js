	/*
		Some preloaded global variables for the Audio
	*/

	var praise = new Audio("audio/meow.wav"); // buffers automatically when created
	var eatcookie = new Audio("audio/eatcookie.wav"); // buffers automatically when created
	var ethanlookatme = new Audio("audio/ethan-look-at-me.wav"); // buffers automatically when created
	var goodjobethan = new Audio("audio/goodjobethan.wav"); // buffers automatically when created

	/*
		Send in the type that should be played.
		The sound file will be preloaded so there is no delay
	*/

	function PlayAudio(type)
	{
		if( type == 'praise' ) {
			praise.play();
		} else if( type == 'eatcookie' ) {
			eatcookie.play();
		} else if( type == 'ethanlookatme' ) {
			ethanlookatme.play();
		} else if( type == 'goodjobethan' ) {
			goodjobethan.play();
		}
	}

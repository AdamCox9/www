/*
	Some preloaded global variables
*/
var praise = new Audio("../audio/meow.wav"); // buffers automatically when created
var c; //this will be the canvas...it gets instantiated when it is presented...
var ctx;

/*
	This is the ready function for jQuery so that we can use all the convenient
	functions that jQuery has to offer...
*/
$(document).ready(function() {

	/*
		Let's create the audio files now so that they are buffered:
	*/

	LetterTest();
})



/*
	This test will mimic the test that is currently on the existing ABA training application
	It will mimic a virtual presenter presenting two letter cards to a virtual child which
	will decide if they are the same or not.
*/
function LetterTest()
{
	$("#content").html("<h2>Letter Test</h2>" + 
						"<div>" +
							"<canvas id='canvas' width='500' height='300'>Please Upgrade Your Browser!</canvas>" + 
							"<a href='#' onclick=\"PlayAudio('praise');\">Play Sound</a>  | " +
							"<a href='#' onclick=\"Mouth();\">Mouth</a>  | " +
							"<a href='#' onclick=\"Eyes();\">Eyes</a>" +
						"</div>");


	//Let's set the global canvas variable and get the 2D context...
	c = document.getElementById("canvas");
	ctx=c.getContext("2d");

	DrawFace();
}

var y = 0;
function Eyes()
{

	if( y % 2 == 0 ) {
		ctx.strokeStyle="#00FF00";
	} else {
		ctx.strokeStyle="#FF0000";
	}

	//(xpos, ypos, radius, ?, ?, ?)

	ctx.beginPath();  
	ctx.moveTo(65,65);  
	ctx.arc(60,65,5,0,Math.PI*2,true);  // Left eye  
	ctx.moveTo(95,65);  
	ctx.arc(90,65,5,0,Math.PI*2,true);  // Right eye  
	ctx.stroke();  
	
	y++;

	setTimeout( "Eyes()", 1000);
}

var x = 0;
function Mouth()
{
	var x1, x2, y1, y2;

	ctx.beginPath();  
	ctx.clearRect ( 0 , 0, 300, 300 );
	ctx.stroke();

	DrawFace();
	ctx.beginPath();
	
	//Let's move the top lips up and down...
	if( x % 2 == 0 ) {
		x1 = 40;
		y = 75;
		x2 = 110;
	} else {
		x1 = 45;
		y = 90;
		x2 = 105;
	}


	
	ctx.moveTo(x1,y);  
    ctx.lineTo(x2,y);   // Mouth (clockwise)  

	ctx.stroke();  

	x++;

	setTimeout( "Mouth()", 1000);

}

/*
	Let's draw a shape for now
*/
function DrawFace()
{

	//(xpos, ypos, radius, ?, ?, ?)

    ctx.beginPath();  
    ctx.arc(75,75,50,0,Math.PI*2,true); // Outer circle  
    ctx.moveTo(110,75);  
    ctx.arc(75,75,35,0,Math.PI,false);   // Mouth (clockwise)  
    ctx.moveTo(65,65);  
    ctx.arc(60,65,5,0,Math.PI*2,true);  // Left eye  
    ctx.moveTo(95,65);  
    ctx.arc(90,65,5,0,Math.PI*2,true);  // Right eye  
    ctx.stroke();  


}


/*
	Send in the type that should be played.
	The sound file will be preloaded so there is no delay
*/
function PlayAudio(type)
{
	if( type == 'praise' ) {
		praise.play();
	}
}
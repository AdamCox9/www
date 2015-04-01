//
// GLTUT.cpp : Defines the entry point for the console application.
//
// opengl32.lib;glu32.lib;glut32.lib;glew32.lib;

#include "stdafx.h"

GLdouble eyex=0.0, eyey=0.0, eyez=5.0, centerx=0.0, centery=0.0, centerz=0.0, upx=0.0, upy=1.0, upz=0.0;

bool centerx_trigger_plus = false, centerx_trigger_minus = false, centery_trigger_plus = false, centery_trigger_minus = false, eyez_trigger_plus = false, eyez_trigger_minus = false;

GLuint abuffer;

void init(void) 
{
	glClearColor (0.0, 0.0, 0.0, 0.0);
	glShadeModel (GL_FLAT);
	glViewport (0, 0, 510, 510); 
	glMatrixMode (GL_PROJECTION);
	gluPerspective(30.0, 1.0, 1.0, 50.0);
	glMatrixMode (GL_MODELVIEW);
}

void display(void)
{
	glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);	//Clear the display
	glColor3f (1.0, 1.0, 1.0);		//Black
	glLoadIdentity();				//clear the matrix

	//viewing transformation
	gluLookAt (eyex, eyey, eyez, centerx, centery, centerz, upx, upy, upz);

	//modeling transformation
	//glScalef(1.0, 2.0, 1.0); 
	//glRotatef(5, 0.0, 1.0, 0.0);
	//glTranslatef(1.0,0.0,1.0);


	// Front side - this is projected forward and it is larger than the rest of the sides
	// It is almost like a flash light :)
	glBegin(GL_POLYGON); 
		glColor3f( 1.0, 0.0, 0.0 );     glVertex3f(  0.5, -0.5, -0.5 );      // P1 is red
		glColor3f( 1.0, 0.0, 1.0 );     glVertex3f(  0.5,  0.5, -0.5 );      // P2 is green
		glColor3f( 0.0, 0.0, 1.0 );     glVertex3f( -0.5,  0.5, -0.5 );      // P3 is blue
		glColor3f( 0.0, 1.0, 0.0 );     glVertex3f( -0.5, -0.5, -0.5 );      // P4 is purple
	glEnd();

	// Back side
	glBegin(GL_POLYGON);
		// Color
		glColor3f(  1.0,  1.0,  1.0 );
		// Back Side Vertices
		glVertex3f(  0.1, -0.1, 0.1 );
		glVertex3f(  0.1,  0.1, 0.1 );
		glVertex3f( -0.1,  0.1, 0.1 );
		glVertex3f( -0.1, -0.1, 0.1 );
	glEnd();
 
	// Right side
	glBegin(GL_POLYGON);
		// Purple
		glColor3f(  1.0,  0.0,  1.0 );
		// Right Side Vertices
		glVertex3f( 0.1, -0.1, -0.1 );
		glVertex3f( 0.1,  0.1, -0.1 );
		glVertex3f( 0.1,  0.1,  0.1 );
		glVertex3f( 0.1, -0.1,  0.1 );
	glEnd();
 
	// Left side
	glBegin(GL_POLYGON);
		// Green
		glColor3f(   0.0,  1.0,  0.0 );
		// Left Side Vertices
		glVertex3f( -0.1, -0.1,  0.1 );
		glVertex3f( -0.1,  0.1,  0.1 );
		glVertex3f( -0.1,  0.1, -0.1 );
		glVertex3f( -0.1, -0.1, -0.1 );
	glEnd();
 
	// Top side
	glBegin(GL_POLYGON);
		// Blue
		glColor3f(   0.0,  0.0,  1.0 );
		// Top Side Vertices
		glVertex3f(  0.1,  0.1,  0.1 );
		glVertex3f(  0.1,  0.1, -0.1 );
		glVertex3f( -0.1,  0.1, -0.1 );
		glVertex3f( -0.1,  0.1,  0.1 );
	glEnd();
 
	// Bottom side
	glBegin(GL_POLYGON);
		// Red
		glColor3f(   1.0,  0.0,  0.0 );
		// Bottom Side Vertices
		glVertex3f(  0.1, -0.1, -0.1 );
		glVertex3f(  0.1, -0.1,  0.1 );
		glVertex3f( -0.1, -0.1,  0.1 );
		glVertex3f( -0.1, -0.1, -0.1 );
	glEnd();

	vec3 points[3];
	points[0] = (0.0, 1.0, 2.0);
	points[0] = (2.0, 1.0, 0.0);
	points[0] = (1.0, 1.0, 0.0);

	//glGenBuffers(1, &abuffer);
	//glBindBuffer(GL_ARRAY_BUFFER, abuffer);
	//glBufferData(GL_ARRAY_BUFFER, sizeof(points), points, GL_STATIC_DRAW);


	//Flush then swap buffers:
	glFlush ();
	glutSwapBuffers();
}

void mouse(int button, int state, int x, int y)
{
	//170, 340, 510

/*
	centerx_trigger_plus = false;
	centerx_trigger_minus = false;
	centery_trigger_plus = false;
	centery_trigger_minus = false;
	eyez_trigger_plus = false;
	eyez_trigger_minus = false;
*/

	//Top left sixtant:
	if( x < 170 && y < 255 ) {
		centerx_trigger_plus = true;
		centerx_trigger_minus = false;
	}
	
	//Bottom left sixtant
	if( x > 340 && y > 255 ) {
		centery_trigger_plus = true;
		centery_trigger_minus = false;
	}
	
	//Bottom left sixtant:
	if( x < 170 && y > 255 ) {
		centery_trigger_minus = true;
		centery_trigger_plus = false;
	}
	
	//Bottom right sixtant
	if( x > 340 && y < 255 ) {
		centerx_trigger_minus = true;
		centerx_trigger_plus = false;
	}
	
	//Top middle sixtant
	if( x > 170 && x < 340 && y < 255 ) {
		eyez_trigger_plus = true;
		eyez_trigger_minus = false;
	}
	
	//Bottom middle sixtant
	if( x > 170 && x < 340 && y > 255 ) {
		eyez_trigger_minus = true;
		eyez_trigger_plus = false;
	}
	
}

void idle()
{
	if( centerx_trigger_plus )
		centerx += 0.0001;
	if( centerx_trigger_minus )
		centerx -= 0.0001;
	if( centery_trigger_plus )
		centery += 0.0001;
	if( centery_trigger_minus )
		centery -= 0.0001;
	if( eyez_trigger_plus )
		eyez += 0.0005;
	if( eyez_trigger_minus )
		eyez -= 0.0005;

	glutPostRedisplay();
}

void keyboard( unsigned char key, int x_c, int y_c )
{

	switch (key) {
	case 'z':

		centerx_trigger_plus = false;
		centerx_trigger_minus = false;
		centery_trigger_plus = false;
		centery_trigger_minus = false;
		eyez_trigger_plus = false;
		eyez_trigger_minus = false;

		eyex=0.0, eyey=0.0, eyez=5.0, centerx=0.0, centery=0.0, centerz=0.0, upx=0.0, upy=1.0, upz=0.0;

		glutPostRedisplay();

		break;
	}
}

int main(int argc, char** argv)
{

	glutInit(&argc, argv);
	glutInitDisplayMode(GLUT_DOUBLE | GLUT_RGB | GLUT_DEPTH);	//Double Buffer | Red Green Blue

	glutInitWindowSize(510, 510);
	glutInitWindowPosition(200, 200); //where the window will pop up on my screen
	glutCreateWindow(argv[0]);

	GLint GlewInitResult = glewInit();
	if (GLEW_OK != GlewInitResult) 
	{
		printf("ERROR: %s\n",glewGetErrorString(GlewInitResult));
		exit(EXIT_FAILURE);
	}

	init();

	glHint(GL_PERSPECTIVE_CORRECTION_HINT, GL_NICEST);
	glEnable(GL_CULL_FACE);
	glCullFace(GL_BACK);
	glShadeModel(GL_SMOOTH);

	// Enable lighting
	glEnable (GL_DEPTH_TEST);
	glEnable(GL_LIGHTING);
	glEnable(GL_COLOR_MATERIAL);
	glEnable(GL_LIGHT0);
	glEnable(GL_LIGHT1);

	glColorMaterial ( GL_FRONT_AND_BACK, GL_AMBIENT_AND_DIFFUSE );

	vec4 ambient0 = vec4(1.0f, 0.0f, 0.0f, 1.0f);
	vec4 diffuse0 = vec4(1.0f, 0.0f, 0.0f, 1.0f);
	vec4 specular0 = vec4(1.0f, 0.0f, 0.0f, 1.0f);
	vec4 ambient1 = vec4(0.0f, 1.0f, 0.0f, 1.0f);
	vec4 diffuse1 = vec4(0.0f, 1.0f, 0.0f, 1.0f);
	vec4 specular1 = vec4(0.0f, 1.0f, 0.0f, 1.0f);

	// Light #0
	glLightfv(GL_LIGHT0, GL_AMBIENT, ambient0);     // Setup ambient light
	glLightfv(GL_LIGHT0, GL_DIFFUSE, diffuse0);     // Setup diffuse light
	glLightfv(GL_LIGHT0, GL_SPECULAR, specular0);   // Setup specular light
	GLfloat lightpos0[] = {-2.0, -2.0, -2.0, 1.0};
	glLightfv(GL_LIGHT0, GL_POSITION, lightpos0);

	// Light #1
	glLightfv(GL_LIGHT1, GL_AMBIENT, ambient0);     // Setup ambient light
	glLightfv(GL_LIGHT1, GL_DIFFUSE, diffuse0);     // Setup diffuse light
	glLightfv(GL_LIGHT1, GL_SPECULAR, specular0);   // Setup specular light
	GLfloat lightpos1[] = {-4.0, -4.0, -4.0, 1.0};
	glLightfv(GL_LIGHT1, GL_POSITION, lightpos1);


	glutKeyboardFunc(keyboard);	//Let's set the 'z' key to reset the scene
	glutIdleFunc(idle);			//Let's move the object based on last sixtant that was clicked
	glutMouseFunc(mouse);		//Let's handle the click and trigger some action
	glutDisplayFunc(display);	//This gets called when glutPostRedisplay is called

	glutMainLoop();
	return 0;
}
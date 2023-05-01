// Store the json of the animation
var data;

// Keep track if the animation is been played
var playing = 0;

// Javascript interval to repeat a function
var interval;

// Keeps track of the actual phase of the animation that is being played
var actual_phase = 0;

// Store all the ids of the input elements for the creation of the animation descriptor
var input_elements = [ "pixels_number_input", "pattern_input", "phases_number_input", "delay_input", "repetitions_input" ];

// var color_value = [ "phase_number", "pixel_color" ];
var pixel_numbers = [ /*"pixel_number",*/ "from_pixel", "to_pixel" ];

// Checks the input for the animation descriptor
// RETURN:
//	-0: error status code
//	-1: success status code
function check_input_values()
{
	for ( var i = 0; i < input_elements.length; i++ )
	{
		if ( document.getElementById( input_elements[i] ).value <= 0 )
			return 0;

		if ( input_elements[i] == "repetitions_input" )
			if ( document.getElementById( input_elements[i] ).value > 255 )
				return 0;
	}

	return 1;
}

// Checks the input for the pixels color changing
// RETURN:
//	-0: error status code
//	-1: success status code
function check_pixel_input()
{
	// Check that the phase number is correct
	if ( document.getElementById( "phase_number" ).value < 0 || parseInt( document.getElementById( "phase_number" ).value ) >= data[ "descriptor" ][ "phases" ] )
		return 0;
	
	// Check that the color is correct
	if ( document.getElementById( "pixel_color" ).value < 0 || document.getElementById( "pixel_color" ).value > 0xffffff )
		return 0;
	
	// Check that the pixel input to change is in the pixels range
	for ( var i = 0; i < pixel_numbers.length; i++ )
		if ( document.getElementById( pixel_numbers[ i ] ).value < 0 || parseInt( document.getElementById( pixel_numbers[ i ] ).value ) >= data[ "descriptor" ][ "pixels" ] )
			return 0;
	
	// Check that the pixel of start isn't bigger than the end
	if ( document.getElementById( "from_pixel" ).value > document.getElementById( "to_pixel" ).value )
		return 0;
	
	// All was fine
	return 1;
}

// Create the json of a strip
// The json is stored in the global variable
function create_strip()
{
	// Check that the input is correct
	if ( check_input_values() == 0 )
	{
		alert( "Check your Values" );
		return;
	}

	// Store all the values as json
	data = '{ "descriptor": { \
		"pixels": "' + parseInt( document.getElementById( "pixels_number_input" ).value ) + '",\
		"pattern": "' + parseInt( document.getElementById( "pattern_input" ).value ) + '",\
		"phases": "' + parseInt( document.getElementById( "phases_number_input" ).value ) + '",\
		"delay": "' + parseInt( document.getElementById( "delay_input" ).value ) + '",\
		"repetitions": "' + parseInt( document.getElementById( "repetitions_input" ).value ) + '",\
		"name": ""\
	},	"body": [] \
	}';

	data = JSON.parse( data );

	// Store all the strip for the phases
	for ( var i = 0; i < data[ "descriptor" ][ "phases" ]; i++ )
	{
		var phase_strip = "";

		// Create the base phase's strip
		phase_strip = "[";
		for ( var j = 0; j < data[ "descriptor" ][ "pixels" ]; j++ )
		{
			if (  j != 0 )
				phase_strip += ", "
			phase_strip += '[ 0, 0, 0 ]';
		}
		phase_strip += "]";
		phase_strip = JSON.parse( phase_strip );

		data[ "body" ].push( phase_strip );
	}

	// Make number of phase selection
	document.getElementById( "phase_number" ).innerHTML = "";
	for ( var i = 0; i < data[ "descriptor" ][ "phases" ]; i++ )
	{
		document.getElementById( "phase_number" ).innerHTML += "<option value =" + i + ">" + i + "</option>";
	}

	// Write all the leds for the animation and the editor
	document.getElementById( "strip_editor" ).innerHTML = "";
	document.getElementById( "strip_animation" ).innerHTML = "";
	for ( var i = 0; i < data[ "descriptor" ][ "pixels" ]; i++ )
	{
		document.getElementById( "strip_editor" ).innerHTML += "<div id = 'editor_pixel_" + i + "' ></div>";
		document.getElementById( "strip_animation" ).innerHTML += "<div id = 'animation_pixel_" + i + "' ></div>";
	}

	// Set the animation playint interval
	interval = setInterval( play, data[ "descriptor" ][ "delay" ] );
}

// Clear the phase display by the animation playing
// ARGUMENTS ( bool ):
//	-1: clear the phase for the editor strip
//	-0: clear the phase for the animation strip
function clear_phase( editor )
{
	var strip;

	// Check if changing the strip for editor or animation
	if ( editor == 0 )
		strip = "animation";
	else
		strip = "editor";

	// Clear the old strip
	document.getElementById( "strip_animation" ).innerHTML = "";

	// Print all the pixel
	for ( var i = 0; i < data[ "descriptor" ][ "pixels" ]; i++ )
		document.getElementById( "strip_animation" ).innerHTML += "<div class = 'pixel' id = '" + strip + "_pixel_" + i + "'></div>";
}

function display_editor_strip()
{
	// Check that the phase value is correct
	if ( document.getElementById( "phase_number" ).value < 0 || parseInt( document.getElementById( "phase_number" ).value ) >= data[ "descriptor" ][ "phases" ] )
	{
		console.log( "Wrong Phase value" );
		return;
	}

	display_strip( document.getElementById( "phase_number" ).value, 1 );
}

// Change the colors of the animation body
function change_colors()
{
	// Check that the input of the editor is correct
	if ( check_pixel_input() == 0 )
	{
		console.log( "Some values are wrong");
		return 0;
	}
	
	// Get the color
	var rgb = parse_rgb( document.getElementById( "pixel_color" ).value );
	
	// Change the color of the selected pixels
	for ( var i = document.getElementById( "from_pixel" ).value; i <= document.getElementById( "to_pixel" ).value; i++ )
		data[ "body" ][ document.getElementById( "phase_number" ).value ][i] = rgb;
	
	// Change the colors displayed by the editor strip
	display_editor_strip();
}

// Start or stop the playing of the animation
function play_stop()
{
	if ( playing == 1 )
	{
		document.getElementById( "play" ).innerText = "Stop";
		playing = 0;
	}
	else
	{
		document.getElementById( "play" ).innerText = "Play";
		playing = 1;
	}
}

// Display the strip of the animation
// ARGUMENTS ( bool ):
//	-1: clear the phase for the editor strip
//	-0: clear the phase for the animation strip
function display_strip( phase_number, editor )
{
	var strip;

	// Check if changing the strip for editor or animation
	if ( editor == 0 )
		strip = "animation";
	else
		strip = "editor";

	// Change each single pixel
	for ( var i = 0; i < data[ "descriptor" ][ "pixels" ]; i++ )
	{
		var style = "width: 30px; height:30px; background-image: radial-gradient(rgba("
					+ data[ "body" ][phase_number][i][0] + ", " + data[ "body" ][phase_number][i][1] + ", " + data[ "body" ][phase_number][i][2]
					+ ", 0.6 ), rgba(" + data[ "body" ][phase_number][i][0] + ", " + data[ "body" ][phase_number][i][1] + ", " + data[ "body" ][phase_number][i][2] + ", 0.1)), url( 'images/pixel.png' );";
		document.getElementById( strip + "_pixel_" + i ).style = style;
	}
}

// Play the animation
function play()
{
	// Check if playing
	if ( playing == 1 )
	{
		// Display the strip
		display_strip( actual_phase, 0 );

		// Encrease to the next phase
		actual_phase = ( actual_phase + 1 ) % data[ "descriptor" ][ "phases" ];
	}
}

// Store the animation in a cookie
function save_animation()
{
	document.getElementById( "animation_body_post" ).value = JSON.stringify( data );
	// document.cookie = "animation" + JSON.stringify( data );
}

/*UTILITY*/

// Parse the hex value of a color to his rgb value
// ARGUMENTS ( int ):
//	-value: the hex value of the color
// RETURN:
//	-[]: the array of the rgb colors [ r, g, b ]
function parse_rgb( value )
{
	var rgb = [];

	// Parse the value
	rgb[0] = parseInt( value.substring( 1, 3 ), 16 );
	rgb[1] = parseInt( value.substring( 3, 5 ), 16 );
	rgb[2] = parseInt( value.substring( 5, 7 ), 16 );

	return rgb;
}

// style=\"background-image: radial-gradient(rgb(255, 0, 255), rgba(255, 0, 255, 0.6), rgba(255, 0, 255, 0.1)), url(\"images/pixel.png\");\"

// <div class = "pixel" style='background-image: radial-gradient(rgb(255, 0, 255), rgba(255, 0, 255, 0.6), rgba(255, 0, 255, 0.1)), url("images/pixel.png");' ></div>
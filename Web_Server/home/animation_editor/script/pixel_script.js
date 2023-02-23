var data;
var playing = 0;
const interval = setInterval( play, 1000 );

var input_elements = [ "pixels_number_input", "pattern_input", "phases_number_input", "delay_input", "repetitions_input" ];

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

function create_strip()
{
	if ( check_input_values() == 0 )
	{
		alert( "Check your Values" );
		return;
	}

	// Store all the values as json
	data = '{ "descriptor": { \
		"pixels": "' + document.getElementById( "pixels_number_input" ).value + '",\
		"pattern": "' + document.getElementById( "pattern_input" ).value + '",\
		"phases": "' + document.getElementById( "phases_number_input" ).value + '",\
		"delay": "' + document.getElementById( "delay_input" ).value + '"\
	},	"body": [] \
	}';

	data = JSON.parse( data );

	var phase_strip = "";

	// Create the base phase's strip
	phase_strip = "[";
	for ( var i = 0; i < data["descriptor"]["pixels"]; i++ )
	{
		if ( i != 0 )
			phase_strip += ", "
		phase_strip += '[ "255", "0", "0" ]';
	}
	phase_strip += "]";
	phase_strip = JSON.parse( phase_strip );

	// Store all the strip for the phases
	for ( var i = 0; i < data["descriptor"]["phases"]; i++ )
	{
		data[ "body" ][i] = phase_strip;
	}

	// Make number of phase selection
	document.getElementById( "phase_number" ).innerHTML = "";
	for ( var i = 0; i < data["descriptor"]["phases"]; i++ )
	{
		document.getElementById( "phase_number" ).innerHTML += "<option value =" + i + ">" + i + "</option>";
	}
}

function change_phase()
{
	// Clear the old strip
	document.getElementById( "strip" ).innerHTML = "";

	// Print all the pixel
	for ( var i = 0; i < data[ "descriptor" ][ "pixels" ]; i++ )
	{
		document.getElementById( "strip" ).innerHTML += "<div class = 'pixel' id = 'pixel_" + i + "'></div>";
	}
}

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

function play()
{
	if ( playing == 1 )
	{
		var style = "background-image: radial-gradient(rgba(255, 0, 255, 0.6), rgba(255, 0, 0, 0.1)), url(\"images/pixel.png\");";
		for ( var i = 0; i < data[ "descriptor" ][ "pixels" ]; i++ )
		{
			document.getElementById( "pixel_" + i ).style = style;
		}
	}
}

// style=\"background-image: radial-gradient(rgb(255, 0, 255), rgba(255, 0, 255, 0.6), rgba(255, 0, 255, 0.1)), url(\"images/pixel.png\");\"

// <div class = "pixel" style='background-image: radial-gradient(rgb(255, 0, 255), rgba(255, 0, 255, 0.6), rgba(255, 0, 255, 0.1)), url("images/pixel.png");' ></div>
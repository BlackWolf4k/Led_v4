// Array where used elements sign if the are used ( 1 ) or not ( 0 )
// Used to check if signup button can be enabled
used_elements = { "username" : 0, "email" : 0 };

function check_username( name )
{
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function()
	{
		if ( this.readyState == 4 && this.status == 200 )
		{
			if ( this.responseText == "false" )
			{
				if ( document.getElementById( "username" ).classList.contains( "is-invalid" ) )
					document.getElementById( "username" ).classList.remove( "is-invalid" );
				document.getElementById( "username" ).classList.add( "is-valid" );

				// Store that the username is unused
				used_elements[ "username" ] = 0;

				// Enable the signup button
				// Do not enable if some other var is wrong
				for ( key in used_elements )
				{
					console.log( used_elements[ key ] );
					if ( used_elements[ key ] == 1 )
						return;
				}
				document.getElementById( "signup_button" ).disabled = false;
			}
			else if ( this.responseText == "true" )
			{
				if ( document.getElementById( "username" ).classList.contains( "is-valid" ) )
					document.getElementById( "username" ).classList.remove( "is-valid" );
				document.getElementById( "username" ).classList.add( "is-invalid" );

				// Store that the username is used
				used_elements[ "username" ] = 1;

				// Disable the signup button
				document.getElementById( "signup_button" ).disabled = true;
			}
		}
	};

	// Send the request
	xmlhttp.open( "GET", "/api/using.php?is_username_already_in_use=" + name.value, true );
	xmlhttp.send();
}

function check_email( email )
{
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function()
	{
		if ( this.readyState == 4 && this.status == 200 )
		{
			if ( this.responseText == "false" )
			{
				if ( document.getElementById( "email" ).classList.contains( "is-invalid" ) )
					document.getElementById( "email" ).classList.remove( "is-invalid" );
				document.getElementById( "email" ).classList.add( "is-valid" );

				// Store that the username is unused
				used_elements[ "email" ] = 0;

				// Enable the signup button
				// Do not enable if some other var is wrong
				for ( key in used_elements )
				{
					console.log( key + used_elements[ key ] );
					if ( used_elements[ key ] == 1 )
						return;
				}
				document.getElementById( "signup_button" ).disabled = false;
			}
			else if ( this.responseText == "true" )
			{
				if ( document.getElementById( "email" ).classList.contains( "is-valid" ) )
					document.getElementById( "email" ).classList.remove( "is-valid" );
				document.getElementById( "email" ).classList.add( "is-invalid" );

				// Store that the username is used
				used_elements[ "email" ] = 1;

				// Disable the signup button
				document.getElementById( "signup_button" ).disabled = true;
			}
		}
	};

	// Send the request
	xmlhttp.open( "GET", "/api/using.php?is_email_already_in_use=" + email.value, true );
	xmlhttp.send();
}
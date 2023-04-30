function check_password_same( password )
{
	if ( document.getElementById( "password_" ).value == document.getElementById( "password_confirm" ).value && ( document.getElementById( "password_" ).value != "" && document.getElementById( "password_confirm" ).value != "" ) )
	{
		// Remove old class
		/*if ( document.getElementById( "password" ).classList.contains( "is-invalid" ) )
			document.getElementById( "password" ).classList.remove( "is-invalid" );*/
		if ( document.getElementById( "password_confirm" ).classList.contains( "is-invalid" ) )
			document.getElementById( "password_confirm" ).classList.remove( "is-invalid" );

		// Add new class
		//document.getElementById( "password" ).classList.add( "is-valid" );
		document.getElementById( "password_confirm" ).classList.add( "is-valid" );

		// Store that the username is unused
		used_elements[ "password_" ] = 0;

		// Enable the signup button
		// Do not enable if some other var is wrong
		for ( key in used_elements )
		{
			console.log( used_elements[ key ] );
			if ( used_elements[ key ] == 1 )
				return;
		}
		// document.getElementById( "signup_button" ).disabled = false;
	}
	else
	{
		// Remove old class
		/*if ( document.getElementById( "password" ).classList.contains( "is-valid" ) )
			document.getElementById( "password" ).classList.remove( "is-valid" );*/
		if ( document.getElementById( "password_confirm" ).classList.contains( "is-valid" ) )
			document.getElementById( "password_confirm" ).classList.remove( "is-valid" );

		// Add new class
		//document.getElementById( "password" ).classList.add( "is-invalid" );
		document.getElementById( "password_confirm" ).classList.add( "is-invalid" );

		// Store that the username is used
		used_elements[ "password_" ] = 1;

		// Disable the signup button
		// document.getElementById( "signup_button" ).disabled = true;
	}
}
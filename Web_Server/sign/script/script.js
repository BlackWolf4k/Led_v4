function check_password_same( password )
{
	if ( document.getElementById( "password" ).value == document.getElementById( "password_confirm" ).value )
	{
		// Remove old class
		/*if ( document.getElementById( "password" ).classList.contains( "is-invalid" ) )
			document.getElementById( "password" ).classList.remove( "is-invalid" );*/
		if ( document.getElementById( "password_confirm" ).classList.contains( "is-invalid" ) )
			document.getElementById( "password_confirm" ).classList.remove( "is-invalid" );

		// Add new class
		//document.getElementById( "password" ).classList.add( "is-valid" );
		document.getElementById( "password_confirm" ).classList.add( "is-valid" );
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
	}
}
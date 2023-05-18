<?php
/*RESTFUL START*/

error_reporting( E_ALL );
ini_set( "display_errors", 1 );

include "./restful/handler.php";

// Set the header content type as json
header( "Content-Type:application/json" );

if ( !isset( getallheaders()[ "Authorization" ] ) )
{
	echo '{ "Error": "Not authorized" }';
	die();
}

$GLOBALS[ "auth" ] = getallheaders()[ "Authorization" ];

if ( is_restful_call( $_SERVER[ "REQUEST_URI" ] ) )
{
	handle_resteful_request( $_SERVER[ "REQUEST_URI" ] );
}
/*RESTFUL END*/
?>
import socket

# To tell the other core to play or stop playing an animation
from Animation.animation import play

# To interpretate web pages
from Server import interpreter

# To read pages
from File import read
# To dump content
from File import write
# To update config files
from File import update

# To sync data with the main server
from Services import requests

# To require the animation
from Animation import animation

# To handle json
import json

socket_descriptor = 0

# Initialize the webserver
def init():
	global socket_descriptor

	# Create the socket
	socket_descriptor = socket.socket( socket.AF_INET, socket.SOCK_STREAM )

	# Enable reuse of local address
	socket_descriptor.setsockopt( socket.SOL_SOCKET, socket.SO_REUSEADDR, 1 )

	# Bind the address
	socket_descriptor.bind( ( "0.0.0.0", 80 ) )

	# Start listening for connection
	socket_descriptor.listen( 2 ) # <- TO CHANGE

# THe main function of the server that handles clients' connections
def main():
	global socket_descriptor

	# Keep listening for connections
	while True:
		# Start connection
		try:
			print( "Waiting for a client to connect" )
			# Accept the connection
			client_connection, address = socket_descriptor.accept()
			print( "A Client Connected" )

			# Recive the request
			request = client_connection.recv( 1024 )

			# Analize the request
			analyzed_request = analize_request( request[ "demands" ] )

			# Check if broadcast message
			if ( analyzed_request[ "broadcast" ] == 1 ):
				# Handle the message
				handle_broadcast_message( analyzed_request[ "demands" ] )

				# Close the connection
				client_connection.close()
			else:
				# Get the content of the page
				response = interpreter.interpreter( response_filename )

				# Send html response header
				client_connection.send( "HTTP/1.0 200 OK\r\nContent-type: text/html\r\n\r\n" )

				# Send the response
				client_connection.send( response )

				# Close the connection
				client_connection.close()

		except OSError as error: # Except a error
			# Close the connection with the client
			client_connection.close()
			print( "Error" )

# Decide what to respond to a request
# ARGUMENTS ( string ):
#	-request: the request wich to find a response
# RETURNS ( dict ):
#	-analyzed_request: by now can return two different results:
#		{ "broadcast": 1, "demands": {} }: in this case the request recived by the server is just a message from another board broadcasting server demands
#		{ "broadcast": 0, "filename": "" }: in this casse the request recived is a http request to this server
def analize_request( request ):
	# Check if a broadcast message ( first becouse more common )
	response = is_broacast( request )

	if ( response != False ): # A broadcast message
		return response # Return the message
	
	response = { "broadcast": 0, "filename": {} }

	# Get the request path
	path = get_path( request )

	# Check that the path was interpreted correctly
	if path == 0:
		response[ "filename" ] = "error.html"
		return response # Return error page

	print( path )
	print( str( request ) )

	# Get the parameters of the request
	parameters = get_parameters( path )

	# Check that the request contains parameters
	if ( parameters != {} and parameters != 0 ):

		found_paramters = {}

		# Get all the request parameters that are accepted
		for key in list( parameters.keys() ):
			if key in accepted_parameters: # Check if the paramters in acceptable
				found_paramters[ key ] = parameters[ key ] # Add it to the list of found parameters

		# Do what requested
		# Check that the paramets list is not empty
		if found_paramters != {}:
			# Check that there is a code
			if "code" not in found_paramters:
				# Return error page
				response[ "filename" ] = "error.html"
				return response

			# Check that the code's code exists
			if str( found_paramters[ "code" ] ) not in codes:
				# Return error page
				response[ "filename" ] = "error.html"
				return response

			# Interpretate the code
			response[ "filename" ] = codes[ str( found_paramters[ "code" ] ) ]( parameters )
			return response

	# Check if asking just a page
	# Get all the pages
	pages = list_pages()

	# Check if the request contains a page
	for page in pages:
		if ( page in path ): # Check if page contained
			response[ "filename" ] = page
			return response # Return the page
	
	# No code or page request found so return the index
	response[ "filename" ] = "index.html"
	return response

# Check if a this message is a broadcast message
def is_broacast( request ):
	# Decode the request ( from byte[] to string )
	request = request.decode( "utf-8" )

	# The things that must be in a broadcast message must be: "Asking", "Code" and "Board"
	if ( "Asking" in request and "Code" in request and "Board" in request ):
		# Create what to return
		response = { "broadcast": 1, "demands": {} }

		# Load the server demands
		try: # Some data passed can be not nice for json loading
			response[ "demands" ] = json.loads( request )
		except Exception:
			response[ "demands" ] = json.loads( request.replace( "'", "\"" ) )
		
		# Return the response
		return response

	return False

# List the webpages
# ARGUMENTS ():
# RETURNS ( list ):
#	-pages: the list of the web pages
def list_pages():
	# List the files in the pages directory
	pages = read.list_directory( "/Server/pages" )

	# Return the pages
	return pages

# Returns the path requested in a HTTP request
# ARGUMENTS ( byte[] ):
#	-request: the http request
# RETURNS ( string | int ):
#	-path: the request path
#	-0: error code
def get_path( request ):
	# Decode the request ( from byte[] to string )
	request = request.decode( "utf-8" )

	try:
		# Get the first line of the request ( delimited by '\r\n' )
		request = ( request.split( "\r\n" ) )[ 0 ]

		# Get the path ( delimited by a ' ' at the beginning and at the end )
		path = ( request.split( " " ) )[ 1 ]

		# Return the path
		return path
	except Exception:
		return 0

# Handle a recived broadcast message
# If the board id specified in the request if the same of this board the message will be interpreted, otherwise will be dropped
def handle_broadcast_message( demands ):
	# Read this board configuration
	board_config = read.read_conf_file( "board.json" )

	# Compare the board id
	if ( demands[ "Board" ] == board_config[ "board_id" ] ):
		requests_codes[ int( demands[ "Code" ] ) ]()

# Get the parameters from the request
# ARGUMENTS ( string ):
#	-request: the request
# RETURN ( dict | int ):
#	-parameters: a dictionary of the parameters
#	-0: there is a error in the request
def get_parameters( request ):
	parameters_start = 0

	# Get where the ? starts
	try:
		parameters_start = request.index( "?" ) + 1
	except ValueError as error:
		return 0
	
	# Split the request on the &
	try:
		parameters_list = request[ parameters_start : ].split( "&" )
	except ValueError as error:
		return 0

	parameters = {}

	# Store all the parameters in a dictionary
	for parameter in parameters_list:
		# Split key and value
		key_value = parameter.split( "=" )

		# Store in the dictionary
		try:
			parameters[ key_value[0] ] = key_value[ 1 ]
		except ValueError as error:
			return 0

	# return the parameters
	return parameters

# Interpretate page

# Trasmit a message on the broadcast
# ARGUMENTS ( dict ):
#	-parameters: the parameters of the request
# RETURN ()
def broadcast( parameters ):
	print( "Broadcast" )
	return

# Modifies the board informations
# ARGUMENTS ( dict ):
#	-parameters: the parameters of the request
# RETURN ()
def update_config( parameters ):
	print( "Update" )

	# Check the values passed

	# Update the config file
	changed_keys = update.update_config_file( "board.json", parameters )

	# Sync the new config with the main server
	board_server_send_sync()

	# If he animation has changed require it
	if "actual_animation" in changed_keys:
		requests.board_server_specific_animation()

	# If the default animation has changed require it
	if "remote_animation" in changed_keys:
		animation.set_default_animation( requests.board_server_specific_animation( True ) )

	# Return the index page
	return "index.html"

def update_token( paramters ):
	print( "Update Token" )

	# Check the passed values

	# Update the config file
	update.update_config_file( "board.json", parameters )

	# Return the token changing page
	return "token_page.html"

# Conatins the function that a specific request code has to execute
codes = { "2": broadcast, "7": update_config, "8": update_token }

# Accepted GET parameters
accepted_parameters = [ "board_id", "code" ]
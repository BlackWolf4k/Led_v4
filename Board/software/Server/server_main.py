import socket

# To tell the other core to play or stop playing an animation
from Animation.animation import play

# To read pages
from File import read

# To dump content
from File import write

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

			write.dump_plain( str( request ) )

			# Analize the request
			response_filename = analize_request( request )

			# Read the response file content
			response = read.read_file( "/Server/pages/" + response_filename )

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
# RETURNS ( string ):
#	-filename: the name of the response file
def analize_request( request ):
	# Get the request path
	path = get_path( request )

	# Check that the path was interpreted correctly
	if path == 0:
		return "error.html" # Return the error page

	print( path )

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
				return "error.html"

			# Check that the code's code exists
			if str( found_paramters[ "code" ] ) not in codes:
				# Return error page
				return "error.html"

			# Interpretate the code
			return codes[ str( found_paramters[ "code" ] ) ]( parameters )

	# Check if asking just a page
	# Get all the pages
	pages = list_pages()

	# Check if the request contains a page
	for page in pages:
		if ( page in path ): # Check if page contained
			return page # Return the page
	
	# No code or page request found so return the index
	return "index.html" # Return the index page

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
def update( parameters ):
	print( "Update" )
	# Check the values passed
	return "error.html"

# Conatins the function that a specific request code has to execute
codes = { "2": broadcast, "7": update }

# Accepted GET parameters
accepted_parameters = [ "board_id", "code" ]
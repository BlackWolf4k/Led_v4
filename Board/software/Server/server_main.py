import socket

# To tell the other core to play or stop playing an animation
from Animation.animation import play

# To read pages
from File import read

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
		#try:
			print( "Waiting for a client to connect" )
			# Accept the connection
			client_connection, address = socket_descriptor.accept()
			print( "A Client Connected" )

			# Recive the request
			request = client_connection.recv( 1024 )

			# Analize the request
			response_filename = analize_request( str( request ) )

			# Read the response file content
			response = read.read_file( "/Server/pages/" + response_filename )

			# Send html response header
			client_connection.send( "HTTP/1.0 200 OK\r\nContent-type: text/html\r\n\r\n" )

			# Send the response
			client_connection.send( response )

			# Close the connection
			client_connection.close()

		#except OSError as error: # Except a error
		#	# Close the connection with the client
		#	client_connection.close()
		#	print( "Error" )

# Decide what to respond to a request
# ARGUMENTS ( string ):
#	-request: the request wich to find a response
# RETURNS ( string ):
#	-filename: the name of the response file
def analize_request( request ):
	# Check if the request contains a code
	for key in list( codes.keys() ):
		# Check if the code is contained
		if ( request.find( "code=" + str( key ) ) ):
			# Execute the code's function
			return codes[ key ]()

	# Check if asking just a page
	# Get all the pages
	pages = list_pages()

	# Check if the request contains a page
	for page in pages:
		if ( page in request ): # Check if page contained
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
def broadcast():
	return

# Modifies the board informations
def update():
	# Check the values passed
	return "ok.html"

# Conatins the function that a specific request code has to execute
codes = { "2" : broadcast, "7" : update }
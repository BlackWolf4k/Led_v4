#import urequests
import urequests as urequests

# To boardcast messages
import socket

# To read config file
from File import read

# Just to get the wlan
from Connections import connection

# Convert a dict to a 'get request' string
# { "id": 1, "name": "Joe" } => ?id=1&name="Joe"
# ARGUMENTS ( dict ):
#	-content: the content to convert in 'get request' form
# RETURN ( str ):
#	-str: the converted string
def __dict_to_str( content ):
	# The get requests start with a ?
	get_content = "?"
	
	# Convert each key
	for key in list( content.keys() ):
		get_content += str( key ) + "=" + str( content[ key ] ) + "&"
	
	# Return the converted string
	return get_content

# Send a get request
# ARGUMENTS ( str, dict ):
#	-url: the url where to send the get request
#	-content: the content of the get request
# RETURN ( dict ):
#	-dictionary: containes the response from the server
#	-0: error code
def get( url, content ):
	# Convert the get content into a string
	get_content = __dict_to_str( content )

	# Send the request
	response = urequests.get( url + get_content )

	# Return the result
	return response

# Send a post request
# ARGUMENTS ( str, dict ):
#	-url: the url where to send the post request
#	-content: the content in json format to send
# RETURN ( dict ):
#	-dictionary: contains the response from the server
#	-0: error code
def post( url, content ):
	# Send the request
	response = urequests.post( url, json = content )

	# Return the result
	return response

# Send a message to the whole network
# ARGUMENTS ( any ):
#	-message: the message to send ( can be of any type )
# RETURN
#	-0: error code
#	-1: success code
def broadcast( message ):
	# Create the socket
	socket_descriptor = socket.socket( socket.AF_INET, socket.SOCK_DGRAM )

	# Set socket to broadcast
	# socket_descriptor.setsockopt( socket.SOL_SOCKET, socket.SO_BROADCAST, 1 ) # --> ( not accepted by micropython )

	# Do broadcasting the hard way
	# Just do it for small groups ( max is 0.0.0.255 )
	# Get the subnet mask relevant byte
	mask_relevant_byte = int( connection.wlan.ifconfig()[ 1 ].split( "." )[ 3 ] )

	# Get the actual address group
	address_group = connection.wlan.ifconfig()[ 0 ].split( "." )[ 0 : 3 ]

	# Broadcast the message
	for i in range( 1, mask_relevant_byte, 1 ):
		# Send the message to a specific address
		socket_descriptor.sendto( message.encode(), ( address_group + str( i ), shared[ "broadcast_port" ] ) )

# Return the url of the web server
# ARGUMENTS ()
# RETURN ( string ):
#	-url: the url of the website
def get_server_url():
	# Get the config about the server
	server_config = read.read_conf_file( "server.json" )

	# Compose the url
	url = server_config[ "protocol"] + server_config[ "dns" ] + ":" + str( server_config[ "port" ] ) + server_config[ "path" ]

	# Return the url
	return url
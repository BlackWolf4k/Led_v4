#import urequests
import urequests as urequests

# To boardcast messages
import socket

# To read config file
from File import read

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
	sockt = socket.socket( AF_INET, SOCK_DGRAM )

	# Set socket to broadcast
	sockt.setsockopt( socket.SOL_SOCKET, socket.SO_BROADCAST, 1 )

	# Send the message in broadcast
	sockt.sendto( message.encode(), ( "255.255.255.255", shared[ "broadcast_port" ] ) )

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
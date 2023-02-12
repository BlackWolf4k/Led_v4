import urequests
import socket

# Send a get request
# ARGUMENTS ( str, dict ):
#	-url: the url where to send the get request
#	-content: the content of the get request
# RETURN ( dict ):
#	-dictionary: containes the response from the server
#	-0: error code
def get( url, content ):
	# Send the request
	response = urequests.get( url, params = content )

	# Check the response
	if response.status_code != 200:
		return 0

	# Return the result
	return response.content

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

	# Check the response
	if response.status_code != 200:
		return 0

	# Return the result
	return response.content

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
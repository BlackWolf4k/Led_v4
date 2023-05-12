# Requestes ( get, post )
from Comunication import comunication

# Get configs
from File import read

# To handle jsons
import json

# Make a get request to the server requiring the next animation
# Code of the request: 0x0001
# ARGUMENTS ( none )
# RETURN:
#	-byte[]: the server response
def board_server_next_animation():
	# Get the board configurations
	board_config = read.read_conf_file( "board.json" )

	# Make the request
	response = comunication.get( comunication.get_server_url(),
					  { "board_id": board_config[ "board_id" ], "code": 0x0001, "token": board_config[ "token" ] } )

	# Check the response
	if response.status_code != 200:
		return 0 # There was an error

	# Return the response content
	return response.content

# Sync the local data to the one of the board
# Code of the request: 0x0003
def board_server_send_sync():
	# Get the board configs
	board_config = read.read_conf_file( "board.json" )

	# Make the request
	response = comunication.get( comunication.get_server_url(),
								 { "board_id": board_config[ "board_id" ], "leds": board_config[ "leds_number" ], "code": 0x0003, "token": board_config[ "token" ] } )
	
	# Check the response
	if response.status_code != 200:
		return 0 # There was an error
	
	# Return the response content
	return response.content

# Require a specif animation to the server
# Code of the request: 0x0005
def board_server_specific_animation( default = False ):
	# Get the board configs
	board_config = read.read_conf_file( "board.json" )

	# Is the animation to get the ofline or the online
	if default:
		default = "remote_animation"
	else:
		default = "actual_animation"

	# Make the request
	response = comunication.get( comunication.get_server_url(),
								 { "board_id": board_config[ "board_id" ], "animation_name": board_config[ default ], "code": 0x0005, "token": board_config[ "token" ] } )
	
	# Check the response
	if response.status_code != 200:
		return 0 # There was an error
	
	# Return the response content
	return response.content

# The board requeires it's configuration to the server
def board_server_get_sync():
	return

# Checks if in a response there are some informations about the server asking something
def check_if_server_asking( response ):
	# Check the response dictionary passed

	# Asking should always be in position 1 of "Content"
	if "Asking" in response.contain[ "Content" ][ 1 ]:
		return True
	return False

def handle_server_hasking( server_demands ):
	# Check the demands dictionary passed

	# Broadcast the message
	comunication.broadcast( json.dumps( server_demands ) )

requests_codes = { 0x0001: board_server_next_animation, 0x0003: board_server_send_sync, 0x0005: board_server_specific_animation, 0x0007: board_server_get_sync }
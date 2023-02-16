# Requestes ( get, post )
from Comunication import comunication

# Get configs
from File import read

# Make a get request to the server requiring the next animation
# ARGUMENTS ( none )
# RETURN:
#	-byte[]: the server response
def board_server():
	# Get the config about the server
	server_config = read.read_conf_file( "server.json" )

	# Get the board configurations
	board_config = read.read_conf_file( "board.json" )

	# Make the request
	response = comunication.get( server_config[ "protocol"] + server_config[ "dns" ] + ":" + str( server_config[ "port" ] ) + server_config[ "path" ],
					  { "board_id": board_config[ "board_id" ], "code": 0x0001 } )

	# Check the response
	if response.status_code != 200:
		return 0 # There was an error

	# Return the response content
	return response.content
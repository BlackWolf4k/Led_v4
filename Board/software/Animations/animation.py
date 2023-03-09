# Decode the animation
import Animations.animation_decode as animation_decode

# Make the get request
from Services import requests

# Ask an animation to the server
# ARGUMENTS ( none )
# RETURN:
#	-0: error code
#	-dict: the animation
def get_animation():
	# Require the new animation
	response = requests.board_server()

	# Check the returned value
	if ( response == b'{}' or response == 0 ):
		# Return an error code
		return 0
	else:
		# Decode the animation from byte array to string
		animation_string = response.decode( "utf-8" )

		# Return the decode animation
		return animation_decode.decode_animation( animation_string )
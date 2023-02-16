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
	if ( response == b'{}' ):
		# Return an error code
		return 0
	else:
		# Return the decode recived content
		return animation_decode.decode_animation( response )
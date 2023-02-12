# Decode the animation
import animation_decode

# Make the get request
from Services import requests

# Ask an animation to the server
# ARGUMENTS ( none )
# RETURN:
#	-0: error code
#	-dict: the animation
def get_animation():
	# Require the new animation
	requests.board_server()

	# Check the returned value
	if ( response.content == b'{}' ):
		# Return an error code
		return 0
	else:
		# Return the decode recived content
		return decode_animation( response.content )
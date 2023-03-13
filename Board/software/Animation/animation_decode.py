# For animation decoding
import json

# Decode an animation
# From string to dictionary in animation format
# PARAMS ( byte[] ):
#	-encoded_animation: byte array containing the animation
# RETURN:
#	-0: error code
#	-dict: decoded animation
def decode_animation( animation ):
	# Convert the animation
	dict_animation = json.loads( animation )
	
	# Return the animation
	return dict_animation
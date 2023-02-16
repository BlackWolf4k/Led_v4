# For structures
from ctypes import *

# ANIMATION DESCRIPTOR
# number_of_lines: 	the number of lines in the file
# line_length:		the length of a single line
# repeat: 			does the animation repeat ( 0 - 254: number of times to repeat, 255: loop )
# delays: 			pointer to the delays matrix
# pattern:			animations with a repeating patter ( 0: none, 1: rainbow )
#animation_descriptor_t = {
#		"number_of_lines" : 0 | INT32,
#		"line_length" : 4 | INT32,
#		"delay" : 8 | UINT8,
#		"repeat" : 9 | UINT8,
#		"pattern" : 10 | UINT8
#}

# Decode an animation
# From byte array to dictionary in animation format
# PARAMS ( byte[] ):
#	-encoded_animation: byte array containing the animation
# RETURN:
#	-0: error code
#	-dict: decoded animation
def decode_animation( animation ):
	return animation
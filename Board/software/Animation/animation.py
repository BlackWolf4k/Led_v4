# Decode the animation
import Animation.animation_decode as animation_decode

# Make the get request
from Services import requests

# To write animation
from File import write

# To read config files
from File import read

# To handle phisical led pins
from machine import Pin

# To sleep on animation playing
import time

# To handle leds
from Led import led

play = 1

# Ask an animation to the server
# ARGUMENTS ( none )
# RETURN:
#	-0: error code
#	-dict: the animation
def get_animation():
	# Require the new animation
	response = requests.board_server_next_animation()

	# Check the returned value
	if ( response == b'{}' or response == 0 ):
		# Return an error code
		return 0
	else:
		# Decode the animation from byte array to string
		animation_string = response.decode( "utf-8" )

		# Return the decode animation
		return animation_decode.decode_animation( animation_string )

# Play the animation passed
# ARGUMENTS ( dict ):
#	-animation: the animation to play
# RETURN ( int ):
#	-0: error code
#	-1: success code
#	-2: interrupted
def play_animation( animation ):
	global play

	# Clear the strip
	led.clear_strip()

	# Change the play global variable
	play = 1

	print( animation )

	# Check that the server isn't interrupting
	while ( play or animation[ "descriptor" ][ "repeat" ] > 0 ):
		# Check that the animation is not a loop
		if ( not ( animation[ "descriptor" ][ "repeat" ] == 255 ) ):
			# Descrease the repetitions
			animation[ "descriptor" ][ "repeat" ] -= 1
		
		# Play all the phases
		for i in range( 0, animation[ "descriptor" ][ "phases" ], 1 ):
			# Play a single phase
			for j in range( 0, animation[ "descriptor" ][ "leds" ], 1 ):
				# Print the colors of the leds
				print( "Phase: " + str( i ) + ", Led: " + str( j ) + ", Color: [" + str( animation[ "body" ][ i ][ j ][ 0 ] ) + ", " + str( animation[ "body" ][ i ][ j ][ 1 ] ) + ", " + str( animation[ "body" ][ i ][ j ][ 2 ] ) + "]" )
				
				# Change phiscal colors
				led.change_led_color( j, animation[ "body" ][ i ][ j ] )
		
			# Display the strip
			led.apply_changes()

			# Turn off the play led
			led.led_off( led.play_led )

			# Delay
			time.sleep( animation[ "descriptor" ][ "delay" ] / 1000 )

			# Turn on the play led
			led.led_on( led.play_led )

	# Playing ended
	# Change the global variable
	play = 0

# Get the default animation from the main server and stores it in local
def set_default_animation( animation ):
	# Store the animation
	write.write_default_animation( animation )
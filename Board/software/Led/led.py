# To handle pixel strip
import neopixel

# For hardware comunication with the pins
from machine import Pin

# To read config files
from File import read

# Store the leds strip
leds_strip = 0

# Initialize the led strip
def init():
	global leds_strip

	# Read the config file of the board
	board_config = read.read_conf_file( "board.json" )

	# Create the strip
	leds_strip = neopixel.NeoPixel( Pin( board_config[ "leds_pin" ], Pin.OUT ), board_config[ "leds_number" ] )

# Change the color of al led
# Does not display the change of the color ( to do that you must call apply_changes() )
# ARGUMENTS ( int, array<byte>[3] ):
#	-led: the number of the led to change colors
#	-color: a 3 byte array containing the new color of the led ( RGB )
# RETURN ( int ):
#	-0: error code
#	-1: success code
def change_led_color( led, color ):
	global leds_strip

	# Check the values
	if ( led < 0 or len( color ) != 3 ): # The values are wrong
		return 0 # Return error code
	
	# Change the colors
	leds_strip[ led ] = color

# Change the color of al led and instanlty apply the changes
# ARGUMENTS ( int, array<byte>[3] ):
#	-led: the number of the led to change colors
#	-color: a 3 byte array containing the new color of the led ( RGB )
# RETURN ( int ):
#	-0: error code
#	-1: success code
def change_and_apply_led_color( led, color ):
	# Change the color of the led
	change_led_color( led, color )

	# Apply the changes
	apply_changes()

# Apply the changes made to the led strip
def apply_changes():
	global leds_strip

	# Apply the changes made to the strip
	leds_strip.write()
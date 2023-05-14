# To handle pixel strip
import neopixel

# For hardware comunication with the pins
from machine import Pin

# To read config files
from File import read

# Store the leds strip
leds_strip = 0

# The "debug" leds
# The idle
idle_led = 0
# The playing
play_led = 0

# Initialize the led strip
def init_strip():
	global leds_strip

	# Read the config file of the board
	board_config = read.read_conf_file( "board.json" )

	# Create the strip
	leds_strip = neopixel.NeoPixel( Pin( board_config[ "leds_pin" ], Pin.OUT ), board_config[ "leds_number" ] )

	# Clear the strip becouse it may be on
	clear_strip()

# Initialize the debug leds
def init_debug():
	global idle_led
	global play_led

	# Read the config file of the board
	board_config = read.read_conf_file( "board.json" )

	# The idle
	idle_led = Pin( board_config[ "idle_pin" ], Pin.OUT )

	# The playing
	play_led = Pin( board_config[ "playing_pin" ], Pin.OUT )

	# Turn off the leds
	all_off( False )

# Clear the strip
# Change all the colors to ( 0, 0, 0 )
def clear_strip():
	global leds_strip

	# Change the colors of the entire strip
	for i in range( 0, len( leds_strip ), 1 ):
		change_led_color( i, ( 0, 0, 0 ) )

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
	if ( led < 0 or len( color ) != 3 or led > len( leds_strip ) ): # The values are wrong
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

# Turn on a led
# If already on stais on
def led_on( led ):
	led.value( 1 )

# Turn off a led
# If already off stais off
def led_off( led ):
	led.value( 0 )

# Turn both the debug leds to signal the exception
# PARAMETERS ( ?bool ):
#	strip: if to turn off even the strip
def error( strip = False ):
	global idle_led
	global play_led

	# Turn on the debug leds
	led_on( idle_led )
	led_on( play_led )

	# Decide if to turn off the strip too
	if ( strip ):
		# Turn off the strip
		clear_strip()

# Turn off all the leds
# PARAMETERS ( ?bool ):
#	-True: turn off even the strip
#	-False: only turn off debug leds
def all_off( strip = False ):
	global idle_led
	global play_led

	# Turn off the debug leds
	led_off( idle_led )
	led_off( play_led )

	# Decide if to turn off the strip too
	if ( strip ):
		# Turn off the strip
		clear_strip()
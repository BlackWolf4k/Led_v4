# For hardware comunication with the pins
from machine import Pin

# To read config files
from File import read

# To sleep
import time

# The button for access point start
button = 0

def init():
	global button

	# Read the config file of the board
	board_config = read.read_conf_file( "board.json" )

	# Initialize the button
	button = Pin( board_config[ "button_pin" ], Pin.IN )

# Read the actual value of the button
# RETURN ( bool ):
#	-True: button is beeing pressed
#	-False: the button is not beeing pressed
def read_button():
	global button

	# Read the state
	value = button.value()

	# The readed value may be wrong
	# Wait some time to be sure that the readed values is correct
	time.sleep( 0.5 )

	# Return true only if both the readed values where true
	if ( button.value() == True and value == True ):
		return True

	return False
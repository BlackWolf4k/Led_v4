# For wlan_ap connection
import network

# To read config file
from File import read

# Create a global variable to store the wlan_ap
wlan_ap = 0

def init():
	global wlan_ap

	# Read the config file of the board
	board_config = read.read_conf_file( "board.json" )

	# Create the wlan_ap as AP_IF ( access point )
	wlan_ap = network.WLAN( network.AP_IF )

	# Give the access point the ssid with the name of the board and as password "password"
	wlan_ap.config( essid = board_config[ "board_name" ], password = "password" )

def start():
	global wlan_ap

	# Active the wlan_ap
	wlan_ap.active( True )
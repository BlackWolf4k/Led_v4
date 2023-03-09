# For wlan connection
import network

# To read config file
from File import read

# Create a global variable to store the wlan
wlan = 0

# Initialize the wlan interface
def init():
	global wlan

	# Initialize the wlan
	wlan = network.WLAN( network.STA_IF )
	wlan.active( True )

# Connect to the wifi
# RETURN ( int ):
#	-0: error code
#	-1: success code
def connect():
	global wlan

	# Read from the connection config file the ssid and the password
	wifi_config = read.read_conf_file( "wifi.json" )

	# Check that the opening was sucessful
	if ( wifi_config == 0 ):
		return 0

	# Connect
	wlan.connect( wifi_config[ "ssid" ], wifi_config[ "password" ] )

	# Check if connected
	if ( wlan.isconnected() == False ):
		return 0 # Not connected
	else:
		return 1 # Connected

# Disconnect from the wifi
def disconnect():
	global wlan
	
	# Disconnect
	wlan.disconnect()

# Scan for the aveilable wifi networks
# RETURN ( array )
#	-array: list of the networks ( may be empty )
def scan():
	global wlan

	# Scan for networks and return the result
	return wlan.scan()
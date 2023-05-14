# For wlan connection
import network

# To read config file
from File import read

# To sleep during connection
import time

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

def try_secure_connection():
	global wlan

	connection_status = 0

	# 10 should be enough
	times = 20

	# Try to connect 10 times
	while ( connection_status == 0 and times > 0 ):
		# Connect to the wifi
		connection_status = connect()

		print( wlan.ifconfig() )

		# Sleep some time to be sure that there are more connection attempts
		time.sleep( 0.5 )

		times = times - 1
	
	# Check if connected
	if ( connection_status == 0 ): # Not yet connected
		return False

	return True
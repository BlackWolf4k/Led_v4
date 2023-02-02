# For wlan connection
import network

# To read config file
from File import read

# Create a global variable to store the wlan
wlan = 0

# Initialize the wlan interface
def init():
	global wlan

	# Initialize the vlan
	wlan = network.WLAN( network.STA_IF )
	wlan.active( True )

# Connect to the wifi
# RETURN:
#	-0: error code
#	-1: success code
def connect():
	global wlan

	# Read from the connection config file the ssid and the password
	file_content = file_read( "Config/wifi.conf" )

	# Check that the opening was sucessful
	if ( file_content == 0 )
		return 0

	content = content.split( ":" )

	# Connect
	wlan.connect( content[0], content[1] )

	# Check if connected
	if ( wlan.isconnected() == False )
		return 0 # Not connected
	else
		return 1 # Connected

# Disconnect from the wifi
def disconnect()
	global wlan
	
	# Disconnect
	wlan.disconnect()

# Scan for the aveilable wifi networks
# RETURN
#	-list: list of the networks ( may be empty )
def scan():
	global wlan

	# Scan for networks and return the result
	return wlan.scan()
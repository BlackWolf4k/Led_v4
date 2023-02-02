# Import needed standard libraries
import _thread

# Import needed libraries
from Connections import connection

# Main function
if __name__ == "__main__":
	# Connect to the wifi
	status = connection.connect()

	# If connection fails play offline animation
	#if ( status == 0 )
	# Start the local server
	#local_server_thread = _thread.start_new_thread( start_local_server, () ) # Server is started on the second core
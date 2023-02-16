# Import needed standard libraries
import _thread

# Import needed libraries
#from Connections import connection
import Animations.animation as animation

# Main function
if __name__ == "__main__":
	# Connect to the wifi
	#status = connection.connect()
	print( animation.get_animation() )

	# If connection fails play offline animation
	#if ( status == 0 )
	# Start the local server
	#local_server_thread = _thread.start_new_thread( start_local_server, () ) # Server is started on the second core
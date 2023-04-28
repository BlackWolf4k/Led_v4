# Import needed standard libraries
import _thread

# Import needed libraries
from Connections import connection
import Animation.animation as animation

# To run the webserver
from Server import server_main

# To dump Data
from File import write

# To update config file
from File import update

# Main function
if __name__ == "__main__":
	# Connect to the wifi
	connection.init()
	status = connection.connect()

	print( connection.wlan.ifconfig() )

	# Configure the webserver
	server_main.init()
	#server_main.main()

	# Start the web server
	server_thread = _thread.start_new_thread( server_main.main, () ) # Server is started on the second core

	# Get the animation
	animation_ = animation.get_animation()

	# Update the name of the actual animation
	#update.update_config_file( "board.json", { "actual_animation" : animation_[ "descriptor" ][ "name" ] } )

	# Dump the animation
	#write.dump_json( animation_ )

	#print( connection.wlan.ifconfig() )

	animation.play_animation( animation_ )

	print( "Ended" )


	# If connection fails play offline animation
	#if ( status == 0 )
	# Start the local server
	#local_server_thread = _thread.start_new_thread( start_local_server, () ) # Server is started on the second core

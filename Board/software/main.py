# Import needed standard libraries
# import _thread

# To connect to wifi
from Connections import connection

# To make access point
from Connections import access_point

# To get and play animations
import Animation.animation as animation

# To run the webserver
from Server import server_main

# To dump Data
from File import write

# To update config file
from File import update

# To initialize the leds
from Led import led

# To read the value of the access point button
from Button import button

# For connection

# Initalize all the components that need to be initialized
# Checks if the ap button is pressed
# RETURNS ( bool ):
#	-True: server mode
#	-False: client mode
def early_main():
	# Initialize the button
	button.init()

	# Configure the debug leds
	led.init_debug()

	# Check the value of the access point button
	if ( button.read_button() ): # Pressed
		# If the buttton is pressed start the server
		# Initialize the access point
		access_point.init()

		# Configure the server
		server_main.init()

		# Return True indicating to start server
		return True
	else:
		# If the button is not pressed play animations
		# Initialize the connection to the wifi
		connection.init()

		# Configure the leds
		led.init_strip()

		# Return False to indicate client
		return False

# Main function
if __name__ == "__main__":
	# Start the early main
	is_server = early_main()

	# Based of the early main values decide if to run the web server of the animation player
	if ( is_server ): # Is server
		# Start the access point
		access_point.start()

		# Run the web server
		try:
			server_main.main()
		except Exception as exception: # Proably a socket exception
			print( exception )

			# Go in error state
			led.error()
	else:
		connection_status = connection.try_secure_connection()

		# Check if realy connected
		if ( connection_status == 0 ): # Not realy connected
			# Play offline animation forever
			while True:
				animation.play_default_animation()
		else:
			# Play animations in loop
			while True:
				try:
					# Get the animation
					animation_ = animation.get_animation()

					# Play the animation
					animation.play_animation( animation_ )
				except Exception as exception: # Proably a timeout exception
					#print( exception )

					# Go in error state and turn off the strip
					led.error( True )

		# If comes here probably not connected and the deault animation ended
		led.error( True )

#server_thread = _thread.start_new_thread( server_main.main, () ) # Server is started on the second core
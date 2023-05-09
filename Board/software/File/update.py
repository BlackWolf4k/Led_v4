import json
# To read and write files
from File import read, write

# Update a config file given
# Only write the keys that are in the config file
# ARGUMENTS ( string, dict ):
#	-filename: the name of the config file
#	-data: the data to write to the file
# RETURNS ( int, arr )
#	-0: error code
#	-[]: the data changed
def update_config_file( filename, data ):
	# Read the content of the file
	content = read.read_conf_file( filename )

	# Check that a file was opened
	if ( content == 0 ):
		return 0 # No content in the file or no file

	# The key that have been changed
	changed_keys = []

	# Open the file
	with open( "/Config/" + filename, "r" ) as file:
		# Write one key at time
		for key in list( data.keys() ):
			# Check that the key is in the content
			if ( key in list( content.keys() ) ):
				# Change the value
				content[ key ] = data[ key ]
				# Store that this key has been changed
				changed_keys.append( key )
	
	# Write the file
	write.write_conf_file( filename, content )

	return changed_keys
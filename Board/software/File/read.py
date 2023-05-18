import json

# To read directories
import os

# Read a file
# ARGUMENTS ( str ):
#	-filename: the name of the file to read
# RETURN ( list ):
#	-list: content of the file
#	-0: error code
def read_file( filename ):
	# Create the string to store the file content
	content = ""

	# Read the content
	try:
		with open( filename, "rb" ) as file:
			# Read one byte per time
			content = file.read()
	except: # No file found
		return 0
	
	# Return the file content
	return content

# Read a file
# ARGUMENTS ( str ):
#	-filename: the name of the config file to read
# RETURN ( dict ):
#	-dict: json
#	-0: error code
def read_conf_file( filename ):
	file = 0

	# Open the file
	try:
		file = open( "/Config/" + filename, "r" ) # Check this
	except: # No file found
		return 0

	# Read the json
	content = json.load( file )

	# Close the file
	file.close()

	# Return the json
	return content

# Read the default animation file
def read_default_animation():
	file = 0

	# Open the file
	try:
		file = open( "/Animation/" + "default_animation.json", "r" ) # Check this
	except: # No file found
		return 0

	# Read the json
	content = json.load( file )

	# Close the file
	file.close()

	# Return the json
	return content

# List all the files in a directory
# ARGUMENTS ( string ):
#	-path: the directory absolute path
# RETURNS ( list ):
#	-files_list: the list of the found devices
def list_directory( path ):
	# Go to the directory
	os.chdir( path )

	# List the files
	files_list = os.listdir()

	# Return the result
	return files_list
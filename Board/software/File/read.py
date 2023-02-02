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
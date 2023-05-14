# To read files
from File import read

# Interpretate index.html
# ARGUMENTS ()
# RETURNS ( string | int ):
#	-content: the content of the page
#	-0: error code
def __index_interpreter():
	# Read the page
	content = read.read_file( "/Server/pages/index.html" )

	# Decode the contet
	content = content.decode( "utf-8" )

	# Get the values of the board
	board_config = read.read_conf_file( "board.json" )

	# Changhe the %() values
	#content = content % ( board_config[ "board_name" ], int( board_config[ "leds_number" ] ), board_config[ "actual_animation" ], board_config[ "remote_animation" ] )
	print ( board_config[ "board_name" ] )
	content = content.replace( "__board_name__", str( board_config[ "board_name" ] ) )
	content = content.replace( "__leds_number__", str( board_config[ "leds_number" ] ) )
	content = content.replace( "__actual_animation__", board_config[ "actual_animation" ] )
	content = content.replace( "__remote_animation__", board_config[ "remote_animation" ] )

	# Return the interpreted content
	return content

# ARGUMENTS ()
# RETURNS ( string | int ):
#	-content: the content of the page
#	-0: error code
# Interpretate error.html
def __error_interpreter():
	# Read the page
	content = read.read_file( "/Server/pages/error.html" )

	# Return the interpreted content
	return content

def __token_interpreter():
	# Read the page
	content = read.read_file( "/Server/pages/token.html" )

	# Decode the content
	content.decode( "utf-8" )

	# Get the board config
	board_config = read.read_conf_file( "board.json" )

	# Change the values %()
	content = content % board_config[ "token" ]

	# Return the interpreted page
	return content

interpreters = {
	"index.html": __index_interpreter,
	"error.html": __error_interpreter,
	"token.html": __token_interpreter
}

# Interpretates a page
# ARGUMENTS ( string ):
#	-filename: the filename of the page
# RETURN ( string | int ):
#	-content: the interpreted content of the page
#	-0: error code
def interpreter( filename ):
	# Check that the file can be interpreted
	if filename not in interpreters: # File not found
		return 0

	# Run the interpreter
	content = interpreters[ filename ]()

	# Return the page's content
	return content
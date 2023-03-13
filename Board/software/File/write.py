# To handle jsons
import json

# Write a config file
# ARGUMENTS ( string, dict ):
#	-filename: the name of the config gile
#	-content: the content to write on the file
# RETURNS ( bool ):
#	-0: error code
#	-1: success code
def write_conf_file( filename, content ):
	# Open the file
	with open( "Config/" + filename, "r" ) as config_file:
		# Dump the json
		content = json.dumps( content )

		# Write the content to the file
		config_file.write( content )

# Dump a json in Dump/json_dump.json
# It appends at the end of the file
# ARGUMENTS ( dict ):
#	-dictionary_content: the dictionary to dump
def dump_json( dictionary_content ):
	# Open the file in append mode
	with open( "Dump/json_dump.json", "a" ) as json_file:
		# Dump the json
		dictionary_content = json.dumps( dictionary_content )

		# Write to the file the dumped json
		json_file.write( dictionary_content )
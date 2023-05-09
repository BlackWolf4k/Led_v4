# To handle jsons
import json

# Write the default animation to the file
# If the file is already create, deletes it and make a new one
# ARGUMENTS ( dict ):
#	-content: the content to write on the file
# RETURNS ( bool ):
#	-0: error code
#	-1: success code
def write_default_animation( content ):
	# Open the file
	try:
		with open( "/Animation/" + "default_animation.json", "w" ) as animation_file:
			# Dump the json
			content = json.dumps( content )

			# Write the content to the file
			animation_file.write( content )
	except OSError:
		return 0
	
	return 1

# Write a config file
# ARGUMENTS ( string, dict ):
#	-filename: the name of the config gile
#	-content: the content to write on the file
# RETURNS ( bool ):
#	-0: error code
#	-1: success code
def write_conf_file( filename, content ):
	# Open the file
	try:
		with open( "/Config/" + filename, "w" ) as config_file:
			# Dump the json
			content = json.dumps( content )

			# Write the content to the file
			config_file.write( content )
	except OSError:
		return 0
	
	return 1

# Dump a json in Dump/json_dump.json
# It appends at the end of the file
# ARGUMENTS ( dict ):
#	-dictionary_content: the dictionary to dump
# RETURNS ( int ):
#	-0: error code
def dump_json( dictionary_content ):
	# Open the file in append mode
	try:
		with open( "/Dump/json_dump.json", "a" ) as json_file:
			# Dump the json
			dictionary_content = json.dumps( dictionary_content )

			# Write to the file the dumped json
			json_file.write( dictionary_content )
	except OSError:
		return 0

	return 1

# Dump a string ( plain text ) in Dump/plain_dumo.txt
# It appends at the end of the file
# ARGUMENTS ( str ):
#	-plain_text: the plain text to dump
# RETURNS ( int ):
#	-0: error code
def dump_plain( plain_text ):
	# Open the file in append mode
	try:
		plain_text_file = open( "Dump/plain_dump.txt", "a" )
	except OSError:
		return 0
	
	# Write the plain text
	plain_text_file.write( plain_text )

	# Close the file
	plain_text_file.close()
# To handle jsons
import json

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
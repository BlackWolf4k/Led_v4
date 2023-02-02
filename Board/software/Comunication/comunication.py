import urequests

# Send a post request
# ARGUMENTS ( str, dict ):
#	-url: the url where to send the post request
#	-content: the content in json format to send
# RETURN ( dict ):
#	-dictionary: contains the response from the server
#	-0: error code
def post( url, content ):
	# Send the request
	response = urequests.post( url, json = content )

	# Check the response
	# Return the result
import requests
from colorama import Fore, Back, Style
import time

token = "648801b60d1395da07cb1cd506a5d64a83984455"
board_id = "1"
offline_animation_name = "Test_one"

def log( text, status ):
	# Normal
	if status == 0:
		print( Fore.WHITE + text )
	# Good ( green )
	elif status == 1:
		print( Fore.GREEN + text + Fore.WHITE )
	# Bad ( red )
	elif status == 2:
		print( Fore.RED + text + Fore.WHITE )

def get_config():
	content = requests.get( "http://192.136.60.75:81/?code=7&board_id=" + board_id + "&token=" + token )

	if content.status_code != 200:
		log( "SOMETHING WRONG IN \"get_config\"", 2 )
		print( content.text )
	else:
		log( "\"get_config\" OK", 1 )
		print( content.text )

def get_new_animation():
	content = requests.get( "http://192.136.60.75:81/?code=1&board_id=" + board_id + "&token=" + token )

	if content.status_code != 200:
		log( "SOMETHING WRONG IN \"get_new_animation\"", 2 )
		print( content.text )
	else:
		log( "\"get_new_animation\" OK", 1 )
		print( content.text )

def get_offline_animation():
	content = requests.get( "http://192.136.60.75:81/?code=1&board_id=" + board_id + "&animation_name=" + offline_animation_name + "&token=" + token )

	if content.status_code != 200:
		log( "SOMETHING WRONG IN \"get_offline_animation\"", 2 )
		print( content.text )
	else:
		log( "\"get_offline_animation\" OK", 1 )
		print( content.text )

def run():
	while True:
		get_config()
		get_new_animation()
		get_offline_animation()
		time.sleep( 2 )

commands = { "get_config" : get_config,
			 "get_new_animation" : get_new_animation,
			 "get_offline_animation" : get_offline_animation,
			 "run" : run }


# Ask for the main config
if __name__ == "__main__":
	while True:
		command = input( "$ " )
		
		if command in commands:
			commands[ command ]()
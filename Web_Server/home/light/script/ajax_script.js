var this_server_url = "127.0.0.1:81";

function change_sub_playlists( selected_group, user_token )
{
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function()
	{
		// Split the results
		var sub_playlist_list = this.responseText.split( "\n" );

		if ( this.readyState == 4 && this.status == 200 )
		{
			// Write all the subplaylist
			for ( var i = 0; i < sub_playlist_list.length; i++ )
			{
				var sub_playlist = sub_playlist_list[i].split( ";" );

				// Check that there is something
				if ( sub_playlist[0] != "" )
					document.getElementById( "playlist" ).innerHTML += "<option value=" + sub_playlist[0] + ">" + sub_playlist[1] + "</option>";
			}
		}
	};

	// Send the request
	xmlhttp.open( "GET", "http://" + this_server_url + "/?code=8197&group_id=" + selected_group + "&token=" + user_token, true );
	xmlhttp.send();
}

function change_animations( selected_sub_playlist )
{
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function()
	{
		// Split the results
		var sub_playlist_list = this.responseText.split( "\n" );

		if ( this.readyState == 4 && this.status == 200 )
		{
			// Write all the subplaylist
			for ( var i = 0; i < sub_playlist_list.length; i++ )
			{
				var sub_playlist = sub_playlist_list[i].split( ";" );

				// Check that there is something
				if ( sub_playlist[0] != "" )
					document.getElementById( "playlist" ).innerHTML += "<option value=" + sub_playlist[0] + ">" + sub_playlist[1] + "</option>";
			}
		}
	};

	// Send the request
	xmlhttp.open( "GET", "get_sub_playlist.php?group_id=" + selected_sub_playlist, true );
	xmlhttp.send();
}
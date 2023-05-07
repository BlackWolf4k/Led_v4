function hide_show_table( group )
{
	if ( document.getElementById( group + "_table" ).style.visibility == "hidden" )
	{
		document.getElementById( group + "hide_show_icon" ).innerHTML = "expand_more";
		document.getElementById( group + "_table" ).style.visibility = "visible";
	}
	else
	{
		document.getElementById( group + "hide_show_icon" ).innerHTML = "expand_less";
		document.getElementById( group + "_table" ).style.visibility = "hidden";
	}
}
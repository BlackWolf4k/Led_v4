function hide_show_table( album )
{
	if ( document.getElementById( album + "_table" ).style.visibility == "hidden" )
	{
		document.getElementById( album + "hide_show_icon" ).innerHTML = "expand_more";
		document.getElementById( album + "_table" ).style.visibility = "visible";
	}
	else
	{
		document.getElementById( album + "hide_show_icon" ).innerHTML = "expand_less";
		document.getElementById( album + "_table" ).style.visibility = "hidden";
	}
}
<?php
	// Functions to check that the animation matches the pattern
	$pattern_check = [
		1 => "standard",
		2 => "rainbow"
	];

	// Check if all the values inside the body of the animation are ok
	// Does not control the values of the descriptor
	// ARGUMENTS ( animation ):
	//	-animation: the whole animation
	// RETURNS ( bool ):
	//	-0: error code
	//	-1: success code
	function standard( $animation )
	{
		// Check the rgb value of each pixel
		for ( $i = 0; $i < $animation[ "descriptor" ][ "phases" ]; $i++ )
			for ( $j = 0; $j < $animation[ "descriptor" ][ "pixels" ]; $j++ )
				for ( $k = 0; $k < 3; $k++ )
					if ( $animation[ "body" ][ $i ][ $j ][ $k ] < 0 || $animation[ "body" ][ $i ][ $j ][ $k ] > 255 )
						return 0; // There was an error
		
		// Everything was fine
		return 1;
	}

	// Write inside the body the rainbow animation
	// Does not control the values of the descriptor
	// ARGUMENTS ( animation ):
	//	-animation: the whole animation
	// RETURNS ( bool ):
	//	-0: error code
	//	-1: success code
	function rainbow( $body )
	{}
?>
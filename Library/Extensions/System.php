<?php

class System
{
	public function userExists( $username )
	{
		exec( "id $username 2> /dev/null" , $output , $retvar );
		if ( $retvar == 0 ) {
			return true;
		}
		return false;
	}
}

$ozSystem = new System();
$oz->varExport( 'ozSystem' , $ozSystem );
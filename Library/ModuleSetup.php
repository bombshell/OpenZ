<?php

require '../main.php';

class ModuleSetup extends OZ_Core
{	
	public function addToDb( $name , $disc , $file )
	{
		global $zppZDatabase;
		
		$zppZDatabase->setTableName( 'oz_modules_info' );
		if ( !$zppZDatabase->query( '*' , "WHERE oz_modname = '$name'") ) {
			$data = array( 'oz_modname' => $name , 'oz_moddisc' => $disc , 'oz_modfile' => $file );
			$zppZDatabase->insert( $data );
			if ( $zppZDatabase->errorId == 'ERR0401' )
				return false;
		}
		return true;
	}
}
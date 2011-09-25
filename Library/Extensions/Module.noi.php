<?php

class Module
{
	private $currModule;
	
	public function __construct( $module )
	{
		$this->currModule = $module;
	}
	
	public function getFile()
	{
		return OZ_PATH_LIBRARY . path_rewrite( 'Modules/' . $this->currModule[ 'oz_modfile' ] );
	}
	
	public static function getAll()
	{
		global $zppZDatabase;
		$zppZDatabase->setTableName( 'oz_modules_info' );
		return $zppZDatabase->query();
	}
}

//$ozInitThisExtension = true;
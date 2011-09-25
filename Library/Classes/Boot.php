<?php

class Boot
{	
	public function __construct()
	{
	}
	
	public static function classes()
	{
		/*** Global Variables ***/
		global $OpenZ;
		
		/*** Load Zippee Classes ***/
		
		
		/* Load OpenZ Classes */
		$zppFilesystem = new Filesystem();
		$path = OZ_PATH_LIBRARY . 'Extensions/';
		$ExtensionFiles = $zppFilesystem->dirRead( $path );
		
		foreach( $ExtensionFiles as $File ) {
			$Extension = basename( $File , '.php' );
			
			/*** Grep Interface **/
			if ( preg_match( '/(cli|web|noi)$/' , $Extension , $match ) ) {
				$Interface = $match[1];
				if ( $Interface == $OpenZ->getSapi() ) {
					require $path . $File;
				} elseif ( $Interface == 'noi' ) {
					require $path . $File;
				}
				if ( @$ozInitThisExtension ) {
					$Extension = preg_replace( '/(.cli|.web|.noi)$/' , '' , $Extension );
					$ExtensionName = 'oz' . $Extension;
					$$ExtensionName = new $Extension;
					$OpenZ->varExport( $ExtensionName , $$ExtensionName );
				}
				$ozInitThisExtension = false;
			}
		}
	}
	
	public function database()
	{
		global $OZCFG;
		
	
		return $connectOptions;
	}
	
	public static function checkDatabase()
	{
		global $Database, $ozProfile, $OpenZ, $ozConfig;
		/* Verify if the connection was made */
		if ( $Database->errorId == 'ERR0403' ) {
			oz_quit( OZ_ERROR . 'Unable to establish a connection to the database: ' . $Database->errorMsg );
		}
		
		//"Table 'ozdb.oz_accounsts1' doesn't exist"
		/* Tables exists, if not, create them */
		$path = TEMP_DIR . '.openZ_disabledbcheck';
		if ( !file_exists( $path ) ) {
			$tables = array( 'oz_accounts' , 'oz_account_mods' , 'oz_account_notes' , 'oz_email_data' , 'oz_login_counts' , 'oz_modules_info' );
			foreach( $tables as $table ) {
				$Database->setTableName( $table );
				if ( !$Database->query() ) {
					if ( preg_match( "/Table '{$ozConfig[ 'Database' ][ 'Name' ]}.$table' doesn't exist/" , $Database->errorMsg ) ) {
						if ( $sql = @file_get_contents( OZ_PATH_LIBRARY . 'SQL/Version2.sql' ) ) {
							$sql = $OpenZ->stripReplace( array( '%oz_database_name%' => $ozConfig[ 'Database' ][ 'Name' ] ) , $sql );
							$Database->exec( $sql );
					    
							/* Check if SQL Excution went well */
							if ( $Database->errorId == 'ERR0401' )
								oz_quit( 'Error: Failed to create missing tables: SQL Excution failed: ' . $Database->errorMsg );
						} else {
							oz_quit( 'Error: Failed to create missing tables: SQL Excution failed: Unable to read SQL File' );
						}
						
					
						$linebreak = ( $this->sapi == 'web' ) ? '<br><br>Refresh Browser' : "\n\n" . 'Re-run script ' . $_SERVER[ 'PHP_SELF' ];
						oz_quit( 'Error: Detected missing table(s): Automatically executed SQL script' . $linebreak );
					}
				}
			}
		}
		/* Disable Check */
		$OpenZ->fileWrite( 'openZ_disabledbcheck' , $path ); 
		
		$ozProfile->setType( 'admin' );
		if ( !$ozProfile->getAccountByName( 'root' ) )
			oz_quit( 'Error: Root admin account missing' );
	}
	
	public static function close()
	{	
		global $ozEmail, $ozConfig, $OpenZ;
		if ( $OpenZ->getSapi() == 'cli' ) {
			oz_std();
			/*** I shouldn't be doing this here, I moving this to UI/CLI/Cli.php ***/
			/*if ( $_SERVER[ 'USER' ] != 'root' ) {
				oz_quit( OZ_ERROR . OZ_NAME . ' needs to be run as root' );
			}*/
		}
		
		
	}
}
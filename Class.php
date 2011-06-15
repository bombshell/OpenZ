<?php

/*** 

	Copyright (c) http://wiki.bombshellz.net/
	Author: Lutchy Horace
	Version: 0.0.1
	
	Redistribution and use in source or binary forms are permitted provided that the following conditions are met:
		
		* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
		* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
		* Neither the name of the BombShellz.net nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
		* Modification to this file or program is not permitted without the consent of the author.
		* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	
***/

class OZ_Core extends Framework
{
	private $init;
	
	public function __construct( $config )
	{
		parent::__construct( $config );
	}
	
	/**
	 * 
	 * Converts Unix Epoch TimeStamp into human readable format
	 * @param (int) $time timestamp returned by time()
	 * 
	 */
	public function timeToStr( $time )
	{
		return date( 'm/d/Y h:i:s A' , $time );
	}
	
	/**
	 * 
	 * Prints a formatted string
	 * @param (string) $str
	 * 
	 */
	public function printf( $str )
	{
		if ( $this->getSapi() == 'cli' )
			fwrite( STDOUT , $str . "\n" );
		else
			$this->logData( 'ERR0000' , $str );
	}
	
	/**
	 * 
	 * Sanitize path to avoid security exploits
	 * @param (string) $path Path to sanitize
	 * 
	 */
	public function sanitazePath( $path )
	{
		return str_replace( '..' . DS , '' , $path );
	}
	
	public function hash( $str )
	{
		global $OZCFG;
		
		if ( !empty( $OZCFG[ 'Hash_Algo' ] ) )
			if ( in_array( $OZCFG[ 'Hash_Algo' ] , hash_algos() ) ) {
				return hash( $OZCFG[ 'Hash_Algo' ] , $str );
			}
		return false;
	}
	
	public function varExport( $name , $value )
	{
		$GLOBALS[ $name ] = $value;
	}
}

class OZ_Init extends OZ_Core
{	
	public function __construct()
	{
		global $OZCFG, $OZPATH;
		
		/* Init Zippee framework */
		if ( !preg_match( '/^\//' , $OZPATH[ 'ZippeeConfig' ] ) )
			$OZPATH[ 'ZippeeConfig' ] = OZ_PATH_BASE . $OZPATH[ 'ZippeeConfig' ];
			
		parent::__construct( $OZPATH[ 'ZippeeConfig' ] );
		$this->setDebug( $OZCFG[ 'Debug' ] );
	}
	
	public function classes()
	{
		global $zppFilesystem;
		
		/*** Load Zippee Classes ***/
		$this->loadClass( 'Filesystem' );
		$this->loadClass( 'Session_Core' );
		$this->loadClass( 'EmailPhp_Core' );
		$this->loadClass( 'DatabasePDO' );
		$this->loadClass( 'Http' );
		
		/* Load OpenZ Classes */
		$zppFilesystem = new Filesystem();
		$path = OZ_PATH_LIBRARY . 'Extensions/';
		$exts = $zppFilesystem->dirRead( $path );
		
		$oz = $this;
		foreach( $exts as $ext ) {
			require $path . $ext;
		}
	}
	
	public function database()
	{
		global $OZCFG;
		
		$connectOptions[ 'dbType' ] = 'mysql';
		$connectOptions[ 'dbPath' ] = $OZCFG[ 'Database' ][ 'Location' ];
		$connectOptions[ 'dbName' ] = $OZCFG[ 'Database' ][ 'Name' ];
		$connectOptions[ 'dbUser' ] = $OZCFG[ 'Database' ][ 'Username' ]; 
		$connectOptions[ 'dbPass' ] = $OZCFG[ 'Database' ][ 'Password' ];
		return $connectOptions;
	}
	
	public function vars()
	{
		global $OZPATH;
		
		define( 'OZ_NAME' , 'OpenZ Shell User Management System' );
		define( 'OZ_VER' , 'v1.0.0' );
		define( 'OZ_PATH_LIBRARY' , OZ_PATH_BASE . path_rewrite( 'Library/' ) );
		define( 'OZ_ERROR' , 'Error: ' );
	}
	
	public function checkDatabase()
	{
		global $zppZDatabase, $OZCFG;
		/* Verify if the connection was made */
		if ( $zppZDatabase->errorId == 'ERR0403' ) {
			oz_quit( OZ_ERROR . 'Unable to establish a connection to the database: ' . $zppZDatabase->errorMsg );
		}
		
		//"Table 'ozdb.oz_accounsts1' doesn't exist"
		/* Tables exists, if not, create them */
		$tables = array( 'oz_accounts1' , 'oz_accounts_admin' , 'oz_login_counts' , 'oz_account_mods' );
		foreach( $tables as $table ) {
			$zppZDatabase->setTableName( $table );
			if ( !$zppZDatabase->query() ) {
				if ( preg_match( "/Table '{$OZCFG[ 'Database' ][ 'Name' ]}.$table' doesn't exist/" , $zppZDatabase->errorMsg ) ) {
					$linebreak = ( $this->sapi == 'web' ) ? '<br><br>Refresh Browser' : "\n\n" . 'Re-run script ' . $_SERVER[ 'PHP_SELF' ];
					oz_quit( 'Error: Detected missing table(s): Automatically executed SQL script' . $linebreak );
					
					$sql = $this->fileRead( OZ_PATH_LIBRARY . 'SQL/version1.sql' );
				}
			}
		}
		exit;
	}
	
	public function close()
	{
		global $ozProfile;
		$ozProfile->setType( 'admin' );
		if ( !$ozProfile->getByName( 'root' ) )
			oz_quit( 'Error: Root admin account missing' );
			
		if ( $this->sapi == 'cli' ) {
			oz_std();
			if ( $_SERVER[ 'USER' ] != 'root' ) {
				oz_quit( OZ_ERROR . OZ_NAME . ' needs to be run as root' );
			}
		}
	}
}

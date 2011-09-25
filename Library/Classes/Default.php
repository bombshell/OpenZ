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
	
	public function __construct()
	{
		global $ozConfig;
		
		if ( empty( $ozConfig ) ) {
			oz_quit( 'Error: Missing OpenZ Configuration Settings' );
		}
		
		/*** Initialize Zippee Framework ***/
		if ( !preg_match( '/^\//' , $ozConfig[ 'Path' ][ 'ZippeeConfig' ] ) )
			$ozConfig[ 'Path' ][ 'ZippeeConfig' ] = OZ_PATH_BASE . $ozConfig[ 'Path' ][ 'ZippeeConfig' ];	
		parent::__construct( $ozConfig[ 'Path' ][ 'ZippeeConfig' ] );	
		
		/*** Set Verbosity ***/
		$this->setDebug( $ozConfig[ 'Debugging' ][ 'Verbose' ] );
	
	}
	
	/**
	 * 
	 * Converts Unix Epoch TimeStamp into human readable format
	 * @param (int) $time timestamp returned by time()
	 * 
	 */
	public function timeToStr( $time )
	{
		/* Atempt to convert string to long */
		if ( !is_int( $time ) ) {
			if ( preg_match( '/\d*/' , $time ) ) {
				settype( $time , 'integer' );
			} else { 
				return 'Invalid Timestamp';
			}	
		}
		
		if ( $time != 0 ) {
			return date( 'm/d/Y h:i:s A' , $time );
		} else { 
			return 'Time Undefined';
		}
	}
	
	public function time()
	{
		return date( 'm/d/Y h:i:s A' );
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
	
	public function realPath( $path )
	{
		if ( !preg_match( '/^(\w:\\\|\/)/' , $path ) ) {
			return OZ_PATH_BASE . $path;
		}
		return $path;
	}
	
	public function varExport( $name , $value )
	{
		$GLOBALS[ $name ] = $value;
	}
	
	public function sendAlert( $data ) 
	{
		if ( !is_dir( OZ_PATH_ALERTS) ) {
			mkdir( OZ_PATH_ALERTS );
		}
		$time = $this->time();
		$data = "Alert: $time\n$data";
		$alert_filename = tempnam( OZ_PATH_ALERTS , 'alert_' );
		$this->fileWrite( $data , $alert_filename );
	}
	
	public function SendEmail2Root( $subject , $body )
	{
		global $ozEmail, $ozConfig;
		
		if ( !$ozEmail->send( 'root@' .$ozConfig[ 'Url' ][ 'Host' ] , null , $ozConfig[ 'Email' ][ 'Admin' ] ,  $subject , $body ) ) {
			$this->logData( 'ERRx0116' , 'Error: OZ_Core->SendEmail2Root(): Failed to send mail to ' .  $ozConfig[ 'Email' ][ 'Admin' ] );
			return false;
		}
		return true;
	}
	
	/*** Proposal ***/
	public function inc( $file )
	{
		if ( !is_file( $file ) ) {
			
		}
	}
}




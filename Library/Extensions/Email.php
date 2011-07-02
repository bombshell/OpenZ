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

class Email extends Email_Core
{
	public function sendType( $type , $data )
	{
		global $oz, $zppZDatabase, $ozProfile, $ozConfig;
		
		$zppZDatabase->setTableName( 'oz_email_data' );
		$email_data = $zppZDatabase->query( '*' , "WHERE Name = '$type'" );
		if ( empty( $email_data ) ) {
			$oz->logData( 'ERRx0116' , 'Error: Email->sendType(): Invalid E-Mail type: ' . $type , array( 'file' => __FILE__ , 'line' => __LINE__ ) );
			return false;	
		}
		$email_data = $email_data[0];
			
		/*** Prepare E-Mail ***/
		$body = $this->fileRead( $oz->realPath( @$ozConfig[ 'Path' ][ 'EmailLogo' ] ) ) . "\r\n" ;
		$body .= 'Date: ' . $oz->time() . "\r\n\r\n";
		
		$realname = $ozProfile->getField( 'oz_realname' );
		if ( empty( $realname ) )
			if ( $ozProfile->getType() == 'admin' )
				$realname = 'Admininstrator';
			else 
				$realname = 'Client';
		$body .= 'Hello ' . $realname . ",\r\n\r\n";
		
		$heading = $email_data[ 'Heading' ];
		if ( !empty( $heading ) )
			$body .= $heading . "\r\n\r\n";
		
		if ( $this->isflag( 'u' , $email_data[ 'Flags' ] ) ) {
			$body .= '   Username: ' . $ozProfile->getField( 'oz_uid' ) . "\r\n";
		}
		if ( $this->isflag( 'p' , $email_data[ 'Flags' ] ) ) {
			$body .= '   Package: ' . $ozProfile->package() . "\r\n";
		}
		/**
		if ( $this->isflag( 'u' , $email_data[ 'oz_varinclude_flags' ] ) ) {
			$body .= 'Username: ' . $ozProfile->getField( 'oz_uid' );
		}*/
		
		/* Include fields */
		if ( !empty( $data ) )
			foreach( $data as $field => $value )
				$body .= '   ' . $field . ': ' . $value . "\r\n";
		
		$footer = $email_data[ 'Footer' ];
		if ( !empty( $footer ) )
			$body .= $footer . "\r\n";	
		
		$body .= "\r\n\r\n" . $this->fileRead( $oz->realPath( @$ozConfig[ 'Path' ][ 'EmailSig' ] ) );
		
		if ( !empty( $ozConfig[ 'Email.From' ][ $email_data[ 'UseEmailFromType' ] ] ) )
			$from = $ozConfig[ 'Email.From' ][ $email_data[ 'UseEmailFromType' ] ];
		elseif ( !empty( $ozConfig[ 'Email.From' ][ 'Default' ] ) )
			$from = $ozConfig[ 'Email.From' ][ 'Default' ];
		else
			$from = $ozConfig[ 'Email.From' ][ 'Admin' ];
		if ( !$this->send( $from[ 'Address' ] , $from[ 'Name' ] , $ozProfile->getField( 'oz_email' ) ,  $email_data[ 'Subject' ] , $body ) ) {
			$oz->logData( 'ERRx0116' , 'Error: Email->sendType(): Failed to send mail' , array( 'file' => __FILE__ , 'line' => __LINE__ ) );
			return false;
		}
		return true;
	}
	
	public function isflag( $flag , $flags )
	{
		return preg_match( "/$flag/" , $flags );
	}
}

$ozInitThisExtension = true;
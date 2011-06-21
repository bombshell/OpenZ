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

class Session extends Session_Core
{
	public function __construct()
	{
		/* Start Session */
		parent::__construct();
	}
	
	public function isAuth()
	{
		global $zProfile;
		
		if ( !$this->isValid() || !@$_SESSION[ 'is_auth' ] ) {
			$_SESSION[ 'is_auth' ] = false;
			return false; 
		}  
		return true;
			
	}
	
	public function auth( $ozuid , $ozpwd , $authType )
	{
		global $oz, $ozProfile;
		
		if ( empty( $ozuid ) || empty( $ozpwd ) ) {
			return false;
		}
		
		$error_prefix = 'Error: Login Failed: ';
		$ozProfile->setType( $authType );
		$ozProfile->getByName( $ozuid );
		if ( $ozProfile->valid() ) {
			if ( $ozProfile->getField( 'oz_uid' ) == $ozuid ) {
				if ( $oz->hash( $ozpwd ) == $ozProfile->getField( 'oz_pwd' ) ) {
					$_SESSION[ 'is_auth' ]      = true;
					$_SESSION[ 'profile' ]      = $ozProfile->getAllFields();
					$_SESSION[ 'profile_type' ] = $authType;
					return true;
				} else {
					$this->errorId = 'ERR0606';
					$this->errorMsg = $error_prefix . 'Invalid Password: ' . $ozuid;
					$oz->logData( $this->errorId , $this->errorMsg );
				}
			} else {
				$this->errorId = 'ERR0606';
				$this->errorMsg = $error_prefix . 'Invalid Account: ' . $ozuid;
				$oz->logData( $this->errorId , $this->errorMsg );
			}
		} else {
			$this->errorId = 'ERR0605';
        	$this->errorMsg = $error_prefix . 'Profile disabled: ' . $ozuid;
        	$oz->logData( $this->errorId , $this->errorMsg );
		}
		return false;
	}
}


$ozSession = new Session();
$oz->varExport( 'ozSession' , $ozSession );
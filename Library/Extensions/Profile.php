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

class Profile
{	
	private $profileType;
	private $currProfile;
	
	public function getByName( $ozuid )
	{
		global $zppZDatabase;
		
		if ( empty( $ozuid ) )
			return false;
			
		$ozuid = trim( $ozuid );
		if ( @$this->currProfile[ 'ozuid' ] != $ozuid ) {
			if ( $this->profileType == 'admin' )
				$zppZDatabase->setTableName( 'oz_account_admins' );
			else 
				$zppZDatabase->setTableName( 'oz_account_clients');
			$account = $zppZDatabase->query( '*' , "WHERE oz_uid = '$ozuid'" );
			if ( empty( $account ) )
				return false;
			$this->currProfile = $account[0];
		}
		return true;
	}
	
	public function setType( $profileType )
	{
		$this->profileType = $profileType;
	}
	
	public function getField( $field )
	{
		return $this->currProfile[ $field ];
	}
	
	/**
	 * 
	 * Return strip to lower version of oz_uid
	 * for *Nix usernames compatability
	 * @access public
	 * @return (string) Profile Username
	 *  
	 */
	public function getUid()
	{
		return strtolower( $this->currProfile[ 'oz_uid' ] );
	}
	
	public function getAllFields()
	{
		return $this->currProfile;
	}
	
	public function valid()
	{
		if ( $this->profileType == 'admin' ) {
			if ( $this->currProfile[ 'oz_status' ] != 'Suspended' ) {
				return true;
			}
		} elseif ( @$this->currProfile[ 'oz_emailvalid' ] == 1 
				&& ( $this->currProfile[ 'oz_status' ] != 'Suspended' || $this->currProfile[ 'oz_status' ] != 'Inactive' ) ) {
			return true;
		}
		return false;
	}
	
	public function package()
	{
		global $OZCFG;
		
		if ( !empty( $OZCFG[ 'Package' ][ $this->currProfile[ 'oz_packageid'] ] ) ) {
			return 	$OZCFG[ 'Package' ][ $this->currProfile[ 'oz_packageid'] ];
		} else {
			return 'Undefine';
		}
	}
	
	public function activateShell()
	{
		global $ozSystem;
		
		/* Check if we have profile to operate on */
		if ( !$this->profileOpen() ) 
			return false;
		
		/* Check if user doesn't exists */
		if ( $ozSystem->userExists( $this->getUid() ) ) {
			$this->errorId = 'ERR0703';
			$this->errorMsg = 'Error: Account already exists on system';
			return false;
		}
		
	}
	
	/**
	 * 
	 * Checks if a profile is currently open
	 * for usage
	 * @access public
	 * @return (bool) True if a  profile is open, false otherwise
	 * 
	 */
	public function profileOpen()
	{
		if ( !empty( $this->currProfile ) )
			return true;
		
		$this->errorId = 'ERR0000';
		$this->errorMsg = 'Notice: profileOpen(): No profile open';
		return false;
	}
	
	public function modifyProfile( $data , $reason )
	{
		
	}
	
	public function close()
	{
		$this->currProfile = null;
	}
}

$ozProfile = new Profile();
$oz->varExport( 'ozProfile' , $ozProfile );
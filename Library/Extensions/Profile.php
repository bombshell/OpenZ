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
			$zppZDatabase->setTableName( $this->getTable() );
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
	
	public function getType()
	{
		return $this->profileType;
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
			return 	$OZCFG[ 'Package' ][ $this->currProfile[ 'oz_packageid' ] ][ 'Name' ];
		} 
		return false;
	}
	
	public function isLocked()
	{
		if ( $this->currProfile[ 'oz_status' ] == 'Suspended'
				|| $this->currProfile[ 'oz_status' ] == 'Inactive' ) {
			return true;			
		}
		return false;
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
		global $zppZDatabase, $oz;
		
		/* Set User table */
		$zppZDatabase->setTableName( $this->getTable() );
		if ( !$zppZDatabase->update( $data , 'WHERE oz_uid = "' . $this->currProfile[ 'oz_uid' ] . '"' ) ) {
			$oz->logData( $zppZDatabase->errorId , 'Error: Failed at updating profile: ' . $zppZDatabase->errorMsg , array( 'file' => __FILE__ , 'line' => __LINE__ ) );
			return false;
		}
			
		/* Series of checks */
		if ( empty( $reason ) )
			$reason = 'Unknown';
			
		$cols[ 'oz_clientid' ] = $this->currProfile[ 'ozid' ];
		$cols[ 'oz_time_mod' ] = time();
		$cols[ 'oz_mod_user' ] = $_SESSION[ 'profile' ][ 'oz_uid' ];
		$cols[ 'oz_mod' ]      = $reason;
		$zppZDatabase->setTableName( 'oz_account_mods' );
		$zppZDatabase->insert( $cols );
	}
	
	public function close()
	{
		$this->currProfile = null;
	}
	
	private function getTable()
	{
		if ( $this->profileType == 'admin' ) {
			return 'oz_account_admins';
		} else {
			return 'oz_account_clients';
		}
	}
}

$ozProfile = new Profile();
$oz->varExport( 'ozProfile' , $ozProfile );
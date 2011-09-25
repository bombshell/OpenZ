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
	public $errorId;
	private $profileType;
	private $currProfile;
	
	public function __construct()
	{
		global $Database;
		
		/*** Build SQL Cache ***/
		$Database->setTableName( $this->getTable() );
		$Database->StoreQueryInCache( 'PROFILE_SELECT' , $Database->BuildSelectQuery( '*' , ' WHERE oz_uid = ?' ) );
		$Database->StoreQueryInCache( 'PROFILE_LOCK' , $Database->BuildUpdateQuery( array( 'oz_status' , 'oz_admin_locked' , 'oz_time_locked' , 'oz_lockedthreshold' , 'oz_lockedreason' , 'oz_trialstatus' ) , 'WHERE oz_uid = :oz_uid' ) );
	}
	
	public function GetAccountByName( $ozuid , $force = false )
	{
		global $Database;
		
		if ( empty( $ozuid ) )
			return false;
			
		$ozuid = trim( $ozuid );
		$Database->setTableName( $this->getTable() );
		if ( @$this->currProfile[ 'oz_uid' ] != $ozuid || $force == true ) {
			/*** BUG 09/04/2011 : As a child process, PDO->prepare() with bind arguments will fail ***/
			$account = $Database->ExecuteStoredQuery( 'PROFILE_SELECT' , array( $ozuid ) );
			if ( empty( $account ) ) {
				/*** Fall back to normal method ***/
				$account = $Database->query( '*' , "WHERE oz_uid='$ozuid'" );
				if ( empty( $account ) ) {
					return false;
				}
			}
			$this->currProfile = $account[0];
		}
		return true;
	}
	
	public function getByEmail( $ozemail )
	{
		/*** Global Variables ***/
		global $Database;
		
		if ( empty( $ozemail ) )
			return false;
			
		$ozemail = trim( $ozemail );
		if ( @$this->currProfile[ 'oz_email' ] != $ozemail ) {			
			$Database->setTableName( $this->getTable() );
			$account = $Database->query( '*' , "WHERE oz_email = '$ozemail'" );
			if ( empty( $account ) )
				return false;
			$this->currProfile = $account[0];
		}
		return true;
	}
	
	public function getType()
	{
		return @$this->currProfile[ 'oz_accounttype' ];
	}
	
	/*public function getField( $field )
	{
		return @$this->currProfile[ $field ];
	}*/
	
	public function GetField( $field , $return_error_string = false ) 
	{
		if ( $return_error_string == true ) {
			if ( !isset( $this->currProfile[ $field ] ) ) {
				return 'Undefined';
			} elseif ( empty( $this->currProfile[ $field ] ) ) {
				return 'N/A';
			}
		} 
		return @$this->currProfile[ $field ];	
		
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
	
	public function setByData( $data )
	{
		$this->currProfile = $data;
	}
	
	public function setType( $profileType )
	{
		$this->profileType = $profileType;
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
	
	public function package( $id = null )
	{
		global $ozConfig;
		if ( !empty( $id ) ) {
			if ( !empty( $ozConfig[ 'Package' ][ $id ] ) ) {
				return $ozConfig[ 'Package' ][ $id ][ 'Name' ];
			} 
		} elseif ( !empty( $ozConfig[ 'Package' ][ @$this->currProfile[ 'oz_packageid' ] ] ) ) {
			return 	$ozConfig[ 'Package' ][ $this->currProfile[ 'oz_packageid' ] ][ 'Name' ];
		} 
		return 'Unassigned';
	}
	
	public function IsLocked()
	{
		if ( $this->currProfile[ 'oz_status' ] == 'Suspended'
				/*|| $this->currProfile[ 'oz_status' ] == 'Inactive'*/ ) {
			return true;			
		}
		return false;
	}
	
	
	public function lock( $lockId )
	{
		$data[ ':oz_status' ]          = 'Suspended'; 
		$data[ ':oz_admin_locked' ]    = $_SESSION[ 'profile' ][ 'oz_uid' ];
		$data[ ':oz_time_locked' ]     = time();
		$data[ ':oz_lockedthreshold' ] = $this->currProfile[ 'oz_lockedthreshold' ] + 1;
		$data[ ':oz_lockedreason' ]    = $lockId;
		$data[ ':oz_trialstatus' ]     = ( $lockId == 'tf' || $lockId == 'te' ) ? 'Failed' : $this->currProfile[ 'oz_trialstatus' ];
		return $this->ModifyProfile( 'PROFILE_LOCK' , $data , "Locking Account reason $lockId" );
	}
	
	public function unlock()
	{
		global $ozConfig, $oz;
		
		$lockThreshold = ( !empty( $ozConfig[ 'LockedThreshold' ] ) ) ? $ozConfig[ 'LockedThreshold' ] : 5;
		if ( $this->getField( 'oz_lockedthreshold' ) <= $lockThreshold ) {
			/* Empty fields */
			$data[ ':oz_status' ]          = 'Active';
			$data[ ':oz_admin_locked' ]    = 'NULL';
			$data[ ':oz_time_locked' ]     = 'NULL';
			$data[ ':oz_lockedthreshold' ] = 'NULL';
			$data[ ':oz_lockedreason' ]    = 'NULL';
			$data[ ':oz_trialstatus' ]     = $this->currProfile[ 'oz_trialstatus' ];
			return $this->ModifyProfile( 'PROFILE_LOCK' , $data , 'Unlocking Account' );
		} else {
			$oz->logData( 'ERRx0116' , 'Error: Account ' . $this->currProfile[ 'oz_uid' ] . ' has exceeded lock threshold set in configuration' );
			return false;
		} 
	}
	
	public function LockStatus()
	{
		if ( $this->currProfile[ 'oz_status' ] == 'Suspended' ) {
				if ( $this->currProfile[ 'oz_lockedreason' ] == 'o' ) {
					$reason = 'Other, see admin';
				} elseif( $this->currProfile[ 'oz_lockedreason' ] == 'f' || $this->currProfile[ 'oz_lockedreason' ] == 'tf' ) {
					$reason = 'Failed Trial';
				} elseif( $this->currProfile[ 'oz_lockedreason' ] == 'te') {
					$reason = 'Trial Expired';
				} elseif( !empty( $ozConfig[ 'LockReasons' ][ $this->currProfile[ 'oz_lockedreason' ] ] ) ) {
					$reason = $ozConfig[ 'LockReasons' ][ $this->currProfile[ 'oz_lockedreason' ] ];
				} else {
					$reason = 'Unknown';
				}
				$reason = "$reason (({$this->currProfile[ 'oz_admin_locked' ]})" . $oz->timeToStr( $this->currProfile[ 'oz_time_locked' ] ) . ')';	
		} else {
			$reason = 'Not Locked';
		}
		return $reason;
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
		return false;
	}
	
	public function add( $data )
	{
		global $oz, $ozConfig, $Database;
		$Database->setTableName( $this->getTable() );
		
		/* Check if user already exists */
		if ( empty( $data[ 'oz_uid' ] ) ) {
			$this->errorId = 'ERRx0116';
			$this->errorMsg = 'Error: Profile->add(): Missiong Username';
			if ( $oz->debug == 2 ) {
				oz_quit( $this->errorId . ' ' . $this->errorMsg );
			}
			$oz->logData( $this->errorId , $this->errorMsg );
			return false;
		}
		if ( $this->getByName( $data[ 'oz_uid' ] ) || $this->getByEmail( $data[ 'oz_email' ] ) ) {
			$this->errorId = 'ERR0703';
			$this->errorMsg = "Error: Profile->add(): Supplied username {$data[ 'oz_uid' ]}: Account already exists with account id {$this->currProfile[ 'oz_uid' ]}";
			$oz->logData( $this->errorId , $this->errorMsg );
			$this->close();
			return false;
		}
		/*** Feature added 8/13/2011 : Try to protect from multiple registrations ***/
		$Database->setTableName( $this->getTable() );
		if ( $oz->getSapi() == 'web' ) {
			if ( $Database->query( '*' , "WHERE oz_registeripprotection='{$_SERVER[ 'REMOTE_ADDR' ]}'" ) ) {
				$this->errorId = 'ERR0703';
				$this->errorMsg = "Error: Profile->add(): Multiple accounts from same source ip {$_SERVER[ 'REMOTE_ADDR' ]}";
				$oz->logData( $this->errorId , $this->errorMsg );
				return false;
			}
		}
		if ( !empty( $ozConfig[ 'Profile' ][ 'Add.ReserveNicks' ] ) ) {
			if ( in_array( $data[ 'oz_uid' ] , $ozConfig[ 'Profile' ][ 'Add.ReserveNicks' ] ) ) {
				$this->errorId = 'ERRx0119';
				$this->errorMsg = "Error: Profile->add(): {$data[ 'oz_uid' ]} is reserved";
				$oz->logData( $this->errorId , $this->errorMsg );
				return false;
			}
		}
		
		if ( $this->profileType == 'admin' ) {
			switch( @$ozConfig[ 'Profile' ][ 'Default.Add.ClientStatus' ] ) {
				case 1;
					$status = 'Pending';
				break;
				default:
					$status = 'Active';
				break;
			}
		} else {
			switch( @$ozConfig[ 'Profile' ][ 'Default.Add.ClientStatus' ] ) {
				case 2:
					$status = 'Active';
				break;
				default:
					$status = 'Pending';
				break;
			}
		}
		$data[ 'oz_status' ]        = $status;
		$data[ 'oz_time_creation' ] = time();
		$data[ 'oz_accounttype' ]   = $this->profileType;
		if ( $this->profileType == 'admin' ) {
			$data[ 'oz_trialstatus' ] = 'Passed';
			$data[ 'oz_time_trialcomplete' ] = time();
		} else {
			$data[ 'oz_trialstatus' ] = 'Pending';
		}		
		if ( $oz->getSapi() == 'web' ) {
			$data[ 'oz_registeripprotection' ] = $_SERVER[ 'REMOTE_ADDR' ];
		}
		
		$Database->insert( $data );
		if ( $Database->errorId == 'ERR0401' ) {
			$oz->logData( $Database->errorId , 'Error: Profile->add(): ' . $Database->errorMsg );
			return false;
		}
		
		/* Set newly created profile to active */
		$this->currProfile = $data;
		return true;
	}
	
	public function update( $data )
	{
		global $zppZDatabase, $oz;
		if ( empty( $data ) )
			return false;
		if ( !$this->profileOpen() )
			return false;
		
		$zppZDatabase->update( $data , 'WHERE oz_uid = \'' . $this->currProfile[ 'oz_uid' ] . '\'' );
		if ( $zppZDatabase->errorId == 'ERR0401' ) {
			$oz->logData( $zppZDatabase->errorId , 'Error: Profile->update(): ' . $this->currProfile[ 'oz_uid' ] . ': ' . $zppZDatabase->errorMsg );
			return false;
		}
		return true;
	}
	
	public function ModifyProfile( $type , $data , $reason )
	{
		global $zppZDatabase, $Database, $oz;
		
		$Database->errorId = null;
		$data[ ':oz_uid' ] = $this->currProfile[ 'oz_uid' ];
		$Database->ExecuteStoredQuery( $type , $data );
		if ( $Database->errorId == 'ERR0401' ) {
			$oz->logData( $Database->errorId , 'Error: Profile->ModifyProfile(): Account ' . $this->currProfile[ 'oz_uid' ] . ': Failed at updating profile: ' . $Database->errorMsg , array( 'file' => __FILE__ , 'line' => __LINE__ ) );
			return false;
		}
		//$this->currProfile = array_merge( $this->currProfile , $data );
		$this->getByName( $this->currProfile[ 'oz_uid' ] , true );
		
		
		/* Series of checks */
		if ( empty( $reason ) )
			$reason = 'Unknown';
			
		$cols[ 'oz_clientid' ] = $this->currProfile[ 'ozid' ];
		$cols[ 'oz_time_mod' ] = time();
		$cols[ 'oz_mod_user' ] = $_SESSION[ 'profile' ][ 'oz_uid' ];
		$cols[ 'oz_mod' ]      = $reason;
		$zppZDatabase->setTableName( 'oz_account_mods' );
		$zppZDatabase->insert( $cols );
		
		if ( $zppZDatabase->errorId == 'ERR0401' ) {
			$oz->logData( $zppZDatabase->errorId , 'Error: Account ' . $this->currProfile[ 'oz_uid' ] . ': Profile->ModifyProfile(): ' . $zppZDatabase->errorMsg , array( 'file' => __FILE__ , 'line' => __LINE__ ) );
			return false;
		}
		return true;
	}
	
	public function close()
	{
		$this->currProfile = null;
	}
	
	public function getTable()
	{
		return 'oz_accounts';
	}
}

$ozInitThisExtension = true;
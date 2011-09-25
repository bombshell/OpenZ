<?php

class CliInterface
{
	public $mLockFile;
	
	/**
	 * 
	 * Display Account information to stdout
	 * 
	 */
	public function DisplayAccount()
	{
		global $ozProfile, $oz, $ozSystem, $ozConfig, $Database, $ozAdminGroup, $ozUserManagement;
		
		/*** Lock profile from being edited by someone else ***/
		
		
		while(true) {
			/*** Default Values ***/ 
			$userOnSystem = $ozSystem->userExists( $ozProfile->GetField( 'oz_system_uid' ) );
			$is_myaccount = ( $ozProfile->GetField( 'oz_uid' ) == $_SESSION[ 'auth_uid' ] ) ? true : false;
			$email_valid = ( $ozProfile->GetField( 'oz_emailvalid' ) == '1' ) ? 'Yes' : 'No'; 
			$shellactivated = ( $ozProfile->GetField( 'oz_time_shellactivated' ) ) ? "(" . $ozProfile->GetField( 'oz_admin_shellactivated' ) . ") " . $oz->timeToStr( $ozProfile->GetField( 'oz_time_shellactivated' ) ) : 'N/A';
			$adminlevel = ( $ozProfile->GetField( 'oz_level' ) != "" ) ? $ozAdminGroup[ $ozProfile->GetField( 'oz_level' ) ][ 'Name' ] : 'N/A';
			
			
			/*
			if ( !empty( $profile[ 'oz_custom_admin_vouched' ] ) ) {
				$vouched = $profile[ 'oz_custom_admin_vouched' ] . ' (' . $oz->timeToStr( $profile[ 'oz_custom_time_vouched' ] ) . ')';
			} else {
				$vouched = 'None';
			}*/
			
			Console::showTitle( 'Profile...' );
			pf( 'Username: ' . str_pad( $ozProfile->GetField( 'oz_uid' , true ) , 23 ) . 'Realname: ' . $ozProfile->GetField( 'oz_realname' , true ) );
			pf( 'IRC Nick: ' . str_pad( $ozProfile->GetField( 'oz_ircnick' , true ) , 15 ) );
			pf( 'Account Created: ' .  $oz->timeToStr( $ozProfile->GetField( 'oz_time_creation' , true ) ) );
			pf( 'Email: ' . str_pad( $ozProfile->GetField( 'oz_email' , true ) , 26 ) . 'Email Valid: ' . $email_valid );
			pf( 'Package: ' . str_pad( $ozProfile->package() , 15 ) );
			pf( 'Trial Status: ' . $ozProfile->GetField( 'oz_trialstatus' , true ) );
			pf( 'Account Status: ' . str_pad( $ozProfile->GetField( 'oz_status' , true ) , 17 ) . 'Lock Reason: ' . $ozProfile->LockStatus() );
			pf( 'Shell Activated: ' . $shellactivated );
			pf( 'Administrator Level: ' . $adminlevel );
			
			/***
			 * Shell Reason
			 */
			pf( "\nShell Reason:" );
			pf( $ozProfile->GetField( 'oz_shellreason' , true ) );
			pf();
			
			/*** 
			 * Show Last 1 Note
			 */
			pf( "\nNotes:" );
			$Database->setTableName( 'oz_account_notes' );
			$notes = $Database->lastRowsBy( 'noteid' , '2' , "WHERE note_to = '" . $ozProfile->GetField( 'oz_uid' ) . "'" );
			if ( !empty( $notes ) ) {
				foreach( $notes as $note ) {
					pf( 'Administrator: ' . $note[ 'note_sender' ] . ' Date/Time: ' . $oz->timeToStr( $note[ 'note_time' ] ) . "\n--" );
					pf( wordwrap( $note[ 'note_body' ] , 50 , "\n" ) . "\n--" );
				}
			} else {
				pf( 'None..' );
			}
			//print "\n";
			
			/***
			 * Show Last Login
			 */
			if ( $last_login = $ozSystem->LastLogin( $ozProfile->GetField( 'oz_uid' )) ) {
				pf( "\nLast Login:" );
				pf( $last_login );
			}
			
			/***
		 	* Show Options
			 */
			pf("\n");
			$options = array();
			if ( $ozProfile->getType() == 'client' && !$userOnSystem ) {
				$options[ 'a' ] = 'Activate';
			} else {
				if ( !$is_myaccount ) {
					/*** Only Show these options if it's not My Account ***/
					if ( $ozProfile->getType() == 'client' || $_SESSION[ 'auth_acc_level' ] == '0' ) {
						/*** Show Lock or Unlock in the following conditions: If this is Admin Type, only root can lock/unlock
					 	or if this is client type, only Admins is with Lock Privilege can Lock ***/
						if ( $ozUserManagement->hasPriviledge( 'Lock' ) ) {
							if ( $ozProfile->isLocked() ) {
								$options[ 'u' ] = 'Unlock Account';
							} else {
								$options[ 'l' ] = 'Lock Account';
							}
						}
					}
					if ( $ozProfile->getType() == 'client' &&  !$isLocked && $this->hasPriviledge( 'SwitchPackage' ) )
						$options[ 's' ] = 'Switch Package';
						
					/*** Feature Added: Account Trial support ***/
					if ( empty( $profile[ 'oz_time_trialcomplete' ] ) && !$ozProfile->isLocked() ) {
						$options[ 'p'] = 'Pass trial';
					}
					
					/*** Feature Added 09/05/2011: Able to kill user processes ***/
					if ( ( $ozUserManagement->hasPriviledge( 'KillProcesses' ) && $ozProfile->getType() != 'admin' ) || $ozProfile->GetField( 'oz_level' ) == '0' ) {
						$options[ 'k' ] = 'Kill User Process(es)';
					}
				}
				
				if ( !$ozProfile->IsLocked() ) {
					$options[ 'r' ] = 'Reset Password';
				}
				
				/*** Only the Administrator greater then the current can view notes and has the view other admins notes privelege ***/
				if ( ( $_SESSION[ 'auth_acc_level' ] < $ozProfile->GetField( 'oz_level' ) && $ozUserManagement->hasPriviledge( 'View.OtherAdminsNotes' ) ) || $is_myaccount || $ozProfile->getType() == 'client' ) {
					$options[ 'n' ] = 'Notes';
				}
				$options[ 'm' ] = 'All Modifications';
				
			}
			$options[ 'c' ] = 'Cancle';
			$option = Console::showOptionForm( $options , true , true );
		
			if ( $option == 'c' ) { 
				$this->close();
				break;
			} elseif ( $option == 'r' && $is_myaccount ) {
				$this->showChangeAdminPasswordForm();
			} elseif ( !empty( $options[ $option ] ) ) {
				$this->doOption( $option );
				pf();
				Console::pause();
			}	
		}
	}
	
	/**
	 * 
	 * Display a lookup form used to lookup accounts
	 * 
	 */
	public function DisplayLookupForm()
	{
		global $ozProfile, $ozConsole, $oz, $ozUserManagement;
		
		$profile_type = $ozProfile->getType();
		
		while(true) {
			Console::showTitle( 'Account Lookup...' );
			pf( 'Type m to go back to the previous menu' );
			$username = Console::showInput( 'Account username to retrieve' );
			if ( $username == 'm' ) {
				return;
			}
			/* Add a link break */
			pf();
	
			if ( $ozProfile->GetAccountByName( $username ) ) {
				if ( $ozProfile->GetField( 'oz_accounttype' ) == 'admin' ) {
					if ( !$ozUserManagement->hasPriviledge( 'View.Admins' ) ) {
						pf( 'Error: Permission Denied to view this account' );
						$ozProfile->close();
						Console::pause();
						continue;
					}
				}
				if ( $ozProfile->GetField( 'oz_uid' ) == $_SESSION[ 'auth_uid' ] ) {
					pf( 'Error: Please use My Account to view your account information' );
					$ozProfile->close();
					Console::pause();
					continue;
				}
				
				/***
 		 		 * Lock account to this Administrator
				***/

				/* Check if Account is already locked */
				if ( $admin = $this->isLocked() ) {
					pf( 'Account is currently locked for modification by ' . $admin );
					pf( 'If this is a mistake, delete ' . $this->lockFile );
					Console::pause();
				} elseif ( !$this->lockUserProfile() ) {
					pf( 'Unable to lock acount' );
					Console::pause();
				} else {
					$this->showProfile();
				}
			} else {
				print "\nError: Invalid User: Try Again\n";
				$ozProfile->close();
				Console::pause();
			}
		}
	}
	
	public function IsLocked()
	{
		global $oz, $ozProfile;
		$this->mLockFile = TEMP_DIR . '.account_locked_' . $ozProfile->getField( 'oz_uid' );
			
		/* BUG Fixed: 6/27/2011 : Both functions cache data, clear cache */
		@clearstatcache( true , $this->lockFile );
		if ( is_file( $this->mLockFile ) ) {
			$admin = $oz->fileRead( $this->mLockFile );
			if ( $admin != $_SESSION[ 'auth_uid' ] ) {
				return $admin;
			}
		}
		return false;
	}
}

$ozInitThisExtension = true;
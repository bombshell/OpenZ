<?php
class UserManagement
{
	public $lockFile;
	
	public function __construct()
	{
		global $ozConsole, $Database, $ozProfile;
		$ozConsole->addQuitHook( 'User Management' , $this );
		
		$Database->setTableName( $ozProfile->getTable() );
		$Database->StoreQueryInCache( 'UM_ACTIVATE' , $Database->BuildUpdateQuery( array( 'oz_status' , 'oz_admin_shellactivated' , 'oz_time_shellactivated' , 'oz_packageid' , 'oz_trialstatus' ) , 'WHERE oz_uid = :oz_uid' ) );
		$Database->StoreQueryInCache( 'UM_CHANGE_PACKAGE' , $Database->BuildUpdateQuery( array( 'oz_packageid' ) , 'WHERE oz_uid = :oz_uid' ) );
		$Database->StoreQueryInCache( 'UM_PASS_TRIAL' , $Database->BuildUpdateQuery( array( 'oz_trialstatus' , 'oz_time_trialcomplete' ) ,  'WHERE oz_uid = :oz_uid' ) );
		$Database->StoreQueryInCache( 'UM_CHANGE_PASS' , $Database->BuildUpdateQuery( array( 'oz_pwd' ) , 'WHERE oz_uid = :oz_uid' ) );
	}
	
	public function showProfile( $myAccount = false )
	{
		global $ozProfile, $oz, $ozSystem, $ozConfig, $Database, $ozAdminGroup;
		
		while(true) {
			/*** Default Values ***/
			$profile      = $ozProfile->getAllFields(); 
			$userOnSystem = $ozSystem->userExists( $profile[ 'oz_system_uid' ] );
			$isLocked     = $ozProfile->isLocked();
			//$myAccount  = ( $ozProfile[ 'oz_uid' ] == $_SESSION[ 'profile' ][ 'oz_uid' ] ) ? true : false;

			$username   = ( !empty( $profile[ 'oz_uid' ] ) ) ? $profile[ 'oz_uid' ] : 'Unknown';
			$realname   = ( !empty( $profile[ 'oz_realname' ] ) ) ? $profile[ 'oz_realname' ] : 'Unknown';
			$email      = ( !empty( $profile[ 'oz_email' ] ) ) ? $profile[ 'oz_email' ] : 'Unknown';
			$status     = ( !empty( $profile[ 'oz_status' ] ) ) ? $profile[ 'oz_status' ] : 'Unknown';
			$ircnick    = ( !empty( $profile[ 'oz_ircnick' ] ) ) ? $profile[ 'oz_ircnick' ] : 'N/A';
			$emailvalid = ( @$profile[ 'oz_emailvalid' ] == '1' ) ? 'Yes' : 'No'; 
			$accountcreated = $oz->timeToStr( $profile[ 'oz_time_creation' ] );
			$shellactivated = ( !empty( $profile[ 'oz_time_shellactivated' ] ) ) ? "({$profile[ 'oz_admin_shellactivated' ]}) " . $oz->timeToStr( $profile[ 'oz_time_shellactivated' ] ) : 'N/A';
			$adminlevel = ( $profile[ 'oz_level' ] != "" ) ? $ozAdminGroup[ $profile[ 'oz_level' ] ][ 'Name' ] : 'N/A';
			
			if ( $status == 'Suspended' ) {
				if ( $profile[ 'oz_lockedreason' ] == 'o' ) {
					$reason = 'Other, see admin';
				} elseif( $profile[ 'oz_lockedreason' ] == 'f' || $profile[ 'oz_lockedreason' ] == 'tf' ) {
					$reason = 'Failed Trial';
				} elseif( $profile[ 'oz_lockedreason' ] == 'te') {
					$reason = 'Trial Expired';
				} elseif( !empty( $ozConfig[ 'LockReasons' ][ $profile[ 'oz_lockedreason' ] ] ) ) {
					$reason = $ozConfig[ 'LockReasons' ][ $profile[ 'oz_lockedreason' ] ];
				} else {
					$reason = 'Unknown';
				}
				$reason = "$reason (({$profile[ 'oz_admin_locked' ]})" . $oz->timeToStr( $profile[ 'oz_time_locked' ] ) . ')';	
			} else {
				$reason = 'N/A';
			}
				
			$package = $ozProfile->package();
			if ( empty( $package ) )
				$package = 'Unassigned';
			
			/*
			if ( !empty( $profile[ 'oz_custom_admin_vouched' ] ) ) {
				$vouched = $profile[ 'oz_custom_admin_vouched' ] . ' (' . $oz->timeToStr( $profile[ 'oz_custom_time_vouched' ] ) . ')';
			} else {
				$vouched = 'None';
			}*/
			
			Console::showTitle( 'Profile...' );
			print 'Username: ' . str_pad( $username , 23 ) . 'Realname: ' . $realname . "\n";
			print 'IRC Nick: ' . str_pad( $ircnick , 15 ) . "\n";
			print 'Account Created: ' . $accountcreated . "\n";
			print 'Email: ' . str_pad( $email , 26 ) . 'Email Valid: ' . $emailvalid . "\n";
			print 'Package: ' . str_pad( $package , 15 ) . "\n";
			print 'Trial Status: ' . $profile[ 'oz_trialstatus' ] . "\n";
			print 'Account Status: ' . str_pad( $status , 17 ) . 'Lock Reason: ' . $reason . "\n";
			print 'Shell Activated: ' . $shellactivated . "\n";
			print 'Administrator Level: ' . $adminlevel . "\n";
			
			/***
			 * Shell Reason
			 */
			print "\nShell Reason:\n";
			print ( !empty( $profile[ 'oz_shellreason' ] ) ) ? $profile[ 'oz_shellreason' ] : 'None';
			print "\n";
			
			/*** 
			 * Show Last 1 Note
			 */
			print "\nNotes\n";
			$Database->setTableName( 'oz_account_notes' );
			$notes = $Database->lastRowsBy( 'noteid' , '2' , "WHERE note_to = '$username'" );
			if ( !empty( $notes ) ) {
				foreach( $notes as $note ) {
					print 'Administrator: ' . $note[ 'note_sender' ] . ' Date/Time: ' . $oz->timeToStr( $note[ 'note_time' ] ) . "\n--\n";
					print wordwrap( $note[ 'note_body' ] , 50 , "\n" ) . "\n--";
					/*** Mark Note As Read ***/
					//$Database->update( array( 'note_read' => '1' ) , "WHERE noteid = '{$note[ 'noteid' ]}'" );
				}
			} else {
				print 'None Unread..';
			}
			print "\n";
			
			/***
			 * Show Last Login
			 */
			if ( $userOnSystem ) {
				print "\nLast Login\n";
				print $ozSystem->shellexec( "lastlog -u {$profile[ 'oz_system_uid' ]}" );
			}
			
			/***
		 	* Show Options
			 */
			pf("\n");
			$options = array();
			if ( $ozProfile->getType() == 'client' && !$userOnSystem ) {
				$options[ 'a' ] = 'Activate';
			} else {
				if ( !$myAccount ) {
					/*** Only Show these options if it's not My Account ***/
					if ( $ozProfile->getType() == 'client' || $_SESSION[ 'profile' ][ 'oz_level' ] == '0' ) {
						/*** Show Lock or Unlock in the following conditions: If this is Admin Type, only root can lock/unlock
					 	or if this is client type, only Admins is with Lock Privilege can Lock ***/
						if ( $this->hasPriviledge( 'Lock' ) ) {
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
					if ( empty( $profile[ 'oz_time_trialcomplete' ] ) && !$isLocked ) {
						$options[ 'p'] = 'Pass trial';
					}
					
					/*** Feature Added 09/05/2011: Able to kill user processes ***/
					if ( ( $this->hasPriviledge( 'KillProcesses' ) && $ozProfile->getType() != 'admin' ) || $profile[ 'oz_level' ] == '0' ) {
						$options[ 'k' ] = 'Kill User Process(es)';
					}
				}
				
				if ( !$isLocked ) {
					$options[ 'r' ] = 'Reset Password';
				}
				
				/*** Only the Administrator greater then the current can view notes and has the view other admins notes privelege ***/
				if ( ( $_SESSION[ 'profile' ][ 'oz_level' ] < $profile[ 'oz_level' ] && $this->hasPriviledge( 'View.OtherAdminsNotes' ) ) || $myAccount || $ozProfile->getType() == 'client' ) {
					$options[ 'n' ] = 'Notes';
				}
				$options[ 'm' ] = 'All Modifications';
				
			}
			$options[ 'e' ] = 'Exit';
			$option = Console::showOptionForm( $options , true , true );
		
			if ( $option == 'e' ) { 
				$this->close();
				break;
			} elseif ( $option == 'r' && $myAccount ) {
				$this->showChangeAdminPasswordForm();
			} elseif ( !empty( $options[ $option ] ) ) {
				$this->doOption( $option );
				pf('');
				Console::pause();
			}	
		}
	}
	
	
	/**
	 * 
	 * Shows Account lookup prompt based on
	 * profile type and retrieves profile
	 * @access Public
	 * @return (null)
	 * 
	 */
	public function showAccountLookup()
	{
		global $ozProfile, $ozConsole, $oz;
		
		$profile_type = $ozProfile->getType();
		
		while(true) {
			Console::showTitle( 'Account Lookup...' );
			pf( 'Type m to go back to the previous menu' );
			$username = Console::showInput( 'Account username to retrieve' );
			$ozConsole->shouldQuit( $username );
			if ( $username == 'm' ) {
				return;
			}
			/* Add a link break */
			pf('');
	
			if ( $ozProfile->getByName( $username ) ) {
				if ( $ozProfile->getField( 'oz_accounttype' ) == 'admin' ) {
					if ( !$this->hasPriviledge( 'View.Admins' ) ) {
						pf( 'Error: Permission Denied to view this account' );
						$ozProfile->close();
						Console::pause();
						continue;
					}
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
	
	public function doOption($option)
	{
		global $ozProfile, $oz, $ozConsole, $ozConfig, $ozSystem, $ozCommands, $zppZDatabase, $ozEmail, $Database;
		$admin = $_SESSION[ 'profile' ];
		
		switch( $option ) {
			case 'a':
				
				Console::showPleaseWait();
				
				/*** Series of verification checks ***/
				if ( $ozProfile->getType() != 'client' ) {
					pf( 'Failed' );
					pf( 'Error: Activation only permitted for Client Accounts from this location' );
					return false;
				}	
				if ( $ozSystem->userExists( $ozProfile->getUid() ) ) {
					pf( 'Failed' );
					pf( 'Error: User already exists on the system' );
					return false;
				}
				if ( !$this->HasPriviledge( 'Activate' ) ) {
					pf( 'Failed' );
					pf( 'Error: Permission denied by configuration' );
					return false;
				}
				if ( $ozProfile->isLocked() ) {
					pf( 'Failed' );
					pf( 'Error: Account is locked' );
					return false;
				}
				/*
				if ( @$ozConfig[ 'Activate' ][ 'CheckVouch' ] ) {
					$vouch = $ozProfile->getField( 'oz_custom_admin_vouched' );
					if ( empty( $vouch ) ) {
						pf( 'Failed' );
						pf( 'Error: Permission denied by configuration: User is not vouched' );
						return false;
					}
				}*/
				
				Console::showProgressBar();
				
				/*** Process set instructions to create Account on the system ***/
				
				/* Check if there's any packages configured */
				if ( !empty( $ozConfig[ 'Package' ][ 'Default' ] ) )
					$packageid = $ozConfig[ 'Package' ][ 'Default' ];
				else 
					$packageid = 1;
				if ( empty( $ozConfig[ 'Package' ][ $packageid ] ) ) {
					pf( 'Failed' );
					pf( 'Error: No package(s) configured' );
					return false;
				}
					
				/* Run commands */
				Console::showProgressBar();
				$groups = ( !empty( $ozConfig[ 'Package' ][ $packageid ][ 'SystemGroups' ] ) ) ?
					$ozConfig[ 'Package' ][ $packageid ][ 'SystemGroups' ] :
					null;
				if ( !$ozSystem->useradd( $ozProfile->getField( 'oz_system_uid' ) , 
										$ozConfig[ 'Package' ][ $packageid ][ 'SystemGroup' ] , 
										$ozProfile->getField( 'oz_realname' ) , 
										$groups ) ) {
					pf( 'Failed' );
					pf( 'Failed to create System Account' );
					return false;						
				}

				/* Set Quota */
				Console::showProgressBar();
				if ( !empty( $ozConfig[ 'Package' ][ $packageid ][ 'Quota' ] ) ) {
					if ( !$ozSystem->setquota( $ozProfile->getField( 'oz_system_uid' ) , 
											 $ozConfig[ 'Package' ][ $packageid ][ 'Quota' ] ) ) {
						pf('Failed');
						pf('Failed to set Quota');
						return false;		 	
					}					 
				}
				
				/* Update profile */
				Console::showProgressBar();
				$data[ ':oz_status' ] = 'Active';
				$data[ ':oz_admin_shellactivated' ] = $_SESSION[ 'profile' ][ 'oz_uid' ];
				$data[ ':oz_time_shellactivated' ] = time();
				$data[ ':oz_packageid' ] = $packageid;
				$data[ ':oz_trialstatus' ] = 'On Trial';
				if ( !$ozProfile->modifyProfile( 'UM_ACTIVATE' , $data , 'Shell Activated' ) ) {
					pf('Failed');
					pf('Unable to update databasse');
					return false;
				}
				
				/* Set and Send Password */
				Console::showProgressBar();
				if ( !$this->setPassword() ) {
					pf('Failed');
					pf('Failed to create password');
					return false;
				}
					
				/***
				 * Run POST hooks
				 */
				$this->runPostActivateHooks();
					
					
				pf('Done');
				return true;
					
				
			break;
			
			case 'l':
				if ( !$ozProfile->isLocked() ) {
					Console::showTitle( 'Lock Client Account...' );
					$ozConfig[ 'LockReasons' ][ 'tf' ] = 'Failed Trial';
					$ozConfig[ 'LockReasons' ][ 'te' ] = 'Trial Expired';
					$ozConfig[ 'LockReasons' ][ 'o' ] = 'Other See Admin';
					$option = Console::showOptionForm( $ozConfig[ 'LockReasons' ] );
					
					Console::showPleaseWait();
					/***
					 * Only root can lock admin accounts
					 */
					if ( $ozProfile->getType() == 'admin' ) {
						if ( $_SESSION[ 'profile' ][ 'oz_level' ] != '0' ) {
							pf('Failed');
							pf('Error: Permission denied');
							return false;
						}
					}
					
					/***
				 	 * Update Database
				 	 */
					if ( !$ozProfile->lock( $option ) ) {
						pf('Failed');
					} else {
						Console::showProgressBar();
						if ( !$ozSystem->lockpasswd( $ozProfile->getField( 'oz_system_uid' ) ) ) {
							pf('Failed');
						}
						Console::showProgressBar();
						if ( !$ozEmail->sendType( 'account_locked' , array( 'Locked Reason' => $ozConfig[ 'LockReasons' ][ $option ] ) ) ) {
							pf( 'Failed' );
							pf( 'Check logs...' );
							return false;
						}
						if ( !$ozSystem->killall( $ozProfile->getField( 'oz_system_uid' ) ) === false ) {
							pf( "\nUser still has running processes" );
						}
						pf('Done');
					}
				} else {
					/*** We're assuming that the account is already locked ***/
					pf('Error: Unable to lock account');
				}
			break;
			
			case 'u':
				/* Before unlocking , only root can superseed and unlock, otherwised, the adminsitrator who locked the account can
				 * Unlock the account */
				Console::showPleaseWait();
				if ( $_SESSION[ 'profile' ][ 'oz_level' ] != '0' ) {
					if ( $_SESSION[ 'profile' ][ 'oz_uid' ] != $ozProfile->getField( 'oz_admin_locked' ) ) {
						pf('Failed');
						pf('Error: Permission Denied: Need root access or ' . $ozProfile->getField( 'oz_admin_locked' ) . ' to unlock this account' );
						return false;
					}
				}
				Console::showProgressBar();
				if ( !$ozProfile->unlock() || !$ozSystem->unlockpasswd( $ozProfile->getField( 'oz_system_uid' ) )  ) {
					pf('Failed');
					pf('Error: Unable unlock account, check logs');
				}
				pf('Done');
				return true;
			break;	
			
			case 's':
				if ( $ozProfile->getType() == 'client' ) {
					Console::showTitle( 'Select Package...' );
					foreach ( $ozConfig[ 'Package' ] as $packageid => $properties ) {
						if ( is_int( $packageid ) )
							$optionForm[ $packageid ] = $properties[ 'Name' ];
					}
					$option = Console::showOptionForm( $optionForm );
				
					Console::showPleaseWait();
					if ( !$ozProfile->modifyProfile( 'UM_CHANGE_PACKAGE' , array( ':oz_packageid' => $option ) , 'Changing Account Package ID to ' . $option ) ) {
						pf('Failed');
						pf('Check logs...');
						return false;
					}
					Console::showProgressBar();
					if ( !$ozSystem->usermod( $ozProfile->getUid() , $ozConfig[ 'Package' ][ $option ][ 'SystemGroup' ] , $ozConfig[ 'Package' ][ $option ][ 'SystemGroups' ] ) ) {
						pf('Failed');
						pf('Check logs...');
						return false;
					}
					Console::showProgressBar();
					if ( !$ozEmail->sendType( 'package_changed' , array( 'Package' => $ozConfig[ 'Package' ][ $option ][ 'Name' ] ) ) ) {
						pf( 'Failed' );
						pf( 'Check logs...' );
						return false;
					}
					
					pf('Done');
					return true;
				}
			break;
			
			case 'r':
				Console::showPleaseWait();
				if ( $ozProfile->isLocked() ) {
					pf( 'Failed' );
					pf( 'Account is locked' );
				} elseif ( !$this->setPassword() ) {
					pf('Failed');
					pf('Check logs');
				}
				pf('Done');
			break;
			
			case 'n':
				$noteMenu[1][ 'Name' ] = 'Add Note';
				$noteMenu[2][ 'Name' ] = 'View All';
				
				while(true) {
					$option = Console::showMenu( 'Notes...' , $noteMenu );
					
					if ( $option == 'm' ) {
						break;
					} elseif ( $option == '1' ) {
						Console::showTitle( 'Add Note...' );
						pf('When completed with the message, type EOL on it\'s own line...' );
						while( $text = Console::getInput() ) {
							@$body .= $text . "\n";
							if ( $text == 'EOL' ) {
								break;
							}
						}
						pf('');
						$data = array( 'note_time' => time() ,
									   'note_read' => '0' ,
									   'note_sender' => $_SESSION[ 'profile' ][ 'oz_uid' ] ,
									   'note_to' => $ozProfile->getField( 'oz_uid' ) ,
						               'note_body' => $body );
						$zppZDatabase->insert( $data );
						if ( $zppZDatabase->errorId == 'ERR0401' ) {
							pf('Failed');
							pf('Error: Unable to insert into the database');
						} else {
							pf('Successful');
						}
					} else {
						Console::showTitle( 'All Notes...' );
						$username = $ozProfile->getField( 'oz_uid' );
						$zppZDatabase->setTableName( 'oz_account_notes' );
						$notes = $zppZDatabase->query( '*' , "WHERE note_to = '$username'" );
				
						if ( !empty( $notes ) ) {
							$i = 1;
							foreach( $notes as $note ) {
								pf( 'Administrator: ' . $note[ 'note_sender' ] . ' Date: ' . $oz->timeToStr( $note[ 'note_time' ] ) . "\n--" );
								pf( wordwrap( $note[ 'note_body' ] , 50 , "\n" ) . "\n--" )	;
								
								/*** Mark Note As Read ***/
								$zppZDatabase->update( array( 'note_read' => '1' ) , "WHERE noteid = '{$note[ 'noteid' ]}'" );							
								if ( $i <= $ozConfig[ 'Note' ][ 'Total.Displayed' ] ) {
									$i++;
								} else {
									$i=1;
									Console::pause();
								}
							}
						} else {
							pf( 'No Notes...' );
							Console::pause();
						}
					}
				}
				
			break;
			
			case 'm':
				Console::showTitle( 'All Modifications...' );
				
				$clientid = $ozProfile->getField( 'ozid' );
				$Database->setTableName( 'oz_account_mods' );
				$Modifications = $Database->query( '*' , "WHERE oz_clientid = '$clientid'" );

				if ( !empty( $Modifications ) ) {
					$i = 1;
					pf( 'Administrator     Time                        Modification' );
					foreach( $Modifications as $Modification ) {
						
						if ( $i <= $ozConfig[ 'AccountModifications' ][ 'TotalDisplayed' ] ) {
							$Mod[ 'user']  = str_pad( $Modification[ 'oz_mod_user' ] , 17 );
							$Mod[ 'time' ] = str_pad( $oz->timeToStr( $Modification[ 'oz_time_mod' ] ) , 27 );
							$Mod[ 'mod' ]  = $Modification[ 'oz_mod' ]; 
							pf( "{$Mod[ 'user' ]} {$Mod[ 'time' ]} {$Mod[ 'mod' ]}" );
							$i++;
						} else {
							$i=1;
							Console::pause();
							pf( 'Administrator     Time                        Modification' );
						}
					}
				} else {
					pf( 'No Modifications...' );
				}
			break;
			
			case 'p':
				Console::showPleaseWait();
				
				$activated_timestamp = $ozProfile->getField( 'oz_time_shellactivated' );
            	$trial_timestamp     = $activated_timestamp + 60*60*24*5;
            	$current_timestamp   = time();
            	if ( ( $trial_timestamp > $current_timestamp ) && $_SESSION[ 'profile' ][ 'oz_level' ] != '0' ) {
            		pf( 'Failed' );
            	 	pf( 'Error: Client hasn\'t completed trial: Permission Denied' );
            	} else {
					$data[ ':oz_trialstatus' ] = 'Passed';
					$data[ ':oz_time_trialcomplete' ] = time();
					if ( !$ozProfile->modifyProfile( 'UM_PASS_TRIAL' , $data , 'Passed Trial' ) ) {
						pf( 'Failed' );
						pf( 'Error: Failed to update profile: Check logs' );
					} else {
						pf('Done');
					}
            	}
			break;
			
			case 'k':
				/*** Build a List of Processes ***/
				Console::showTitle( 'Which process to kill?' );
				$processes = $ozSystem->GetUserProcesses( $ozProfile->getField( 'oz_system_uid' ) );
				if ( !empty( $processes ) ) {
					foreach( $processes as $proc ) {
						$menu[] = 'PID: ' . $proc[ 'pid' ] . ' CMD: ' . $proc[ 'cmd' ];
						$pid[]  = $proc[ 'pid' ];
					}
					$menu[ 'k' ] = 'Killall';
					$menu[ 'g' ] = 'Go back';
					$option = Console::showOptionForm( $menu );
					if ( $option == 'g' ) {
						break;
					}
					if ( $option == 'k' ) {
						if ( $ozSystem->killall( $ozProfile->getField( 'oz_system_uid' ) ) ) {
							pf( 'Sent killall signal to all process(es)' );
						} else {
							pf( 'Failed' );
							pf( 'Failed to kill all user processes. Check Logs' );
						}
					} else {
						if ( $ozSystem->kill( $pid[ $option ] ) ) {
							pf( "Sent kill signal to pid {$pid[ $option ]}..." );
						} else {
							pf( 'An error occurred, unable to kill process. Check Logs' );
						}
					}
				} else {
					pf( 'User has no running processes' );
				}
			break;
			
			default:
				pf('Failed');
				pf('Error: Invalid option');
			break;
		}
		
		return false;
	}
	
	public function hasPriviledge( $priv )
	{
		global $ozPermission;
		
		/* Root always has priviledge */
		if ( $_SESSION[ 'auth_acc_level' ] == '0' )
			return true;
		/* Check Priveledge by Administrator Username */
		if ( !empty( $ozPermission[ $_SESSION[ 'auth_uid' ] ][ $priv ] ) )
			return $ozPermission[ $_SESSION[ 'auth_uid' ] ][ $priv ];
		/* Check Priveledge by Administrator Level */
		if ( !empty( $ozPermission[ $_SESSION[ 'auth_acc_level' ] ][ $priv ] ) ) 
			return $ozPermission[ $_SESSION[ 'auth_acc_level' ] ][ $priv ];
		return false;
	}
	
	public function lockUserProfile()
	{
		global $oz , $ozProfile;
		$this->lockFile = $oz->getTempPath() . '.profile_locked_' . $ozProfile->getField( 'oz_uid' );
		
		/*** We assuming there already been a checked to verify this file already belongs to this session admin ***/
		if ( !is_file( $this->lockFile ) ) {
			return $oz->fileWrite( $_SESSION[ 'profile' ][ 'oz_uid' ] , $this->lockFile );
		}
		return true;	
	}
	
	public function isLocked()
	{
		global $oz, $ozProfile;
		$this->lockFile = $oz->getTempPath() . '.profile_locked_' . $ozProfile->getField( 'oz_uid' );
			
		/* BUG Fixed: 6/27/2011 : Both functions cache data, clear cache */
		@clearstatcache( true , $this->lockFile );
		if ( is_file( $this->lockFile ) ) {
			$admin = $oz->fileRead( $this->lockFile );
			if ( $admin != $_SESSION[ 'profile' ][ 'oz_uid' ] ) {
				return $admin;
			}
		}
		return false;
	}
	
	public function showChangeAdminPasswordForm()
	{
		global $ozProfile, $oz;
		
		Console::showTitle( 'Change My Password...' );
		pf('Notice: This only changes your openZ Administrative Password only');
		$password = Console::showPasswordForm();
		
		$data[ ':oz_pwd' ] = $oz->hash( $password );
		$ozProfile->setType( 'admin' );
		$ozProfile->getByName( $_SESSION[ 'profile' ][ 'oz_uid' ] );
		pf('');
		if ( !$ozProfile->modifyProfile( 'UM_CHANGE_PASS' , $data , 'Administrator Changed Password' ) ) {
			pf('Failed updating database...');
		} else {
			pf('Successfully Changed Password...');
		}	
		Console::pause();
		
	}
	
	public function showAdminForm()
	{
		global $ozConsole, $ozEmail, $oz, $ozAdminGroup, $ozProfile, $ozSystem;
		
		while(true) {
			Console::showTitle( 'Administrator Account...' );
			pf( 'Note: Usernames are strip to lowercase by default' );
			$data[ 'oz_uid' ]        = strtolower( Console::showInput( 'Administrator Username' ) );
			$data[ 'oz_system_uid' ] = &$data[ 'oz_uid' ];
			$data[ 'oz_realname' ]   = Console::showInput( 'Administrator First and Last Name' );
			$data[ 'oz_ircnick' ]    = Console::showInput( 'Administrator IRC Nick' ); 
			$data[ 'oz_emailvalid' ] = '1';
			$data[ 'oz_admin_shellactivated' ] = $_SESSION[ 'profile' ][ 'oz_uid' ];
			$data[ 'oz_time_shellactivated' ]  = time();
			
			/*** Generate Temporary Password ***/
			$openz_password          = $oz->strRandom();
			$data[ 'oz_pwd' ]        = $oz->hash( $openz_password );
			$data[ 'oz_pwd_requires_reset' ] = true;
			
			/* Verify E-Mail */
			while(true) {
				$email = Console::showInput( 'Administrator E-Mail' );
				if ( $ozEmail->validateEmail( $email ) ) {
					$data[ 'oz_email' ] = $email; 
					break;
				} else 
					pf( 'Error: Invalid E-Mail' );
			}
		
			/* Ask for Admin Group */
			foreach( $ozAdminGroup as $level => $properties )
				if ( $level != '0' ) 
					$formData[ $level ] = $properties[ 'Name' ];
			pf( "\nAdministrator Group" );
			$data[ 'oz_level' ] = Console::showOptionForm( $formData );
		
			if ( Console::showQuestion( 'Are you statisfied?' ) ) {
				/*** Check if User is not on the system first ***/
				if ( $ozSystem->userExists( $data[ 'oz_uid' ] ) ) {
					pf( "\nFailed..." );
					pf( "Account system already exists with username '{$data[ 'oz_uid' ]}'" );
				} else {
					pf( "\nUpdating Database..." );
					$ozProfile->setType( 'admin' );
					if ( $ozProfile->add( $data ) ) {
						/* Create system account */
						pf('Create system account...');
						/*** Check if this Administrator level has any system groups ***/
						if ( !empty( $ozAdminGroup[ $data[ 'oz_level' ] ][ 'SystemGroups' ] ) ) {
							$groups = $ozAdminGroup[ $data[ 'oz_level' ] ][ 'SystemGroups' ];
						} else {
							$groups = null;
						}
						if ( !$ozSystem->useradd( $data[ 'oz_uid' ] , 'staff' , $data[ 'oz_realname' ] , $groups ) || !$shell_password = $this->setPassword(true) ) {
							pf('Failed');
							pf('Check Command logs for more detials');
						} else {
							pf( 'Sending Email...' );
							$ozEmail->sendType( 'new_admin_pass' , array( 'OpenZ Password' => $openz_password , 'SSH Password' => $shell_password ) );
							/*** Run Post Hooks ***/
							$this->runPostActivateHooks();
							pf( 'Done' );
						}
						sleep(2);
						break;
					} else {
						if ( $ozProfile->errorId == 'ERR0703' ) {
							pf('Account already Exists');
						} else {
							pf( 'Failed...' );
							pf( 'Check logs for more detials' );
						}
					}
				}
				Console::pause();
			}
		}
		
		//exit;
		
		 
	}
	
	public function setPassword( $returnPassword = false )
	{
		global $oz, $ozEmail, $ozProfile, $ozSystem, $ozPassword;
		$password = $oz->strRandom();
		$username = $ozProfile->getField( 'oz_system_uid' );
		$username = ( empty( $username ) ) ? $ozProfile->getUid() : $username;
		
		$cmd = "echo '$password' | passwd --stdin " . $username;
		if ( !$ozSystem->exec($cmd)  )
			return false;
		/* Pasword Aging */
		if ( $ozProfile->getType() == 'admin' )
			$aging_group = 'Admin';
		else 
			$aging_group = 'Client';
		if ( !empty( $ozPassword[ $aging_group ][ 'PasswordAging' ] ) ) {
			if ( !$ozSystem->chage( $username , 
									$ozPassword[ $aging_group ][ 'Maximum.Days' ]  , 
									$ozPassword[ $aging_group ][ 'Maximum.Inactive' ] ) ) {
										
				return false;							
			}
		}
		$cmd = "chage -d 0 $username";
		if ( !$ozSystem->exec($cmd) )
			return false;
		if ( $returnPassword )
			return $password;
		if ( !$ozEmail->sendType( 'new_client_pass' , array( 'Shell Password' => $password ) ) )
			return false;
		return true;
	}
	
	public function runPostActivateHooks()
	{
		global $ozCommands, $ozSystem, $oz, $ozProfile;
		if ( !empty( $ozCommands[ 'Post.Activate' ] ) ) {
			$failed_post_hook = false;
			$replace[ '%oz_uid%' ] = $ozProfile->getUid();
			foreach( $ozCommands[ 'Post.Activate' ] as $cmd ) {
				$cmd = $oz->stripReplace( $replace , $cmd );
				if ( !$ozSystem->exec( $cmd ) ) {
					$failed_post_hook = true;
				}
			}
			if ( $failed_post_hook ) {
				pf("\nError: Failed to execute one or more post command(s): Check logs");
			}
		}
	}
	
	public function close()
	{
		global $oz, $ozProfile;
		
		if ( is_file( $this->lockFile ) ) {
			$user = $oz->fileRead( $this->lockFile );
			if ( $user == $_SESSION[ 'profile' ][ 'oz_uid' ] ) {
				unlink( $this->lockFile );
			}
		}
		$ozProfile->close();
	}
}

$ozInitThisExtension = true;

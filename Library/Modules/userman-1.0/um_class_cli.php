<?php
class UserManagment
{
	public $lockFile;
	
	public function __construct()
	{
		global $ozConsole;
		$ozConsole->addQuitHook( 'User Management' , $this );
	}
	
	public function showProfile( $myAccount = false )
	{
		global $ozProfile, $oz, $ozSystem;
		
		/*** Default Values ***/
		$profile      = $ozProfile->getAllFields();
		$userOnSystem = $ozSystem->userExists( $profile[ 'oz_uid' ] );

		$username = ( !empty( $profile[ 'oz_uid' ] ) ) ? $profile[ 'oz_uid' ] : 'Unknown';
		$realname = ( !empty( $profile[ 'oz_realname' ] ) ) ? $profile[ 'oz_realname' ] : 'Unknown';
		$email    = ( !empty( $profile[ 'oz_email' ] ) ) ? $profile[ 'oz_email' ] : 'Unknown';
		$status   = ( !empty( $profile[ 'oz_status' ] ) ) ? $profile[ 'oz_status' ] : 'Unknown';
			
		while(true) {
			Console::showTitle( 'Profile...' );
			print 'Username: ' . $username . '     Realname: ' . $realname . "\n";
		
			if ( !empty( $profile[ 'oz_ircnick'  ] ) )
				print 'IRC Nick: ' . $profile[ 'oz_ircnick' ] . "\n";
			
			print 'Account Created: ' . $oz->timeToStr( $profile[ 'oz_time_creation' ] ) . "\n";
			print 'Email: ' . $email; 
		
			if ( !empty( $profile[ 'oz_emailvalid' ] ) ) {
				print '     E-Mail Validated: ';
				if ( $profile[ 'oz_emailvalid' ] == '1' )
					print 'Yes';
				else 
					print 'No';
			}	
			print "\n";
		
			if ( !empty( $profile[ 'oz_packageid' ] ) ) {
				$package = $ozProfile->package();
				if ( empty( $package ) )
					$package = 'Unassigned';
				print 'Package: ' . $package . '     ';
			}
		
			print 'Account Status: ' . $status . "\n";
		
			$vouched = ( !empty( $profile[ 'oz_custom_vouched' ] ) ) ? $profile[ 'oz_custom_vouched' ] : 'None or N/A'; 
			print 'Vouched: ' . $vouched;
		
			if ( !empty( $profile[ 'oz_time_shellactivated' ] ) ) {
				print '     Shell Activated: ' . $oz->timeToStr( $profile[ 'oz_time_shellactivated' ] ); }
			
			/***
			 * Show Last Login
			 */
			if ( $userOnSystem ) {
				print "\n\nLast Login\n";
				print $ozSystem->shellexec( "lastlog -u {$profile[ 'oz_uid' ]}" );
			}
			
			/***
		 	 * Show Options
		 	 */
			pf('');
			pf('');		
			if ( $ozProfile->getType() == 'client' && !$userOnSystem )
				print 'Activate (a) \ ';
			else {
				if ( !$myAccount ) {
					if ( $ozProfile->isLocked() )
						print 'Unlock Account (u) \ ';
					else
						print 'Lock Account (l) \ ';
					print 'Switch Package (s) \ ';
				}
				print 'Reset Password (r) \ ';
			}
			print 'Exit (e)';
			pf('');
			$option = Console::showOptionInput();
		
			if ( !empty( $option ) ) {
				if ( $option == 'E' || $option == 'e' ) { 
					$this->close();
					break;
				} elseif ( ( $option == 'R' || $option == 'r' ) && $myAccount ) {
					$this->showChangeAdminPasswordForm();
				} else {
					$this->doOption( $option );
					pf('');
					Console::pause();
				}	
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
			if ( $profile_type == 'admin' )
				Console::showTitle( 'Administrator Lookup...' );
			else 
				Console::showTitle( 'Client Lookup...' );
			pf( 'Type m to go back to the previous menu' );
			$username = Console::showInput( 'Account username to retrieve' );
			$ozConsole->shouldQuit( $username );
			if ( $username == 'm' ) {
				return;
			}
			/* Add a link break */
			$oz->printf( '' );
	
			if ( $ozProfile->getByName( $username ) ) {
				/***
 		 		 * Lock account to this Administrator
				***/

				/* Check if Account is already locked */
				if ( $admin = $this->isLocked() ) {
					pf( 'Account is already locked by ' . $admin );
					pf( 'If this is a mistake, delete ' . $this->lockFile );
				} elseif ( !$this->lockUserProfile() ) {
					pf( 'Unable to lock acount' );
				} else {
					$this->showProfile();
				}
			} else {
				print "\nError: Invalid User: Try Again\n";
				$ozProfile->close();
			}
	
			Console::pause();
			Console::clear();
		}
	}
	
	public function doOption($option)
	{
		global $ozProfile, $oz, $ozConsole, $ozConfig, $ozSystem, $ozCommands;
		$admin = $_SESSION[ 'profile' ];
		
		switch( $option ) {
			case 'a':
				Console::showPleaseWait();
				Console::showProgressBar();
				
				/*** Security Checks ***/
				/* Verify Admin has the right to excute this option */
				if ( $this->HasPriviledge( 'Activate' ) ) {
					Console::showProgressBar();
					
					/* Check if account is locked */
					if ( $ozProfile->isLocked() ) {
						pf( 'Failed' );
						pf( 'Error: Account is locked' );
						return false;
					}
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
			
					/* Update profile */
					Console::showProgressBar();
					$data[ 'oz_status' ] = 'Active';
					$data[ 'oz_admin_shellactivated' ] = $_SESSION[ 'profile' ][ 'oz_uid' ];
					$data[ 'oz_time_shellactivated' ] = time();
					$data[ 'oz_packageid' ] = $packageid;
					if ( !$ozProfile->modifyProfile( $data , 'Shell Activated' ) ) {
						pf('Failed');
						pf('Unable to update databasse');
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
						pf('Failed to create System Account');
						return false;						
					}
					
					Console::showProgressBar();
					if ( !empty( $ozConfig[ 'Package' ][ $packageid ][ 'Quota' ] ) ) {
						if ( !$ozSystem->setquota( $ozProfile->getField( 'oz_system_uid' ) , 
											 $ozConfig[ 'Package' ][ $packageid ][ 'Quota' ] ) ) {
							pf('Failed');
							pf('Failed to set Quota');
							return false;		 	
						}					 
					}
					
					if ( !$this->setPassword() ) {
						pf('Failed');
						pf('Failed to create password');
						return false;
					}
					
					/***
					 * Run POST hooks
					 */
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
							pf("\nError: Failed to execute one or more POST command(s): Check logs");
						}
					}
					
					
					pf('Done');
					return true;
					
				} else {
					pf( 'Failed' );
					pf( 'Error: Permission denied by configuration' );
				}
				
				pf( '' );
				Console::pause();
			break;
			
			case 'l':
				if ( !$ozProfile->isLocked() ) {
					Console::showTitle( 'Lock Client Account...' );
					$option = Console::showOptionForm( $ozConfig[ 'LockReasons' ] );
					
					Console::showPleaseWait();
					/***
				 	 * Update Database
				 	 */
					if ( !$ozProfile->lock( $option ) ) {
						pf('Failed');
					} else {
						Console::showProgressBar();
						if ( !$ozSystem->lockpasswd( $ozProfile->getUid() ) ) {
							pf('Failed');
						}
					}
				} else {
					/*** We're assuming that the account is already locked ***/
					pf('Error: Unable to lock account');
				}
				
				Console::pause();
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
				if ( !$ozProfile->unlock() || !$ozSystem->unlockpasswd( $ozProfile->getField( 'oz_uid' ) ) ) {
					pf('Failed');
					pf('Error: Unable unlock account, check logs');
				}
				return true;
			break;	
					
			case 'R':
			case 'r':
				
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
		global $ozPriv;
		$admin = $_SESSION[ 'profile' ];
		
		/* Root always has priviledge */
		if ( $admin[ 'oz_level' ] == '0' )
			return true;
		/* Check Priveledge by Administrator Username */
		if ( !empty( $ozPermission[ $admin[ 'oz_uid' ][ $priv ] ] ) )
			return $ozPermission[ $admin[ 'oz_uid' ] ][ $priv ];
		/* Check Priveledge by Administrator Level */
		if ( !empty( $ozPermission[ $admin[ 'oz_level' ] ][ $priv ] ) ) 
			return $ozPermission[ $admin[ 'oz_level' ] ][ $priv ];
		return false;
	}
	
	public function lockUserProfile()
	{
		global $oz , $ozProfile;
		$this->lockFile = $oz->getTempPath() . '.profile_locked_' . $ozProfile->getField( 'oz_uid' );
		return $oz->fileWrite( $_SESSION[ 'profile' ][ 'oz_uid' ] , $this->lockFile );	
	}
	
	public function isLocked()
	{
		global $oz, $ozProfile;
		$this->lockFile = $oz->getTempPath() . '.profile_locked_' . $ozProfile->getField( 'oz_uid' );
		if ( empty( $this->lockFile ) )
			return false;
			
		/* BUG Fixed: 6/27/2011 : Both functions cache data, clear cache */
		clearstatcache( true , $this->lockFile );
		if ( is_file( $this->lockFile ) )
			return $oz->fileRead( $this->lockFile );
		return false;
	}
	
	public function showChangeAdminPasswordForm()
	{
		global $ozProfile, $oz;
		
		Console::showTitle( 'Change My Password...' );
		pf('Notice: This only changes your openZ Administrative Password only');
		$password = Console::showPasswordForm();
		
		$data[ 'oz_pwd' ] = $oz->hash( $password );
		$ozProfile->setType( 'admin' );
		$ozProfile->getByName( $_SESSION[ 'profile' ][ 'oz_uid' ] );
		pf('');
		if ( !$ozProfile->modifyProfile( $data , 'Administrator Changed Password' ) ) {
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
			$data[ 'oz_uid' ]      = trim( Console::showInput( 'Administrator Username' ) );
			$data[ 'oz_realname' ] = Console::showInput( 'Administrator First and Last Name' );
			
			/*** Generate Temporary Password ***/
			$openz_password = $oz->strRandom();
			$data[ 'oz_pwd' ] = $oz->hash( $openz_password );
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
				pf('');
				pf( 'Updating Database...' );
				$ozProfile->setType( 'admin' );
				if ( $ozProfile->add( $data ) ) {
					/* Create system account */
					pf('Create system account...');
					if ( !$ozSystem->useradd( $data[ 'oz_uid' ] , 'staff' , $data[ 'oz_realname' ] ) ) {
						pf('Failed');
						pf('Check Command logs for more detials');
					} elseif( !$shell_password = $this->setPassword(true) ) {
						pf('Failed');
						pf('Check Command logs for more detials');
					} else {
						pf( 'Sending Email...' );
						$ozEmail->sendType( 'new_admin_pass' , array( 'OpenZ Password' => $openz_password , 'Shell Password' => $shell_password ) );
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
					Console::pause();
				}
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
	
	public function close()
	{
		global $oz, $ozProfile;
		
		if ( is_file( $this->lockFile ) ) 
			unlink( $this->lockFile );
		$ozProfile->close();
	}
}

<?php
class UserManagment
{
	public $lockFile;
	
	public function __construct()
	{
		global $ozConsole;
		$ozConsole->addQuitHook( 'User Management' , $this );
	}
	
	public function showProfile()
	{
		global $ozProfile, $ozConsole, $oz, $um;
		
		/*** Default Values ***/
		$profile_type = $ozProfile->getType();
		
		$ozConsole->showTitle( 'Profile...' );
		print 'Username: ' . $ozProfile->getField( 'oz_uid' ) . '     Realname: ' . $ozProfile->getField( 'oz_realname' ) . "\n";
		
		if ( $profile_type == 'client' )
			print 'IRC Nick: ' . $ozProfile->getField( 'oz_ircnick' ) . "\n";
			
		print 'Account Created: ' . $oz->timeToStr( $ozProfile->getField( 'oz_time_creation' ) ) . "\n";
		print 'Email: ' . $ozProfile->getField( 'oz_email' ); 
		
		if ( $profile_type == 'client' ) {
			print '     E-Mail Validated: ';
			$email_valid = $ozProfile->getField( 'oz_emailvalid' );
			if ( !empty( $email_valid ) )
				if ( $email_valid == '1' )
					print 'Yes';
				else 
					print 'No';
		}
		print "\n";
		
		if ( $profile_type == 'client' ) {
			print 'Package: ' . $ozProfile->package();
			print '     Account Status: ' . $ozProfile->getField( 'oz_status' ) . "\n";
		
			$vouched = ( !empty( $vouched ) ) ? $ozProfile->getField( 'oz_custom_vouched' ) : 'None'; 
			print 'Vouched: ' . $vouched; 
			print '     Shell Activated: ' . $oz->timeToStr( $ozProfile->getField( 'oz_ircnick' ) );
			print "\n";
		}
	}
	
	public function showOptions()
	{
		print "\n" . '(A)ctivate \ (S)witch Package \ (L)ock Account \ (C)lose Account' . "\n";
	}
	
	public function doOption($option)
	{
		global $ozProfile, $oz, $ozConsole;
		$admin = $_SESSION[ 'profile' ];
		
		/* Show title */
		$ozConsole->showTitle( 'Please Wait...' );
		
		print 'Processing..';
		$ozConsole->progressBar();
		switch( $option ) {
			case 'A':
			case 'a':
				$ozConsole->progressBar();
				
				/*** Security Checks ***/
				/* Verify Admin has the right to excute this option */
				if ( $this->HasPriviledge( 'Activate' ) ) {
					$ozConsole->progressBar();
					
					/* Check if account is locked */
					if ( $ozProfile->isLocked() ) {
						$oz->printf( 'Failed' );
						$oz->printf( 'Error: Account is locked' );
						return false;
					}
					$ozConsole->progressBar();
					
					
					$ozProfile;
				} else {
					$oz->printf( 'Failed' );
					$oz->printf( 'Error: Permission denied by configuration' );
				}
				
				$oz->printf( '' );
				$ozConsole->pause();
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
		if ( is_file( $this->lockFile ) )
			return $oz->fileRead( $this->lockFile );
		return false;
	}
	
	public function showAdminForm()
	{
		global $ozConsole, $ozEmail, $oz, $ozAdminGroup;
		
		$ozConsole->showTitle( 'Administrator Account...' );
		$data[ 'oz_uid' ]      = $ozConsole->showInput( 'Administrator Username' );
		$data[ 'oz_realname' ] = $ozConsole->showInput( 'Administrator First and Last Name' );
		$data[ 'oz_time_creation' ] = time();
		$data[ 'oz_status' ] = 'Active';
		
		/* Verify E-Mail */
		while(true) {
			$email = $ozConsole->showInput( 'Administrator E-Mail' );
			if ( $ozEmail->validateEmail( $email ) ) {
				$data[ 'oz_email' ] = $email; 
				break;
			} else 
				$oz->printf( 'Error: Invalid E-Mail' );
		}
		
		/* Ask for Admin Group */
		foreach( $ozAdminGroup as $level => $properties )
			if ( $level != '0' ) 
				$formData[ $level ] = $properties[ 'Name' ];
		$oz->printf( "\nAdministrator Group" );
		$data[ 'oz_level' ] = $ozConsole->showOptionForm( $formData );
		
		exit;
		
		 
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
				$ozConsole->showTitle( 'Administrator Lookup...' );
			else 
				$ozConsole->showTitle( 'Client Lookup...' );
			$oz->printf( 'Type m to go back to the previous menu' );
			$username = $ozConsole->showInput( 'Account username to retrieve' );
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
					$oz->printf( 'Account is already locked by ' . $admin );
					$oz->printf( 'If this is a mistake, delete ' . $um->lockFile );
				} elseif ( !$this->lockUserProfile() ) {
					$oz->printf( 'Unable to lock acount' );
				} else {
					while(true) {
						$ozConsole->clear();
						$this->showProfile();
						
						/*** We not going to do anything with Administrator Account ***/
						if ( $profile_type == 'client' ) {
							$this->showOptions();
							print "\n" . 'Option: ';
							$option = $ozConsole->getInput();
							$ozConsole->shouldQuit( $option );
							if ( !empty( $option ) ) {
								if ( $option == 'C' || $option == 'c' ) { 
									$this->close();
									break;
								} else
									$this->doOption( $option );	
							}
						}	
					}
				}
			} else {
				print "\nError: Invalid User: Try Again\n";
				$ozProfile->close();
			}
	
			$ozConsole->pause();
			$ozConsole->clear();
		}
	}
	
	public function close()
	{
		global $oz, $ozProfile;
		
		if ( is_file( $this->lockFile ) ) 
			unlink( $this->lockFile );
		$ozProfile->close();
	}
}

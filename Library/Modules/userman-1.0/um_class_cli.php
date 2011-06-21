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
		$ozConsole->showTitle( 'Profile...' );
		$ozConsole->line();
		print 'Username: ' . $ozProfile->getField( 'oz_uid' ) . '| Realname: ' . $ozProfile->getField( 'oz_realname' ) . "\n";
		print 'IRC Nick: ' . $ozProfile->getField( 'oz_ircnick' ) . "\n";
		print 'Account Created: ' . $oz->timeToStr( $ozProfile->getField( 'oz_time_creation' ) ) . "\n";
		print 'Email: ' . $ozProfile->getField( 'oz_email' ) . ' E-Mail Validated: ';
		
		if ( $ozProfile->getField( 'oz_emailvalid' ) == '1' )
			print 'Yes';
		else 
			print 'No';
		
		print "\n" . 'Package: ' . $ozProfile->package() . ' Account Status: ' . $ozProfile->getField( 'oz_status' ) . "\n";
		
		$vouched = $ozProfile->getField( 'oz_custom_vouched' );
		$vouched = ( !empty( $vouched ) ) ? $ozProfile->getField( 'oz_custom_vouched' ) : 'None'; 
		print 'Vouched :' . $vouched; 
		print '| Shell Activated: ' . $oz->timeToStr( $ozProfile->getField( 'oz_ircnick' ) );
		print "\n";
	}
	
	public function showOptions()
	{
		print "\n" . '(A)ctivate \ (S)witch Package \ (L)ock Account \ (C)lose Account' . "\n";
	}
	
	public function doOption($option)
	{
		global $ozProfile, $oz;
		$admin = $_SESSION[ 'profile' ];
		
		switch( $option ) {
			case 'A':
			case 'a':
				/*** Security Checks ***/
				/* Verify Admin has the right to excute this option */
				if ( $this->HasPriviledge( 'Activate' ) ) {
					$ozProfile;
				} else {
					$oz->printf( 'Error: Permission denied by configuration' );
				}
				return false;
			break;
		}
	}
	
	public function hasPriviledge( $priv )
	{
		global $ozPriv;
		$admin = $_SESSION[ 'profile' ];
		
		/* Check Priveledge by Administrator Username */
		if ( !empty( $ozPriv[ $admin[ 'oz_uid' ][ $priv ] ] ) )
			return $ozPriv[ $admin[ 'oz_uid' ] ][ $priv ];
		/* Check Priveledge by Administrator Level */
		if ( !empty( $ozPriv[ $admin[ 'oz_level' ] ][ $priv ] ) ) 
			return $ozPriv[ $admin[ 'oz_level' ] ][ $priv ];
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
	
	public function close()
	{
		global $oz, $ozProfile;
		
		if ( is_file( $this->lockFile ) ) 
			unlink( $this->lockFile );
		$ozProfile->close();
	}
}

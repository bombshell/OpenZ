<?php

class Console
{
	public $quitHooks; 
	
	public static function getInput()
	{
		return trim( fread( STDIN , 1024 ) );
	}
	
	public static function clear()
	{
		system( 'clear' );
	}
	
	public static function showOptionInput()
	{
		print "\nOption: ";
		return self::getInput();
	}
	
	public static function showOptionForm( $formData , $showByRow = false , $disableVerificationCheck = false )
	{
		global $oz;
		
		if ( is_array( $formData ) ) {
			while(true) {
				if ( $showByRow ) {
					$options = null;
					foreach( $formData as $option => $name ) {
						if ( empty( $options ) ) {
							$options = "$name ($option) ";
						} else {
							$options .= "\ $name ($option) ";
						}
					}
					pf( $options );
				} else {
					foreach( $formData as $option => $name ) {
						pf( '   ' . $option . '. ' . $name );
					}
				} 
				$option = self::showOptionInput();
				if ( empty( $formData[ $option ] ) && $disableVerificationCheck == false ) {
					pf( 'Error: Invalid Option' );
					sleep(2);
				} else 
					break;
			}
			return $option;
		}
			
	}
	
	public static function showPasswordForm()
	{
		while(true) {
			shell_exec( 'stty -echo' );
			$password1 = self::showInput( 'New Password' );
			pf('');
			$password2 = self::showInput( 'Verify Password' );
			shell_exec( 'stty echo' );
			pf('');
			pf('');
			if ( $password1 == $password2 )
				return $password1;
			else 
				pf('Passwords do not match');
		}	
	}
	
	public static function pause()
	{
		print "\nPress enter to continue...";
		self::getInput();
	}
	
	public function addQuitHook( $objectName , $object )
	{
		if ( empty( $objectName ) )
			return false;
		if ( !is_object( $object ) )
			return false;
		$this->quitHooks[ $objectName ] = $object;
	}
	
	public function quit( $msg = null )
	{
		global $oz;
		
		if ( empty( $msg ) )
			$msg = "\n" . 'Quiting';
		pf( $msg );
		
		/* Run quit hooks */
		if ( !empty( $this->quitHooks ) ) {
			foreach( $this->quitHooks as $disc => $object ) {
				if ( $oz->debug >= 1 )
					$oz->logData( 'ERR0000' , "Notice: Console: Running Quit Hook: $disc" );
				$object->close();
			}
		}
		exit;
	}
	
	public function shouldQuit( $str )
	{
		if ( $str == 'q' ) {
			$this->quit();
		}
	}
	
	public static function line()
	{
		print "______________________________________________________________________\n\n";
	}
	
	public static function showInput( $str )
	{
		while(true) {
			print "$str\n: ";
			$response = trim( fread( STDIN , 1024 ) );
			if ( !empty( $response ) )
				return $response;
			print "\nError: Empty Input: Try Again\n\n";
		}
	}
	
	public static function showTitle( $str )
	{
		self::clear();
		print "\n\n  $str\n";
		self::line();
	}
	
	public static function showMenu( $menuTitle , $menuData , $showPreviousMenuOption = true )
	{
		/*** Various Checks ***/
		if ( !is_array( $menuData ) )
			return false;
		if ( empty( $menuTitle ) ) {
			$menuTitle = 'Unknown...';
		}
		
		while(true) {
			self::showTitle( $menuTitle );
			foreach( $menuData as $option => $menu ) {
				pf( "   $option. {$menu[ 'Name' ]}" );
			}
			if ( $showPreviousMenuOption ) {
				pf( '   Type m to go to previous menu' );
			}
			pf( '   Type q to quit' );
			$option = self::showOptionInput();
			
			if ( $option == 'm' || $option == 'q' ) 
				return $option;
			elseif ( !empty( $menuData[ $option ] ) ) {
				return $option;
			} else {
				pf( 'Error: Invalid Option' );
				sleep(2);
			}
		}
	}
	
	public static function showForm( $form )
	{
		if ( is_array( $form ) )
			foreach( $form as $field ) {
				$return[ $field ] = $this->showInput( $field );
			}
	}
	
	public static function showQuestion( $str )
	{
		$choice = self::showInput( $str . ' (y/n)' );
		switch( $choice ) {
			case 'y':
			case 'Y':
				return true;
			break;
		}
		return false;
	}
	
	public static function showPleaseWait()
	{
		self::showTitle( 'Please Wait...' );
		print 'Processing..';	
	}
	
	public static function showProgressBar()
	{
		usleep(950);
		print '..';
	}
}

$ozInitThisExtension = true;
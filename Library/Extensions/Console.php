<?php

class Console
{
	public $quitHooks; 
	
	public function getInput()
	{
		return trim( fread( STDIN , 1024 ) );
	}
	
	public function clear()
	{
		system( 'clear' );
	}
	
	public function showOptionInput()
	{
		print "\nOption: ";
		return $this->getInput();
	}
	
	public function showOptionForm( $formData )
	{
		global $oz;
		
		if ( is_array( $formData ) ) {
			while(true) {
				foreach( $formData as $option => $name ) 
					$oz->printf( '   ' . $option . '. ' . $name );
				$option = $this->showOptionInput();
				if ( empty( $formData[ $option ] ) ) {
					$oz->printf( 'Error: Invalid Option' );
					sleep(2);
				} else 
					break;
			}
			return $option;
		}
			
	}
	
	public function pause()
	{
		print "Press enter to continue...";
		$this->getInput();
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
		$oz->printf( $msg );
		
		/* Run quit hooks */
		if ( !empty( $this->quitHooks ) )
			foreach( $this->quitHooks as $disc => $object ) {
				$oz->logData( 'ERR0000' , "Notice: Console: Running Quit Hook: $disc" );
				$object->close();
			}
		exit;
	}
	
	public function shouldQuit( $str )
	{
		if ( $str == 'q' )
			$this->quit();
	}
	
	public function line()
	{
		print "______________________________________________________________________\n\n";
	}
	
	public function showInput( $str )
	{
		while(true) {
			print "$str\n: ";
			$response = trim( fread( STDIN , 1024 ) );
			if ( !empty( $response ) )
				return $response;
			print "\nError: Empty Input: Try Again\n\n";
		}
	}
	
	public function showTitle( $str )
	{
		$this->clear();
		print "\n\n  $str\n";
		$this->line();
	}
	
	public function showMenu( $menuTitle , $menuData )
	{
		global $oz;
		
		while(true) {
			$this->showTitle( $menuTitle );
			foreach( $menuData as $option => $menu ) {
				$oz->printf( "   $option. {$menu[ 'Name' ]}" );
			}
			$oz->printf( '   Type m to go to previous menu' );
			$option = $this->showOptionInput();
			
			if ( !empty( $menuData[ $option ] ) ) {
				return $option;
			} else {
				$oz->printf( 'Error: Invalid Option' );
				sleep(2);
			}
		}
	}
	
	public function showForm( $form )
	{
		if ( is_array( $form ) )
			foreach( $form as $field ) {
				$return[ $field ] = $this->showInput( $field );
			}
	}
	
	public function progressBar()
	{
		usleep('500' );
		print '..';
	}
}

$ozConsole = new Console();
$oz->varExport( 'ozConsole' , $ozConsole );
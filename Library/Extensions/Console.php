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
	
	public function printOption()
	{
		print "\nOptions: ";
		return $this->getInput();
	}
	
	public function pause()
	{
		print "Press enter to continue...\n";
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
		print "\n\n$str\n";
	}
}

$ozConsole = new Console();
$oz->varExport( 'ozConsole' , $ozConsole );
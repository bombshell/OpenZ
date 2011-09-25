<?php

/***
 * OpenZ Global Functions
 */

function pf( $str = null )
{
	global $oz;
	if ( $oz->getSapi() == 'cli' ) {
		fwrite( STDOUT , $str . "\n" );
	} elseif ( $str ) {
		$oz->logData( 'ERR0000' , $str );
	}
}

function oz_quit( $errorMsg )
{
	global $oz;
	if ( is_object( $oz ) ) {
		$oz->printError( 'ERR0000' , $errorMsg , 'OpenZ Shell User Management System' );
		$oz->logData( 'ERR0000' , $errorMsg );
	} else {
		print $errorMsg . "\n";
	}
	exit(1);
}

function oz_std()
{
	if ( !is_resource( @STDIN ) ) {
		$stdin = fopen( 'php://stdin' , 'r' );
		define( 'STDIN' , $stdin );
	}
	if ( !is_resource( @STDOUT ) ) {
		$stdin = fopen( 'php://stdout' , 'w' );
		define( 'STDOUT' , $stdin );
	}
	if ( !is_resource( @STDERR ) ) {
		$stdin = fopen( 'php://stderr' , 'w' );
		define( 'STDERR' , $stdin );
	}
}
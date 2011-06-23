#!/usr/bin/env php
<?php
require '../../../main.php';

function banner()
{
	global $ozConsole;
	$ozConsole->clear();
	$ver = OZ_VER;
	print "\n\n OpenZ $ver Administrator Interface\n";
	$ozConsole->line();
	print "\n";
}

banner();
print "This system is currently being tested\n";
print "Type q to exit at anytime\n";
$login = false;
do {
	$username = $ozConsole->showInput( 'Please enter your OpenZ Administrator Username' );
	$ozConsole->shouldQuit( $username );
	shell_exec( 'stty -echo' );
	$password = $ozConsole->showInput( "\n" . 'Please enter your OpenZ Administrator Password' );
	shell_exec( 'stty echo' );
	
	if ( $ozSession->auth( $username , $password , 'admin' ) ) {
		$login = true;
	} else {
		print "\n\n" . OZ_ERROR . "Credentials Provided are invalid: Try again\n\n";
	}
} while ( $login == false );


/***
 * Build Menu
 */
$zppZDatabase->setTableName( 'oz_modules_info' );
$i = 1;
if ( $menus = $zppZDatabase->query() ) {
	while ( true ) {
		$ozConsole->showTitle( 'Menu...' );
	
		foreach( $menus as $menu ) {
			//var_dump( $menu );
			$oz->printf( "   " . $i . ". {$menu[ 'oz_modname' ]}" );
			$item[ $i ] = $menu[ 'oz_modfile' ];
			$i++;	
		}
		$i = 1;
		$oz->printf( '   q to quit' );
		
		$oz->printf('');
		$oz->printf( "Welcome " . $_SESSION['profile' ][ 'oz_uid' ] );
		
		$option = $ozConsole->showOptionInput();
		$ozConsole->shouldQuit( $option );
		if ( @$item[ $option ] ) {
			$ozConsole->clear();
			require OZ_PATH_LIBRARY . path_rewrite( 'Modules/' . $item[ $option ] );
		} else {
			print "Error: Invalid Option\n";
			$ozConsole->pause();
		}
	}	
} else {
	print "No Modules Available\n";
}

print "Goodbye ...";




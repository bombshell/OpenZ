#!/usr/local/bin/php
<?php

require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../../../main.php';

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
while(true) {
	$username = Console::showInput( 'Please enter your OpenZ Administrator *Username*' );
	$ozConsole->shouldQuit( $username );
	shell_exec( 'stty -echo' );
	$password = Console::showInput( "\n" . 'Please enter your OpenZ Administrator *Password*' );
	shell_exec( 'stty echo' );
	
	if ( $ozSession->auth( $username , $password , 'admin' ) ) {
		break;
	} else {
		print "\n\n" . OZ_ERROR . "Credentials Provided are invalid: Try again\n\n";
	}
} 

/***
 * Check if THIS user requires a password reset
 */
if ( $_SESSION[ 'profile' ][ 'oz_pwd_requires_reset' ] == '1' ) {
	pf("\n" . 'A password reset is required!');
	$password = Console::showPasswordForm();
	
	pf("\n" . 'Updating Database...');
	$ozProfile->setType( 'admin' );
	$ozProfile->getByName( $_SESSION[ 'profile' ][ 'oz_uid' ] );
	$data[ 'oz_pwd' ] = $oz->hash( $password );
	$data[ 'oz_pwd_requires_reset' ] = '0';
	$ozProfile->update($data);
	pf('DOne');
	sleep(2);
}

/***
 * Build Menu
 */
$zppZDatabase->setTableName( 'oz_modules_info' );
$i = 1;
if ( $menus = $zppZDatabase->query() ) {
	while ( true ) {
		Console::showTitle( 'Menu...' );
	
		foreach( $menus as $menu ) {
			//var_dump( $menus );
			//var_dump( $menu  );
			if ( !empty( $menu[ 'oz_modname' ] ) ) {
				pf( "   " . $i . ". {$menu[ 'oz_modname' ]}" );
				$item[ $i ] = $menu;
				$i++;
			}	
		}
		$i = 1;
		pf( '   q to quit' );
		
		pf('');
		pf( "Welcome " . $_SESSION['profile' ][ 'oz_uid' ] );
		
		$option = Console::showOptionInput();
		$ozConsole->shouldQuit( $option );
		if ( @$item[ $option ] ) {
			Console::clear();
			require OZ_PATH_LIBRARY . path_rewrite( 'Modules/' . $item[ $option ][ 'oz_modfile' ] );
			if ( !empty( $init_failure ) ) {
				pf("Error: Failed to load module {$item[$option]['oz_modname']} {$item[$option]['oz_modver']}: $init_failure");
				$ozConsole->pause();
			}
		} else {
			print "Error: Invalid Option\n";
			Console::pause();
		}
	}	
} else {
	print "No Modules Available\n";
}

print "Goodbye ...";




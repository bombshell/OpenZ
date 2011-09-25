#!/usr/local/bin/php
<?php

require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../../../OpenZ.php';

if ( $_SERVER[ 'USER' ] != 'root' ) {
	oz_quit( OZ_ERROR . OZ_NAME . ' needs to be run as root' );
}

function banner()
{
	global $ozConsole;
	$ozConsole->clear();
	$ver = OZ_VER;
	print "\n\n OpenZ $ver Administrator Interface\n";
	$ozConsole->line();
	print "\n";
}

function login()
{
	/*** Global Variables ***/
	global $ozConsole, $ozSession, $ozProfile, $OpenZ;
	
	banner();
	pf( 'This system is currently being tested' );
	pf( 'Type q to exit at anytime' );
	while(true) {
		$username = Console::showInput( 'Please enter your OpenZ Administrator *Username*' );
		$ozConsole->shouldQuit( $username );
		shell_exec( 'stty -echo' );
		$password = Console::showInput( "\nPlease enter your OpenZ Administrator *Password*" );
		shell_exec( 'stty echo' );
	
		if ( $ozSession->auth( $username , $password , 'admin' ) ) {
			break;
		} else {
			pf( "\n\n" . OZ_ERROR . "Credentials Provided are invalid: Try again\n\n" );
		}
	} 
	
	/***
 	 * Check if THIS user requires a password reset
     */
	if ( $ozProfile->GetField( 'oz_pwd_requires_reset' ) == '1' ) {
		pf("\n\n" . 'A password reset is required!');
		$password = Console::showPasswordForm();
		pf("\n" . 'Updating Database...');
		$data[ 'oz_pwd' ] = $OpenZ->hash( $password );
		$data[ 'oz_pwd_requires_reset' ] = '0';
		$ozProfile->update( $data );
		pf('Done');
		sleep(2);
	}
}

/***
 * Auth User
 */
login();

/***
 * Build Menu
 */
$zppZDatabase->setTableName( 'oz_modules_info' );
$i = 1;
if ( $modules = Module::getAll() ) {
	while ( true ) {
		$menu = null;
		$file = null;
		foreach( $modules as $module ) {
			if ( !empty( $module[ 'oz_modname' ] ) ) {
				$menu[$i][ 'Name' ] = $module[ 'oz_modname' ];
				$menu[$i][ 'Version' ] = $module[ 'oz_modver' ];
				$file[$i] = $module[ 'oz_modfile' ];
				$i++;
			}	
		}
		$i = 1;
		
		/*** Show Menu ***/
		$option = Console::showMenu( "Welcome {$_SESSION[ 'auth_uid' ]}..." , $menu , false );
		$ozConsole->shouldQuit( $option );
		
		/*** Load Option ***/
		Console::clear();
		$file = OZ_PATH_LIBRARY . path_rewrite( 'Modules/' . $file[ $option ] );
		if ( is_file( $file ) ) {
			require $file;
		} else {
			$ModuleInitFailure = 'Module Not found';
		}
		
		/*** Show Any failure Message ***/
		if ( !empty( $ModuleInitFailure ) ) {
			pf( "Error: Failed to load module {$menu[$option][ 'Name' ]} {$menu[$option][ 'Version' ]}: $ModuleInitFailure" );
			Console::pause();
			$ModuleInitFailure = null;
		}
		 
	}	
} else {
	pf( 'No Modules Available' );
	pf( 'Goodbye...' );
}




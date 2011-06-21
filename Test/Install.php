#!/usr/bin/env php
<?php
/* Install 1.0 */
$install_ver = '1.0.0';
$capat_ver   = '1.0.0';

/* Check for the latest version of install */
/*
 1. Check if there is latest code available
 .. Use file_get_contents 
 .... Text data will be returned
 ...... Do a comparasment 
 ........ Use file_get_contents() to pull the latest code
 .......... Eval Code
 ............ Exit
*/

/* We only Compatible with OpenZ < 1.0.0 */

/*** Load Module Configuration ***/
require 'ModuleConfig.php';

print '##################################################' . "\n";
print '            OpenZ Module Installation' . "\n";
print '##################################################' . "\n";

/*** 
 1. Ask where OpenZ base installation is
***/

$stdin = fopen( 'php://stdin' , 'r' );

$base_installation_found = false;
do {
	print "\n" . 'Where\'s the full path to the base installation of OpenVZ?' . "\n: ";
	$response = trim( fread( $stdin , 1026 ) );
	if ( is_file( $response . '/oz.php' ) ) {
		$base_installation = $response;
		$base_installation_found = true;
	} else {
		print 'Error: Invalid path: ' . $response . "\n";
		print 'Not found: ' . $response . '/oz.php';
	}
} while( $base_installation_found == false );

require $base_installation . 'Library/ModuleSetup.php';
exit;
/*** 
 2. Check if we are compatible
***/
if ( $capat_ver != str_replace( 'v' , '' , OZ_VER ) ) {
	print 'We are not compatitable with this version of OpenZ' . "\n";
	exit;
}

/***
 3. Register module 
***/
$moduleSetup = new ModuleSetup();
$moduleSetup->addToDb( $installCfg[ 'mod' ][ 'name' ] , $installCfg[ 'mod' ][ 'disc' ] , $installCfg[ 'mod' ][ 'file' ] );

/***
 4. Copy Contents
***/
if ( !$zppFilesystem->copy( $installCfg[ 'base_dir' ] , OZ_PATH_LIBRARY . 'Modules/' . $installCfg[ 'base_dir' ] ) ) {
	print 'Error: Failed to copy files' . "\n";
} else {
	print 'Installation complete' . "\n";
}
<?php

/**
 * @GlobalSettings
 */
$OZCFG[ 'Debug' ] = 2;

/* Image Extensions and MIME */
$OZCFG[ 'Valid_Image_FileExtensions' ][ 'jpg' ]  = 'image/jpeg';
$OZCFG[ 'Valid_Image_FileExtensions' ][ 'jpeg' ] = 'image/jpeg';
$OZCFG[ 'Valid_Image_FileExtensions' ][ 'png' ]  = 'image/png';

$OZCFG[ 'Hash_Algo' ] = 'md5'; /* Possible values are: md5 , sha256 or sha512. Defaults to md5 if the hash algo is missing. See: http://www.php.net/manual/en/function.hash-algos.php */

$OZCFG[ 'Database' ][ 'Location' ] = 'localhost';
$OZCFG[ 'Database' ][ 'Name' ]     = 'bmsdb';
$OZCFG[ 'Database' ][ 'Username' ] = 'bmsuser';
$OZCFG[ 'Database' ][ 'Password' ] = 'Dic20034@';

/* Do not modify any of settings below */
$OZPATH[ 'ZippeeConfig' ] = 'Config/Zippee/OZ_local.php'; /* Make the aproproitate changes to Z_local.php for local testing */

/***
 * Package id 
***/

$OZCFG[ 'Package' ][1][ 'Name' ] = 'Standard';
$OZCFG[ 'Package' ][1][ 'Quota' ] = '50000';
$OZCFG[ 'Package' ][1][ 'SystemGroup' ] = 'clients';

$OZCFG[ 'Package' ][2][ 'Name' ] = 'Contrib';
$OZCFG[ 'Package' ][2][ 'Quota' ] = '100000';
$OZCFG[ 'Package' ][2][ 'SystemGroup' ] = 'users';

$OZCFG[ 'Package' ][3][ 'Name' ] = 'Premium';
$OZCFG[ 'Package' ][3][ 'Quota' ] = '150000';
$OZCFG[ 'Package' ][3][ 'SystemGroup' ] = 'users';

/***
 * Quote
***/
$ozConfig[ 'Path' ][ 'Quote' ] = '/home';

/***
 * Commands 
 * 
 * <%oz_uid%> Username
 * 
***/
$ozCommands[ 'Post.Activate' ][] = 'chgrp www /home/<$oz_uid%>/';

/* Absolute path to Zippee FrameWork 
  Note: If left empty, openZ will default to $BASEPATH . 'Library/Zippee/'
*/
$ZippeeFramework = '';

/*** Do Not Modify Anything Below This Line ***/
define( 'OZ_PATH_BASE' , dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

if ( file_exists( OZ_PATH_BASE . 'LocalSettings.php') ) {
	require OZ_PATH_BASE . 'LocalSettings.php';
}

require OZ_PATH_BASE . 'oz.php';
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
 * If Package default parameter is missing, openZ will default to package 1
 * 
***/
$ozConfig[ 'Package' ][ 'Default' ] = '1';
$ozConfig[ 'Package' ][1][ 'Name' ] = 'Standard';
$ozConfig[ 'Package' ][1][ 'Quota' ] = '50000';
$ozConfig[ 'Package' ][1][ 'SystemGroup' ] = 'clients';
$ozConfig[ 'Package' ][1][ 'SystemGroups' ] = 'standard'; /* Separated By a Comma */

$ozConfig[ 'Package' ][2][ 'Name' ] = 'Contrib';
$ozConfig[ 'Package' ][2][ 'Quota' ] = '100000';
$ozConfig[ 'Package' ][2][ 'SystemGroup' ] = 'users';
$ozConfig[ 'Package' ][1][ 'SystemGroups' ] = 'contrib';

$ozConfig[ 'Package' ][3][ 'Name' ] = 'Contrib+';
$ozConfig[ 'Package' ][3][ 'Quota' ] = '150000';
$ozConfig[ 'Package' ][3][ 'SystemGroup' ] = 'users';
$ozConfig[ 'Package' ][1][ 'SystemGroups' ] = 'contribplus';

/***
 * Password
 */
$ozPassword[ 'Client' ][ 'PasswordAging' ] = true;
$ozPassword[ 'Client' ][ 'Maximum.Days' ] = '60';
$ozPassword[ 'Client' ][ 'Maximum.Inactive' ] = '3';

$ozPassword[ 'Admin' ][ 'PasswordAging' ] = true;
$ozPassword[ 'Admin' ][ 'Maximum.Days' ] = '30';
$ozPassword[ 'Admin' ][ 'Maximum.Inactive' ] = '0';


/***
 * Email
***/
$ozConfig[ 'Email.From' ][ 'Default' ] = array( 
	'Address' => 'noreply@bombshellz.net',
	'Name' => 'Bombshellz Network' 
); 

$ozConfig[ 'Email.From' ][ 'Admin' ] = array( 
	'Address' => 'admin@bombshellz.net',
	'Name' => '' 
); 

$ozConfig[ 'Email' ][ 'Name' ] = 'Bombshellz Networks';
$ozConfig[ 'Path' ][ 'EmailLogo' ] = 'Library/Email/BombshellzLogo.txt';
$ozConfig[ 'Path' ][ 'EmailSig' ] = 'Library/Email/BombshellzSig.txt';

/***
 * Lock Reasons
***/
$ozConfig[ 'LockReasons' ][1] = 'Too many background processes';
$ozConfig[ 'LockReasons' ][2] = 'Using more then allotted ports';
$ozConfig[ 'LockReasons' ][3] = 'Prohibited Software';
$ozConfig[ 'LockReasons' ][4] = 'Multiple Accounts';
$ozConfig[ 'LockReasons' ][5] = 'Policy Violation(s)';
$ozConfig[ 'LockReasons' ][6] = 'Account Inactive';
//$ozConfig[ 'LockReasons' ][2] = '';

/***
 * Profile
 * 1 = Pending
 * 2 = Active
 * 
 * Default ClientStatus
 *   Pending
 * Default AdminStatus
 *   Active
 *   
***/
//$ozConfig[ 'Profile' ][ 'Default.Add.ClientStatus' ] = 1;
//$ozConfig[ 'Profile' ][ 'Default.Add.AdminStatus' ]  = 2;

/***
 * Quota
***/
$ozConfig[ 'Path' ][ 'Quota' ] = '/home';

/***
 * Commands 
 * 
 * <%oz_uid%> Username
 * 
***/
$ozCommands[ 'Post.Activate' ][] = 'chgrp www /home/%oz_uid%/';

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
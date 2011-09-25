<?php

/***
 * Initialize 
 */

/*** Load Default Functions ***/
require OZ_PATH_BASE . 'Functions.php';

$oz = new OZ_Core();
$OpenZ = &$oz;

/*** Load and Initialize Zippee Classes ***/
$OpenZ->loadClass( 'Filesystem' );
$OpenZ->loadClass( 'Session_Core' );
$OpenZ->loadClass( 'EmailPhp_Core' );
$OpenZ->loadClass( 'DatabasePDO' );
$OpenZ->loadClass( 'Http' );
$OpenZ->loadClass( 'SysMsgQuery' );

$zppFilesystem = new Filesystem();
$zppHttp       = new Http();
//$zppSysMsgQuery = new SysMsgQuery();
$Database = new Database( array( 
 'dbType' => 'mysql',
 'dbPath' => $ozConfig[ 'Database' ][ 'Location' ],
 'dbName' => $ozConfig[ 'Database' ][ 'Name' ],
 'dbUser' => $ozConfig[ 'Database' ][ 'Username' ],
 'dbPass' => $ozConfig[ 'Database' ][ 'Password' ],
 'dbOpts' => array( PDO::ATTR_PERSISTENT => true )
) );
$zppZDatabase = &$Database;

/*** Baseline Constants ***/
define( 'OZ_NAME' , 'OpenZ Shell User Management System' );
define( 'OZ_VER' , 'v1.1.4-Experimental' );
define( 'OZ_PATH_LIBRARY' , OZ_PATH_BASE . path_rewrite( 'Library/' ) );
define( 'OZ_PATH_ALERTS' , $OpenZ->getTempPath() . path_rewrite( 'alerts/' ) );
define( 'OZ_ERROR' , 'Error: ' );

/*** Initialize Miscellaneous stuff ***/
Boot::classes();
Boot::checkDatabase();
Boot::close();
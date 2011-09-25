#!/usr/local/bin/php
<?php 
require 'OpenZ.php';

/*** Process Command Line Arguments ***/
$args = $OpenZ->getCmdArgs();
/* default variables */
$verbose = false;
foreach( $args[ 'params' ] as $param => $value ) {
	switch( $param ) {
		case '-v':
			$verbose = true;
		break;
		default:
			echo "Invalid Argument: $param\n";
			exit(1);
		break;
	}
}

$alerts = $zppFilesystem->dirRead( OZ_PATH_ALERTS , true );
pf( 'Checking for Alerts' );
pf( '---------------------------------' );
if ( !empty( $alerts ) ) {
	foreach( $alerts as $alert ) {
		if ( preg_match( '/^(alert_)/' , basename( $alert ) ) && ( !preg_match( '/(.bak)$/' , $alert ) || $verbose == true ) ) {
			pf( $OpenZ->fileRead( $alert ) . "\n" );
			if ( !rename( $alert , $alert . '.bak' ) ) {
				pf( OZ_ERROR . 'Unable to make a backup of this alert' );
			}
		}
	}
}
pf( '---------------------------------' );
pf( 'Done' );
pf( 'Note: You can view system alerts at anytime by running \'sysalerts\'' );
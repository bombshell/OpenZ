<?php

/* Default Values */
$mod_base_path = dirname( __FILE__ ) . DS;
$mod_name = 'Account Management v0.0.1';

if ( empty( $ozPermission[1][ 'Activate' ] ) ) {
	$ozPermission[1][ 'Activate' ] = true;
}
if ( empty( $ozPermission[1][ 'SwitchPackage' ] ) ) {
	$ozPermission[1][ 'SwitchPackage' ] = true;
}
if ( empty( $ozPermission[1][ 'Lock' ] ) ) {
	$ozPermission[1][ 'Lock' ] = true;
}
if ( empty( $ozPermission[2][ 'Activate' ] ) ) {
	$ozPermission[2][ 'Activate' ] = false;
}

$ozAdminGroup[0][ 'Name' ] = 'root';
if ( empty( $ozAdminGroup[1] ) )
	$ozAdminGroup[1][ 'Name' ] = 'Senior';
if ( empty( $ozAdminGroup[2] ) )
	$ozAdminGroup[2][ 'Name' ] = 'Junior';

/***
 * Initialize
 */
if ( empty( $ozConfig[ 'LockReasons'] ) ) {
	$ModuleInitFailure = 'Error: No lock reasons defined';
} else {
	if ( $oz->getSapi() == 'cli' ) {
		require $mod_base_path . 'um_cli.php';
	}
}


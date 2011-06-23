<?php

/**/
$mod_base_path = dirname( __FILE__ ) . DS;

if ( empty( $ozPermission[1][ 'Activate' ] ) ) {
	$ozPermission[1][ 'Activate' ] = true;
}
if ( empty( $ozPermission[2][ 'Activate' ] ) ) {
	$ozPermission[2][ 'Activate' ] = false;
}

$ozAdminGroup[0][ 'Name' ] = 'root';
if ( empty( $ozAdminGroup[1] ) )
	$ozAdminGroup[1][ 'Name' ] = 'Senior';
if ( empty( $ozAdminGroup[2] ) )
	$ozAdminGroup[2][ 'Name' ] = 'Junior';
	

if ( $oz->getSapi() == 'cli' ) {
	require_once $mod_base_path . 'um_class_cli.php';
	require $mod_base_path . 'um_cli.php';
}


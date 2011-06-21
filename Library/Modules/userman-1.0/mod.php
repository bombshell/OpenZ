<?php

/**/
$mod_base_path = dirname( __FILE__ ) . DS;

if ( empty( $ozPriv[1][ 'Activate' ] ) ) {
	$ozPriv[1][ 'Activate' ] = true;
}
if ( empty( $ozPriv[2][ 'Activate' ] ) ) {
	$ozPriv[2][ 'Activate' ] = false;
}
if ( empty( $ozPriv[1][ 'Activate' ] ) ) {
	$ozPriv[3][ 'Activate' ] = false;
}


if ( $oz->getSapi() == 'cli' ) {
	require_once $mod_base_path . 'um_class_cli.php';
	require $mod_base_path . 'um_cli.php';
}


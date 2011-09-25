<?php
/*** 
 * This Script will become an independent process
 * which will have it's own version of OpenZ running
 */
if ( empty( $argv[1] ) ) {
	print 'Error: Missing OpenZ Path';
	exit(1);
}
require $argv[1];

//$trial_timestamp     = $activated_timestamp + 60*60*24*5;
$current_timestamp   = time();

$Database->setTableName( $ozProfile->getTable() );
$trial_users = $Database->query( 'oz_uid,oz_status,oz_time_shellactivated,oz_time_trialcomplete' , 'WHERE oz_trialstatus = "On Trial"' );
var_dump( $trial_users );
if ( $trial_users ) {
	foreach( $trial_users as $user ) {
		if ( empty( $user[ 'oz_time_trialcomplete' ] ) && $user[ 'oz_status' ] == 'Active' ) {
			$trial_timestamp = $user[ 'oz_time_shellactivated' ] + 60*60*24*7;
			if ( time() > $trial_timestamp ) {
				$OpenZ->sendAlert( "User {$user[ 'oz_uid' ]} trial period has expired. Lock account!" );
			}
		}
	}
}
exit(0);
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

$ignore_procs = array( '/bin/bash' , '-bash' );
$passwd_file = "/etc/passwd";
$lines = file( $passwd_file , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );

//var_dump( $Database->query( '*' , 'WHERE oz_uid = "bombfuck"' ) );
/*** Loop Through Each lines and grep User ***/
foreach( $lines as $line ) {
	preg_match( '/^(.*?):/' , $line , $match );
	$user = $match[1];
	$total_processes = 0;
	/*** Check if User has an Account ***/
	if ( $ozProfile->getByName( $user ) && $user != 'root' ) {
		if ( $processes = $ozSystem->GetUserProcesses( $user ) ) {
			$assigned_ports = (int) trim( `echo $(( 62000 - ( \`id -u $user\` - 1000) * 2 ))` );
			$assigned_ports = array( $assigned_ports , $assigned_ports + 1 );
			$_user_logged_in = $ozSystem->isUserLoggedIn( $user );
			
			foreach( $processes as $proc ) {
				/*** Verify if user didn't exceed alloted processes ***/
				//if ( count( $processes ) > @$ozConfig[ 'Package' ][ $ozProfile->getField( 'oz_packageid' ) ][ 'BackgroundProcesses' ] ) {
				if ( !preg_match( '/^(screen)/i' , $proc[ 'cmd' ] ) && !preg_match( '/(sftp)/i' , $proc[ 'cmd' ] ) && !in_array( $proc[ 'cmd' ] , $ignore_procs ) && !$_user_logged_in ) {
					@$proc_list .= "PID: {$proc[ 'pid' ]}\nCMD: {$proc[ 'cmd' ]}\n\n";
					$total_processes++;
				}
				//}
				/*** Verify if user isn't using unauthorized port ***/	
				if ( !empty( $proc[ 'ports' ] ) ) {
					
					foreach( $proc[ 'ports' ] as $port ) {
						if ( !in_array( $port[1] , $assigned_ports ) ) {
							@$port_list .= "PID: {$proc[ 'pid' ]}\nCMD: {$proc[ 'cmd' ]}\nIP: {$port[0]}\nPORT: {$port[1]}";
						}
					}
				}
			
			}
			
			$_send_email_2_root = false;
			if ( $ozProfile->getType() == 'admin' ) {
				$_send_email_2_root = true;
				if ( empty( $ozAdminGroup[ $ozProfile->getField( 'oz_level' ) ][ 'BackgroundProcesses'] ) ) {
					$ozAdminGroup[ $ozProfile->getField( 'oz_level' ) ][ 'BackgroundProcesses'] = 8;
				}
				if ( $total_processes > $ozAdminGroup[ $ozProfile->getField( 'oz_level' ) ][ 'BackgroundProcesses'] ) {
					$msg = "User $user exceed alloted process\n---------------------------------\n$proc_list\n\n";
				}
			} elseif ( $total_processes > $ozConfig[ 'Package' ][ $ozProfile->getField( 'oz_packageid' ) ][ 'BackgroundProcesses' ] ) {
					$msg = "User $user exceed alloted process\n---------------------------------\n$proc_list\n\n";
			}			
			if ( !empty( $port_list ) ) {
					@$msg .= "User $user is bind to unauthorized port\n---------------------------------\n$port_list";
			}
			if ( !empty( $msg ) ) {
				if ( $_send_email_2_root ) {
					$OpenZ->SendEmail2Root( 'SYSTEM ALERT!!!' , $msg );
				} else {
					$OpenZ->sendAlert( $msg );
				}
			} 
			$msg       = null;
			$proc_list = null;
			$port_list = null;
		}
	}				
} 
	//var_dump( $user );

exit(0);
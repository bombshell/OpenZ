<?php
$um = new UserManagment();

while(true) {
	$ozConsole->showTitle( 'Account...' );
	$ozConsole->line();
	$oz->printf( 'Type m to go back to the menu' );
	$username = $ozConsole->showInput( 'Account username to retrieve' );
	$ozConsole->shouldQuit( $username );
	if ( $username == 'm' ) {
		break;
	}
	
	$ozProfile->setType( 'client' );
	if ( $ozProfile->getByName( $username ) ) {
		/***
 		 * Lock account from other admins 
		***/

		/* Check if Account is already locked */
		if ( $admin = $um->isLocked() ) {
			$oz->printf( 'Account is already locked by ' . $admin );
			$oz->printf( 'If this is a mistake, delete ' . $um->lockFile );
		} elseif ( !$um->lockUserProfile() ) {
			$oz->printf( 'Unable to lock acount' );
		} else {
			
			while(true) {
				$ozConsole->clear();
				$um->showProfile();
				$um->showOptions();

			
				print "\n" . 'Option: ';
				$option = $ozConsole->getInput();
				$ozConsole->shouldQuit( $option );
				if ( !empty( $option ) ) {
					if ( $option == 'C' || $option == 'c' ) { 
						$um->close();
						break;
					} else
						$um->doOption( $option );	
				}
			
			}
		}
	} else {
		print "\nError: Invalid User: Try Again\n";
		$ozProfile->close();
	}
	
	$ozConsole->pause();
	$ozConsole->clear();
}
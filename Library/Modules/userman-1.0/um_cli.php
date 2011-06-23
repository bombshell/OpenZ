<?php

$um = new UserManagment();
$menu = array();

while( true ) {
	$ozConsole->showTitle( 'Account Management...' );
	$menu[1][ 'Name' ] = 'My Account';
	$menu[2][ 'Name' ] = 'Client Accounts';
	
	if ( $um->hasPriviledge( 'View.Admins' ) )
		$menu[3][ 'Name' ] = 'Administrator Accounts';
	$option = $ozConsole->showMenu( 'Account Panel...' , $menu );
		
	if ( $option == 'm' ) {
		break;
	} else {
		switch( $option ) {
			case '1':
				$um->showProfile();
				$ozConsole->pause();
			break;
			case '2':
			case '3':
				if ( $option == '3' ) {
					/* Reset Values */
					$menu = array();
					
					$ozProfile->setType( 'admin' );
					$menu[1][ 'Name' ] = 'Administrator Lookup';
					if ( $um->hasPriviledge( 'Add.Admins' ) )
						$menu[2][ 'Name' ] = 'Add Administrator';
					$option = $ozConsole->showMenu( 'Administrator Accounts...' , $menu );
					
					switch( $option ) {
						case '1':
							$um->showAccountLookup();
						break;
						case '2':
							$um->showAdminForm();
						break;
					}
				} else {
					$ozProfile->setType( 'client' );
					$um->showAccountLookup();
				}
			break;
		}
	}
}
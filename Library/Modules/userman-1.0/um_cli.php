<?php

$um = &$ozUserManagement;

$menu = array();

while( true ) {
	$menu[1][ 'Name' ] = 'My Account';
	$menu[2][ 'Name' ] = 'Account Lookup';
	
	if ( $um->hasPriviledge( 'Manage.Admins' ) )
		$menu[3][ 'Name' ] = 'Administrator Accounts';
	$option = $ozConsole->showMenu( 'Account Management...' , $menu );
		
	if ( $option == 'm' ) {
		break;
	} else {
		switch( $option ) {
			case '1':
				$ozProfile->GetAccountByName( $_SESSION[ 'auth_uid' ] );
				$ozCliInterface->DisplayAccount();
				Console::pause();
			break;
			case '2':
				$ozCliInterface->DisplayLookupForm();
			break;
			case '3':
				/* Reset Values */
				$menu = array();
					
				$ozProfile->setType( 'admin' );
				$menu[1][ 'Name' ] = 'Add Administrator';
				$option = $ozConsole->showMenu( 'Administrator Accounts...' , $menu );
					
				switch( $option ) {
					case '1':
						$um->showAdminForm(); 	
					break;
				}
				 
			break;
		}
	}
}
<?php
global $ozSession, $zppHttp;

if ( @$_POST[ 'AuthAuthenticate' ] == 'true' ) {
	if ( !$ozSession->auth( @$_POST[ 'oz_uid' ] , @$_POST[ 'oz_pwd' ] , @$_POST[ 'AuthType' ] ) ) {
		if ( $ozSession->errorId == 'ERR0605' ) 
			$loginError = 'disabled';
		else 
			$loginError = 'invalid';
				
		$url = $_SERVER[ 'PHP_SELF' ] . '?AuthType=' . $_POST[ 'AuthType' ] . '&AuthError=' . $loginError;
	} else {
		$url = $_SERVER[ 'PHP_SELF' ];
	}
	$zppHttp->redirectClient( $url );
} else {
	require OZ_PATH_WEBUI . path_rewrite( 'Forms/login.php' );
}
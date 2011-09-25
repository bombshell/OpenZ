<?php

/***
 * Preload any custom hooks
 */

if ( file_exists( 'CustomInitHook.php' ) )
	require( 'CustomInitHookBefore.php' );

/***
 * Load Configuration
 */
	
require OZ_PATH_BASE . 'DefaultSettings.php';
	
/***
 * Zippee Framework
 */

if ( empty( $ZippeeFramework ) ) {
	if ( file_exists( OZ_PATH_BASE . 'Library/Zippee/main.php' ) ) {
		$ZippeeFramework = OZ_PATH_BASE . 'Library/Zippee/';
		require $ZippeeFramework . 'main.php';
	}
} else {
	if ( preg_match( '/(\\\|\/)$/' , $ZippeeFramework ) )
		$ZippeeFramework .= DIRECTORY_SEPARATOR;
	if( file_exists( $ZippeeFramework . 'main.php' ) )
		require $ZippeeFramwork . 'main.php';
	else {
		oz_quit( 'Error: Zipper Framework not loaded: Path not found ' . $ZippeeFramework );
	}
}

/*** Zippee Framework is loaded, We may now use some of it's functions ***/

/***
 * Load OpenZ Classes
 */

require OZ_PATH_BASE . path_rewrite( 'Library/Classes/Default.php' );
require OZ_PATH_BASE . path_rewrite( 'Library/Classes/Boot.php' );

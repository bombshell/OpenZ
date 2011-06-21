<?php

/*** 

	Copyright (c) http://wiki.bombshellz.net/
	Author: Lutchy Horace
	Version: 0.0.1
	
	Redistribution and use in source or binary forms are permitted provided that the following conditions are met:
		
		* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
		* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
		* Neither the name of the BombShellz.net nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
		* Modification to this file or program is not permitted without the consent of the author.
		* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	
***/


if ( file_exists( 'CustomInitHook.php' ) )
	require( 'CustomInitHookBefore.php' );
	
/*** FUNCTIONS ***/
function load_extension( $ext )
{
	require BMS_PATH_BASE . path_rewrite( 'Base/Extensions/' . $ext );
	
}

function oz_quit( $errorMsg )
{
	global $oz;
	if ( is_object( $oz ) ) {
		$oz->printError( 'ERR0000' , $errorMsg , 'OpenZ Shell User Management System' );
		$oz->logData( 'ERR0000' , $errorMsg );
	} else {
		print $errorMsg;
	}
	exit(1);
}

function oz_std()
{
	if ( !is_resource( STDIN ) ) {
		$stdin = fopen( 'php://stdin' , 'r' );
		define( 'STDIN' , $stdin );
	}
	if ( !is_resource( STDOUT ) ) {
		$stdin = fopen( 'php://stdout' , 'w' );
		define( 'STDOUT' , $stdin );
	}
	if ( !is_resource( STDERR ) ) {
		$stdin = fopen( 'php://stderr' , 'w' );
		define( 'STDERR' , $stdin );
	}
}

/*** Initialize OpenZ ***/

/* Load Zippee Framework */
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
}
if ( !class_exists( 'Framework' ) ) {
	oz_quit( 'Error: Zipper Framework not loaded: Path not found ' . $ZippeeFramework );
}
//var_dump( $_SERVER[ 'REMOTE_ADDR' ] );
/* Load openZ API */
require 'Class.php';

/* Init API */
$ozInit = new OZ_Init();
$ozInit->vars();
$ozInit->classes();
$zppZDatabase  = new Database( $ozInit->database() );
$ozInit->checkDatabase();
$ozInit->close();
unset( $ozInit );

$zppFilesystem = new Filesystem();
$zppHttp       = new Http();


$oz = new OZ_Core();

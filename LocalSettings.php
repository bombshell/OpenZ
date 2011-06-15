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


$OZPATH[ 'Base' ] = ''; /* Base path of openZ , defaults to base path z.php */

$OZCFG[ 'Debug' ] = 2;

/* Image Extensions and MIME */
$OZCFG[ 'Valid_Image_FileExtensions' ][ 'jpg' ]  = 'image/jpeg';
$OZCFG[ 'Valid_Image_FileExtensions' ][ 'jpeg' ] = 'image/jpeg';
$OZCFG[ 'Valid_Image_FileExtensions' ][ 'png' ]  = 'image/png';

$OZCFG[ 'Hash_Algo' ] = 'md5'; /* Possible values are: md5 , sha256 or sha512. Defaults to md5 if the hash algo is missing. See: http://www.php.net/manual/en/function.hash-algos.php */

$OZCFG[ 'Database' ][ 'Location' ] = 'localhost';
$OZCFG[ 'Database' ][ 'Name' ]     = 'ozdb';
$OZCFG[ 'Database' ][ 'Username' ] = 'ozuser';
$OZCFG[ 'Database' ][ 'Password' ] = 'Dic20034@';

/* Do not modify any of settings below */
$OZPATH[ 'ZippeeConfig' ] = 'Config/Zippee/OZ_local.php'; /* Make the aproproitate changes to Z_local.php for local testing */
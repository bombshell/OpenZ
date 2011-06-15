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

class Forms
{	
	public function processForm( $formType )
	{
		if ( method_exists( $this , $formType ) ) {
			$this->$formType();
		}
	}
	
	public function loginFrom()
	{
		global $ozSession, $zppHttp;
		$url = $_SERVER[ 'PHP_SELF' ] . '?loginType=' . $_POST[ 'loginType' ];
		if ( !$ozSession->auth( $_POST[ 'ozuid' ] , $_POST[ 'ozpwd' ] , $_POST[ 'loginType' ] ) ) {
			if ( $ozSession->errorId == 'ERR0605' ) 
				$loginError = 'disabled';
			else 
				$loginError = 'invalid';
				
			$url .= '&loginError=' . $loginError;
		} else {
			$url = $_SERVER[ 'PHP_SELF' ];
		}
		$zppHttp->redirectClient( $url );
	}
}

$ozForms = new Forms();
$oz->varExport( 'ozForms' , $ozForms );
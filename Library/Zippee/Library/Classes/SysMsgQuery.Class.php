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

class SysMsgQuery extends Framework
{
	public $errorId;
	public $errorMsg;
	private $shmId;
	private $msgId;
	
	public function __construct()
	{
		/*** Create IPC/Share Memory Query ***/
		$this->msgId = shm_attach( getmyinode() );
		if ( $this->msgId == false ) {
			$this->errorId = 'ERRx0116';
			$this->errorMsg = 'Error: SysMsgQuery->__construct(): Fail to attach shared memory';
			$this->logData( $this->errorId , $this->errorMsg );
			if ( $this->debug >= 1 ) {
				$this->throwError();
			}
		}
	}
	
	public function detach()
	{
		shm_detach( $this->msgId );
		$this->msgId = null;
	}
	
	public function attach()
	{
		$this->msgId = shm_attach( $this->shmId );
		if ( $this->msgId == false ) {
			$this->errorId = 'ERRx0116';
			$this->errorMsg = 'Error: SysMsgQuery->attach(): Failed to re-attach to shared memory';
			$this->logData( $this->errorId , $this->errorMsg );
			if ( $this->debug >= 1 ) {
				$this->throwError();
			}
		}
	}
	
	public function addVar( $key , $varvalue )
	{
		if ( $this->msgId == false ) {
			return false;
		}
		return @shm_put_var( $this->msgId , $key , $varvalue );
	}
	
	public function getVar( $key )
	{
		if ( $this->msgId == false ) {
			return false;
		}
		return @shm_get_var( $this->msgId , $key );
	}
	
	public function __destruct()
	{
		shm_remove( $this->msgId );
	}
}
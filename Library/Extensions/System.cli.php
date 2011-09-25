<?php

class System
{
	public $mLogFile;
	
	public function __construct()
	{
		$this->mLogFile =  TEMP_DIR . 'commandLog.txt';
	}
	
	public function LastLogin( $username )
	{
		if ( $this->userExists( $username ) ) {
			return $this->shellexec( "lastlog -u $username" );
		} else {
			return null;
		}
	}
	
	public function userExists( $username )
	{
		exec( "id $username 2> /dev/null" , $output , $retvar );
		if ( $retvar == 0 ) {
			return true;
		}
		return false;
	}
	
	public function useradd( $username , $group , $comment , $groups = null )
	{
		$cmd = "useradd -g $group ";
		if ( !empty( $groups ) )
			$cmd .= "-G $groups ";
		$cmd .= "-m -c '$comment' $username";
		return $this->exec( $cmd );
	}
	
	public function usermod( $username , $group , $groups )
	{
		$cmd = "usermod -g $group -G $groups $username";
		return $this->exec( $cmd );
	}
	
	public function setquota( $username , $quota )
	{
		global $ozConfig, $oz;
		if ( empty( $quota ) || empty( $username ))
			return false;
		if ( empty( $ozConfig[ 'Path' ][ 'Quota' ] ) ) {
			$this->errorId  = '';
			$this->errorMsg = 'Error: System->setquota(): Missing quota path';
			$oz->logData( $this->errorId , $this->errorMsg );
			return false;
		}
		$cmd = "setquota -u $username $quota $quota 0 0 {$ozConfig[ 'Path' ][ 'Quota' ]}";
		return $this->exec( $cmd );
	}
	
	public function chage( $username , $mDays , $mInactive )
	{
		$cmd = "chage -M $mDays -I $mInactive $username";
		return $this->exec( $cmd );
	}
	
	public function lockpasswd( $username )
	{
		$cmd = "usermod -s /sbin/nologin $username";
		if ( !$this->exec( $cmd ) ) 
			return false;
		$cmd = "passwd -l $username";
		if ( !$this->exec( $cmd ) )
			return false;
		return true;
	}
	
	public function unlockpasswd( $username )
	{
		$cmd = "usermod -s /bin/bash $username";
		if ( !$this->exec( $cmd ) ) 
			return false;
		$cmd = "passwd -u $username";
		if ( !$this->exec( $cmd ) )
			return false;
		return true;
	}
	
	public function killall( $username )
	{
		$cmd = "killall -u $username";
		if ( !$this->exec( $cmd ) )
			return false;
		return true;
	}
	
	public function kill( $pid )
	{
		$cmd = "kill $pid";
		if ( !$this->exec( $cmd ) ) {
			return false;
		}
		return true;
	}
	
	public function exec( $cmd )
	{
		global $oz;
		
		/* Send STDERR to file */
		$exec_cmd = $cmd . ' 2>> ' . $this->mLogFile;
		/* Exec command */
		$output = exec( $exec_cmd , $array , $status );
		if ( $status != 0 ) {
			$this->errorId = 'ERRx0116';
			$this->errorMsg = 'Error: System->exec(): Failed to execute: ' . $cmd . ': Check ' . $this->mLogFile;
			$oz->logData( $this->errorId , $this->errorMsg );
			return false;
		}
		if ( !empty( $output ) )
			return $output;
		return true;
	}
	
	public function shellexec($cmd)
	{
		/* Send STDERR to file */
		$exec_cmd = $cmd . ' 2>> ' . $this->mLogFile;
		/* Exec command */
		return shell_exec( $exec_cmd );
	}
	
	public function GetUserProcesses( $user ) 
	{
		global $OpenZ;
		if ( $this->userExists( $user ) ) {
			exec( "ps -FU $user" , $output );
			foreach( $output as $line ) {
				$arr = explode( " " , $line );
				$arr = $OpenZ->removeEmptyArrs( $arr );
				if ( $arr[0] != 'UID' ) {
					$return[] = array(  'pid'  => $arr[1],
					                    'ppid' => $arr[2],
										'rss'  => $arr[3],
										'tty'  => $arr[6],
										'cmd'  => $OpenZ->arrayToString( $arr , 10 ),
										'ports' => $this->getListeningPorts( $arr[1] ) ); 
				}
			}	
			return ( !empty( $return ) ) ? $return : false;	
		}
	}
	
	public function getListeningPorts( $pid )
	{
		global $OpenZ;
		exec( "netstat -lnp --tcp | grep $pid" , $output );
		if ( !empty( $output ) ) {
			foreach( $output as $line ) {
				$arr = $OpenZ->removeEmptyArrs( explode( ' ' , trim( $line ) ) );
				preg_match( '/(.*):(\d+)$/' , $arr[3] , $matches );
				$return[] = array( $matches[1] , $matches[2] );
			}
			return $return;	
		}
		return false;
	}
	
	public function isUserLoggedIn( $uid )
	{
		exec( "who | grep $uid" , $output , $status );
		if ( $status == 0 ) {
			return true;
		}
		return false;
	}
}

$ozInitThisExtension = true;
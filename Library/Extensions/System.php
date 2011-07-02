<?php

class System
{
	public $commandLogFile;
	
	public function __construct()
	{
		$this->commandLogFile =  TEMP_DIR . 'commandLog.txt';
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
	
	public function usermod( $username , $groups )
	{
		$cmd = "usermod -G $groups $username";
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
	
	public function exec( $cmd )
	{
		global $oz;
		
		/* Send STDERR to file */
		$exec_cmd = $cmd . ' 2>> ' . $this->commandLogFile;
		/* Exec command */
		$output = exec( $exec_cmd , $array , $status );
		if ( $status != 0 ) {
			$this->errorId = 'ERRx0116';
			$this->errorMsg = 'Error: System->exec(): Failed to execute: ' . $cmd . ': Check ' . $this->commandLogFile;
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
		$exec_cmd = $cmd . ' 2>> ' . $this->commandLogFile;
		/* Exec command */
		return shell_exec( $exec_cmd );
	}
}

$ozInitThisExtension = true;
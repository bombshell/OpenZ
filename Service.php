#!/usr/local/bin/php
<?php
require 'OpenZ.php';

class Service
{
	public $mRunningProcesses;
	
	public function ForkProcess( $process )
	{
		global $OpenZ, $Database, $ozSystem, $ozProfile, $ozConfig, $ozAdminGroup;
		$pid = pcntl_fork();
		if ( $pid == -1 ) {
			$OpenZ->logData( 'ERRx0116' , 'Error: Service->ForkProcess(): Unable to fork' );
			return false;
			//exit(1);	
		} elseif( $pid ) {
			$this->mRunningProcesses[] = $pid;
			$OpenZ->logData( 'ERRx0118' , "Notice: Forking process $process($pid) has started" );
			return true;
		} else {
			$openz_path = OZ_PATH_BASE . 'OpenZ.php';
			exec( "/usr/local/bin/php $process $openz_path" , $output , $exitstatus );
			//require $process;
			exit( $exitstatus );
		}
	}
	
	public function CheckForRunningProcesses()
	{
		global $oz;
		if ( is_array( $this->mRunningProcesses ) ) {
			while( !empty( $this->mRunningProcesses ) ) {
				foreach( $this->mRunningProcesses as $id => $process ) {
					$rpid = pcntl_waitpid( -1 , $status );
					$exitstatus = pcntl_wexitstatus( $status );
					if ( $exitstatus > 0 ) {
						$oz->logData( 'ERRx0116' , "Error: Service $process has exited with status $exitstatus" );
						unset( $this->mRunningProcesses[ $id ] );
					} elseif ( !empty( $this->mRunningProcesses ) ) {
						unset( $this->mRunningProcesses[ $id ] );
					}	
				}
			}
		}
	}
}
$ozService = new Service();

/*** Default Values ***/
if ( empty( $ozConfig[ 'Service' ][ 'MaxProcs' ] ) ) {
	$ozConfig[ 'Service' ][ 'MaxProcs' ] = 2;
}
//$ozProfile->getByName( 'bombfuck' );
/*** Run Service ***/
$svc_files = $zppFilesystem->dirRead( OZ_PATH_LIBRARY . 'Services' . DS , true );
do {
	foreach( $svc_files as $job => $svc ) {
		if ( !preg_match( '/(.disabled)$/' , $svc ) && count( $ozService->mRunningProcesses ) < $ozConfig[ 'Service' ][ 'MaxProcs' ] ) {
			$ozService->ForkProcess( $svc );
		} 
		unset( $svc_files[ $job ] );
	}
	$ozService->CheckForRunningProcesses();
} while( !empty( $svc_files ) );

exit(0);
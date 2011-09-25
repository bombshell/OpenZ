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

/**
 * @category Class
 * @name Database
 * @version 0.2.0
 * 
 */
class Database extends Framework
{
	public $errorId;
	public $errorMsg;
	
	public $dbTableName;
	public $pdo;
	
	private $connOpts;
	private $disableTestConnection;
	private $mQCache;
	private $mPDOStatmentCache;
	
	
	/**
	 * @method __construct
	 * Database Class
	 * Initiates a PDO database object/connection
	 * @param (array) $connectOptions Connection options 
	 *   dbType => database type
	 *     Supported Database types
	 *       mysql
	 *       sqlite/sqlite2
	 *   dbName => Database Name
	 *   dbUser => Database Username
	 *   dbPass => Database Password
	 *   dbPath => Path or URL to database
	 *   dbPort => Database Port
	 *   dbOpts => Database options pass to PDO.
	 *     See: http://us2.php.net/manual/en/book.pdo.php
	 * @return (bool) Returns true if the connection was successful or false otherwise
	 * 
	 */
	public function __construct( $connectOptions )
	{
		/** init Core **/
		//parent::__construct();
		
		/* Series of checks */
		if ( !is_array( $connectOptions ) ) {
			return false;
		}
		if ( empty( $connectOptions[ 'dbType' ] ) ) {
			return false;
		}
		
		/*** Store connection options ***/
		$this->connOpts = $connectOptions;
		$dbType = @$connectOptions[ 'dbType' ];
		$dbName = @$connectOptions[ 'dbName' ];
		$dbUser = @$connectOptions[ 'dbUser' ];
		$dbPass = @$connectOptions[ 'dbPass' ];
		$dbPath = @$connectOptions[ 'dbPath' ];
		$dbPort = @$connectOptions[ 'dbPort' ];
		$dbOpts = @$connectOptions[ 'dbOpts' ];
		
		switch ( $dbType )
		{
			case 'mysql':
				
				if ( empty( $dbPath ) || empty( $dbName ) ) {
					$this->errorId = 'ERR0403';
					$this->errorMsg = 'Error: Unable to open database connection';
					return false;
				}

				if ( empty( $dbPort ) ) $dbPort = '3306';
				//mysql:host=localhost;port=3307;dbname=testdb
				$dsn = "mysql:host=$dbPath;port=$dbPort;dbname=$dbName";
			break;
			
			case 'sqlite':
			case 'sqlite2':
				/*
				 sqlite:/opt/databases/mydb.sq3
				 sqlite2:/opt/databases/mydb.sq2
				 sqlite::memory:
			    */
				if ( empty( $dbPath ) ) {
					$this->errorId = 'ERR0403';
					$this->errorMsg = 'Error: Unable to open database connection';
					return false;
				}
				$dsn = "$dbType:$dbPath";
			break;
		}
		
		try {
    		$this->pdo = new PDO( $dsn , $dbUser , $dbPass , $dbOpts );
		} catch (PDOException $e) {
			$this->errorId = 'ERR0403';
    		$this->errorMsg = 'Error: Connection failed: ' . $e->getMessage();
    		/* Troubleshoot database connection */
    		$this->logData( $this->errorId , $this->errorMsg , null , 'SYS' );
    		if ( $this->debug > 1 ) {
    			$this->throwError();	
    		}
    		
    		return false;
		}
		
		/* Assume everything went ok */
		return true;
	}
	
	/*** Just Some Ideas ***/
	public function StoreQueryInCache( $id , $sql )
	{
		if ( !is_object( $this->pdo ) ) {
			$this->errorId = 'ERR0401';
			$this->errorMsg = 'Error: Running a query on a invalid database connection';
			return false;	
		}
		$this->mQCache[ $id ] = $sql;
	}
	
	public function ExecuteStoredQuery( $id , $data )
	{
		if ( !is_object( $this->pdo ) ) {
			$this->errorId = 'ERR0401';
			$this->errorMsg = 'Error: Running a query on a invalid database connection';
			return false;	
		}
		
		if ( !empty( $this->mQCache[ $id ] ) ) {
			while(1) {
				$_pdo_statement = $this->pdo->prepare( $this->mQCache[ $id ] );
				if ( $_pdo_statement->execute( $data ) ) {
					$collection = $_pdo_statement->fetchAll();
					/**while( $row = $_pdo_statement->fetchAll() ) {
						if ( !empty( $row ) && is_array( $row ) ) {
							$collection = $row;
						}
					}**/
					//$_pdo_statement->closeCursor();
					if ( empty( $collection ) ) {
						//$error = $_pdo_statement->errorInfo();
						//$this->errorId = 'ERR0401';
						//$db_msg = ( !empty( $error[2] ) && $error[2] != 'NULL' ) ? $error[2] : 'Unknown'; 
						//$this->errorMsg = "Db error id: {$error[0]} Db error msg: $db_msg";
						return array();
					}
					return $collection;
				} else {
					$error = $_pdo_statement->errorInfo();
					$this->errorId = 'ERR0401';
					/*** Feature Replicated 02/28/2011 ***/
					$db_msg = ( !empty( $error[2] ) && $error[2] != 'NULL' ) ? $error[2] : 'Unknown';
					@$this->errorMsg = "Error: PDOStatement->execute(): Db error id: {$error[0]} Db error msg: {$error[2]}";
					if ( !$this->CheckConnection( $this->errorMsg ) ) {
						if ( !$this->ReConnect() ) {
							return false;
						}
					} else {
						return false;
					}
				}
			}
		}
	}
	
	public function BuildSelectQuery( $cols = '*' , $params = null )
	{
		return "SELECT $cols FROM {$this->dbTableName} $params";
	}
	
	public function BuildUpdateQuery( $cols , $params = null )
	{
		foreach ( $cols as $col ) {
			@$updValues .= ( empty( $updValues ) ) ? "$col = :$col" : ",$col = :$col";
		}
		return "UPDATE {$this->dbTableName} SET $updValues $params";
	}
	/**** IDEAS ***/
	
	/**
	 * @method insert
	 * Inserts data in table
	 * @param (array) $data An array column value pair
	 * @return (null)
	 * 
	 */
	public function insert( $data )
	{
		/* INSERT INTO tbl_name (a,b,c) VALUES(1,2,3,4,5,6,7,8,9); */
		if ( empty( $data ) ) {
			return false;
		}
		
		/* build sql query */
		foreach ( $data as $col => $value ) {
			$value = $this->quote( $value );
			@$insCols .= ( empty( $insCols ) ) ? $col : ",$col";
			@$insValues .= ( empty( $insValues ) ) ? "$value" : ",$value";
		}
		
		/* retrieve table name */
		$table = $this->dbTableName;
		$sql = "INSERT INTO $table ($insCols) VALUES ($insValues);";
		$this->exec( $sql );
	}
	
	/**
	 * @method update
	 * Update's fields with $data values
	 * @param (array) $data An array column value pair
	 * @param (string) $params Any additional paramaters to pass to SQL statement
	 * @return (null)
	 * 
	 */
	public function update( $data , $params = null )
	{
		/*UPDATE t1 SET col1 = col1 + 1, col2 = col1; */
		/* build sql query */
		foreach ( $data as $col => $value ) {
			if ( empty( $value ) ) {
				$value = 'NULL';
			} else {
				$value = $this->quote( $value );	
			}
			@$updValues .= ( empty( $updValues ) ) ? "$col = $value" : ",$col = $value";
		}
		
		/* retrieve table name */
		$table = $this->dbTableName;
		$sql = "UPDATE $table SET $updValues $params";
		$this->exec( $sql );
	}
	
	/**
	 * @method delete
	 * Enter description here ...
	 * @param (string) $params Pass any params to the SQL statement.
	 *   Note: As a precaution, params is required to aviod data lost. if
	 *   your intention is to delete all rows. Pass value 'deleteall'.
	 * @return (null)
	 * 
	 */
	public function delete( $params )
	{
		/*** Security FIXED 04/28/2011 : Check if additional params was giving to aviod deleting everything ***/
		if ( $params == 'deleteall' || !empty( $params ) ) 
			$this->exec( "DELETE FROM {$this->dbTableName} $params" );
	}
	
	/**
	 * @method query
	 * Run a select query on database and returns result set
	 * @param (string) $cols Column names
	 * @param (string) $params Any additional paramaters to pass to SQL statement
	 * @return (array) Result set or false
	 */
	public function query( $cols = '*' , $params = null ) 
	{
		/* Check if we have a database connection before we continue */
		if ( !is_object( $this->pdo ) ) {
			$this->errorId = 'ERR0401';
			$this->errorMsg = 'Error: Running a query on a invalid database connection';
			return false;	
		}
		
		if ( empty( $cols ) ) 
			$cols = '*';
		
		/* excute query */
		$sql = "SELECT $cols FROM {$this->dbTableName} $params;";
		$_pdo_statement = $this->pdo->query( $sql );
		if ( !empty( $_pdo_statement ) ) {
			//foreach( $this->pdo->query( $sql ) as $row ) { 
				//$collection[] = $row;
			//}
			/*** Bombshellz 08/27/2011 : Attempting to fix adnomarilty: Invalid argument supplied for foreach(); by finding an alternative solution ***/
			/**while( $row = $result_set->fetch() ) {
				if ( !empty( $row ) && is_array( $row ) ) {
					$collection[] = $row;
				}
			}**/
			$collection = $_pdo_statement->fetchAll();
			//$_pdo_statement->closeCursor();
		}
			
		if ( empty( $collection ) ) {
			$error = $this->pdo->errorInfo();
			$this->errorId = 'ERR0401';
			$db_msg = ( !empty( $error[2] ) ) ? $error[2] : 'Unknow';
			$this->errorMsg = "Error: Database->query($sql): Db error id: {$error[0]} Db error msg: $db_msg";
			if ( !$this->CheckConnection( $this->errorMsg ) ) {
				if ( !$this->ReConnect() ) {
					return false;
				}
			} 
			return false;
		}
		return $collection;
	}
	
	public function lastRowsBy( $orderBy , $limit , $params = null )
	{
		return $this->query( '*' , "$params ORDER BY $orderBy asc LIMIT $limit" );
	}
	
	/**
	 * @method exec
	 * Excutes given SQL statement
	 * @param (string) $sql SQL Statment
	 * @return (bool) True on success or false on error
	 * 
	 */
	public function exec( $sql )
	{
		/* BUG: we need to null errorId and errorMsg */
		$this->errorId = null;
		$this->errorMsg = null;
		/* Check if we have a database connection before we continue */
		if ( !is_object( $this->pdo ) ) {
			$this->errorId = 'ERR0401';
			$this->errorMsg = 'Error: Running a query on a invalid database connection';
			return false;	
		}
		/*** Test Connection ***/
		/*
		if ( $this->disableTestConnection == false ) {
			if ( !$this->testConnection() ) {
				return false;
			}
		}*/
		
		if ( empty( $sql ) ) {
			return false;
		}
		
		while(1) {
			$status = $this->pdo->exec( $sql );
			if ( $status == false ) {
				$error = $this->pdo->errorInfo();
				$this->errorId = 'ERR0401';
			
				/*** Feature Replicated 02/28/2011 ***/
				$db_msg = ( !empty( $error[2] ) ) ? $error[2] : 'Unknown';
				@$this->errorMsg = "Db error id: {$error[0]} Db error msg: {$error[2]}";
				if ( !$this->CheckConnection( $this->errorMsg ) ) {
					if ( !$this->ReConnect() ) {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	}
	
	/**
	 * @method quote
	 * Quotes a given string
	 * @param (string) $str
	 * 
	 */
	public function quote( $str )
	{
		/* Check if we have a database connection before we continue */
		if ( !is_object( $this->pdo ) ) {
			$this->errorId = 'ERR0401';
			$this->errorMsg = 'Running a query on a invalid database connection';
			return false;	
		}
		return $this->pdo->quote( $str );
	}
	
	/**
	 * @method setTableName
	 * Set's object Table Name value
	 * @param (string) $table Valid table name for current database
	 * 
	 */
	public function setTableName( $table )
	{
		if ( !empty( $table ) )
			$this->dbTableName = $table;
	}
	
	/**
	 * @method getTableName
	 * Get's current object Table Name value
	 * @return (string) Table name
	 * 
	 */
	public function getTableName()
	{
		return $this->dbTableName;
	}
	
	/**
	 * 
	 * Checks if connection is alive
	 * @return TRUE if the connection is alive, FALSE if not
	 * 
	 */
	private function testConnection()
	{
		/*** Random SQL Statement ***/
		$sql = 'SELECT * FROM test';
		$this->disableTestConnection = true;
		$this->exec( $sql );
		if ( preg_match( '/(MySQL server has gone away)$/' , $this->errorMsg ) ) {
			/*** Destroy connection and create a new one ***/
			$this->pdo = null;
			if ( !$this->__construct( $this->connOpts ) ) {
				return false;
			}
		}
		$this->disableTestConnection = false;
		$this->errorId = null;
		$this->errorMsg = null;
		return true;
	}
	
	private function CheckConnection( $str )
	{
		if ( preg_match( '/(MySQL server has gone away)$/' , $this->errorMsg ) ) {
			return false;	
		}
		return true;
	}
	
	public function ReConnect()
	{
		/*** Destroy connection and create a new one ***/
		$this->pdo = null;
		if ( !$this->__construct( $this->connOpts ) ) {
			return false;
		}
		return true;	
	}
	
}
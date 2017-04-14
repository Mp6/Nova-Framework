<?php
//Set our namespace
namespace CORE\Database;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

/**
*This object manages connections to a database
*/
class Connection {
	//Static variables for tracking the default connection
	protected static $d_connection, $d_user, $d_server, $d_pass, $d_database;

	//Data about whether or not we are using the default connection
	protected $default;

	//The connection being used
	protected $connection, $user, $server, $pass, $database;

	//Error information about the connection
	protected $error;

	function __construct($username = false, $pass = false, $server = false, $database = false)
	{
		//Check to make sure we either have a database connection or information to create a connection
		if(($username === false || $pass === false || $server === false || $database === false) && self::$d_connection === NULL)
			throw new \CORE\Error\Exception("No Default DB Connection Exists", \CORE\Error\DatabaseError\E_DB_CONNECT_FAIL);
		//If we have a default connection
		else if(($username === false || $pass === false || $server === false || $database === false) && self::$d_connection !== NULL)
		{
			//Grab the default connection data
			$this->connection = self::$d_connection;
			$this->user = self::$d_user;
			$this->server = self::$d_server;
			$this->pass = self::$d_pass;
			$this->database = self::$d_database;
			$this->default = true;

			//Check to make sure we are still connected
			if(!$this->connection->ping())
				$this->AttemptReconnect();
		}
		//If we have enough data for a connection
		else
		{
			$this->user = $username;
			$this->server = $server;
			$this->pass = $pass;
			$this->database = $database;

			//Attempt to connect to the database
			if(!$this->ConnectToDatabase())
				throw new \CORE\Error\Exception("Failed to connect to database, ".$this->error, \CORE\Error\DatabaseError\E_DB_CONNECT_FAIL);

			//Store the default connection if we need to
			if(self::$d_connection === NULL)
			{
				$this->default = true;
				self::$d_user = $this->user;
				self::$d_server = $this->server;
				self::$d_pass = $this->pass;
				self::$d_database = $this->database;
				self::$d_connection = $this->connection;
			}
		}
	}

	function __destruct()
	{

	}

	/**
	*This function disconnects the current database connection
	*/
	public function Disconnect()
	{
		//Check to see if we are connected to the default connection
		if($this->default)
		{
			unset($this->connection);
			self::$d_connection->close();
			unset(self::$d_connection);
		}
		else
		{
			$this->connection->close();
			unset($this->connection);
		}
	}

	/**
	*This function gets the connection, ensuring it's still connected
	*/
	public function GetConnection()
	{
		//Check to see if we have a database connection
		if($this->connection === NULL)
			throw new \CORE\Error\Exception("Could not find Database Connection", \CORE\Error\DatabaseError\E_NO_DB_CONNECTION);

		//Check to make sure the connection still exists
		if(!$this->connection->ping())
			$this->AttemptReconnect();

		//Return the connection
		return $this->connection;
	}

	/*
	=====================================================================================================================================================================
	============================================================       Protected Functions       =========================================================================
	=====================================================================================================================================================================
	*/

	/**
	*This function connects to the database using the stored login information
	*/
	protected function ConnectToDatabase()
	{
		//Attempt to connect to the database
		$this->connection = new \mysqli($this->server, $this->user, $this->pass, $this->database);

		//Check to see if we have an error or not
		if($this->connection->error)
		{
			$this->error = $this->connection->error;
			return false;
		}

		return true;
	}

	/**
	*This function attempts to reconnect to the database if the connection has gone away
	*/
	protected function AttemptReconnect()
	{
		//Try to connect to the database, throw exception on failure
		if(!$this->ConnectToDatabase())
			throw new \Core\Error\Exception("Unable to re-stablish Database Connection", \CORE\Error\DatabaseError\E_DB_CONNECT_FAIL);

		//If we had the default connection, re-store the default
		if($this->default)
			self::$d_connection = $this->connection;
	}
}
?>
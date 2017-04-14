<?php
//Set our namespace
namespace CORE\Database;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

class Table {
	//Connection to the database
	protected $connection;

	//The currently selected table
	protected $table_name, $valid_table;

	//Columns in the table
	protected $columns;

	function __construct($table_name, $connection = NULL) 
	{
		//Grab either a default DB connection or the custom one
		if($connection === NULL)
			$this->connection = new Connection();
		else
			$this->connection = $connection;

		//Attempt to validate the table, if valid grab the column data
		if(($this->valid_table = $this->ValidateTable($table_name)))
		{
			$this->GatherColumnData();
		}
	}

	function __destruct()
	{
		unset($this->connection);
	}

	/**
	*This function returns whether or not we have a valid table selected
	*/

	/*
	=====================================================================================================================================================================
	============================================================       Protected Functions       =========================================================================
	=====================================================================================================================================================================
	*/

	/**
	*This function attempts to select a table from the database and validates it's existance
	*/
	protected function ValidateTable($table_name)
	{
		//Make sure our table name is long enough
		if(strlen($table_name) === 0)
			throw new \CORE\Error\Exception("Table Name Too Short (one character minimum)", \CORE\Error\DatabaseError\E_INVALID_TABLE);

		//Get the list of tables
		$query = new Query($this->connection);
		$tables = $query->Query("SHOW TABLES");

		//Iterate through the list of tables to see if we can validate it
		foreach($tables as $table)
		{
			if(reset($table) === $table_name)
			{
				$this->table_name = reset($table);
				return true;
			}
		}

		//Validate that the table name can actually be used (only letters, numbers, dollar sign, and underscore)
		preg_match("/[0-9a-zA-Z$_]{1,}/", $table_name, $match);

		//Make sure we only have one match
		if(count($match) === 1)
		{
			//Make sure the table name is the same as our match
			if($match[0] === $table_name)
			{
				//Tell the system the table does not exist and store the table name
				$this->table_name = $table_name;
				return false;
			}
		}

		//Throw an exception because the passed table name is not valid
		throw new \CORE\Error\Exception("Table Name Contains Illegal Characters", \CORE\Error\DatabaseError\E_INVALID_TABLE);
	}

	/**
	*This function grabs column data about the current table
	*/
	protected function GatherColumnData()
	{
		//Make sure we have a valid table first
		if(!$this->valid_table)
			throw new \CORE\Error\Exception("Current Table Not Valid", \CORE\Error\DatabaseError\E_INVALID_TABLE);

		//Get the column names
		$query = new Query($this->connection);
		$columns = $query->Query("DESCRIBE ".$this->table_name);

		print_r($columns);
	}
}
?>
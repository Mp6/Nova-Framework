<?php
//Set our namespace
namespace CORE\Database\Query;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

class QueryBase {
	//Object to store tables
	protected $tables;

	//Connection to the database
	protected $connection;

	//The data binder to bind data in a query
	protected $binder;

	function __construct($connection = null)
	{
		//If no connection was passed, grab a default one
		if($connection === NULL)
			$this->connection = new Connection();
		//Store the passed connection
		else
			$this->connection = $connection;

		$this->tables = array();
	}

	/**
	*This function gets the binder object
	*/
	public function GetBinderObject()
	{
		return $this->binder;
	}

	/*
	=====================================================================================================================================================================
	============================================================       Protected Functions       =========================================================================
	=====================================================================================================================================================================
	*/

	/**
	*
	*/
	protected function AddTable($table_name)
	{
		//Make sure the table doesn't already exist
		if($this->GetTable($table_name) !== false)
			return true;

		//Create a table object with the passed data
		$table = new \CORE\Database\Table($table_name, $this->connection);

		//Make sure the table we just created is valid
		if(!$table->ValidTable())
			throw new \CORE\Error\Exception("Failed to validate table", \CORE\Error\DatabaseError\E_INVALID_TABLE);

		//Store the table in our list of tables
		$this->tables[] = $table;
		return true;
	}

	/**
	*
	*/
	protected function GetTable($table_name)
	{
		//Iterate through the tables to find one with a matching name
		foreach($tables as $table)
		{
			if($table->GetTableName() === $table_name)
				return $table;
		}

		return false;
	}

	/**
	*
	*/
	protected function ValidateTableColumn($table_name, $column_name)
	{
		//Make sure the table is valid
		if(($table = $this->GetTable($table_name) === false)
			throw new \CORE\Error\Exception("Failed to validate table", \CORE\Error\DatabaseError\E_INVALID_TABLE);

		if(!$table->ColumnExists($column_name))
			throw new \CORE\Error\Exception("Failed to validate column", \CORE\Error\DatabaseError\E_INVALID_COLUMN);

		return true;
	}
}
?>
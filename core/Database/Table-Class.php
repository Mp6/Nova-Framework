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
	protected $columns, $new_columns, $remove_columns, $change_columns;

	function __construct($table_name, $connection = NULL) 
	{
		//Grab either a default DB connection or the custom one
		if($connection === NULL)
			$this->connection = new Connection();
		else
			$this->connection = $connection;

		//Attempt to validate the table, if valid grab the column data
		if(($this->valid_table = $this->ValidateTable($table_name)))
			$this->GatherColumnData();
		else
			$this->columns = array();

		//Initialize our column arrays
		$this->new_columns = array();
		$this->remove_columns = array();
		$this->change_columns = array();
	}

	function __destruct()
	{
		unset($this->connection);
	}

	/**
	*This function returns whether or not we have a valid table selected
	*/
	public function ValidTable()
	{
		return $this->valid_table;
	}

	/**
	*This function adds a column to the table
	*/
	public function AddColumn($name, $type, $null = true, $key = NULL, $extra = NULL)
	{
		//Validate the column name
		if($this->ColumnExists($name))
			throw new \CORE\Error\Exception("Column Already Exists", \CORE\Error\DatabaseError\E_INVALID_COLUMN);
		if(!$this->ValidateDatabaseName($name))
			throw new \CORE\Error\Exception("Column Name Contains Illegal Characters", \CORE\Error\DatabaseError\E_INVALID_COLUMN);

		//Validate the data type
		if(is_array($type))
		{
			if(!is_int($type[1]))
				throw new \CORE\Error\Exception("Unkown Type Array Parameter", \CORE\Error\DatabaseError\E_INVALID_COLUMN);
			$type_name = $type[0];
		}
		else
			$type_name = $type;
		if(!$this->ValidateDataType($type_name))
			throw new \CORE\Error\Exception("Invalid Data Type", \CORE\Error\DatabaseError\E_INVALID_COLUMN);

		//Validate the key
		if(!$this->ValidateKey($key))
			throw new \CORE\Error\Exception("Invalid Key Type", \CORE\Error\DatabaseError\E_INVALID_COLUMN);

		//Validate the extra variables
		if(!$this->ValidateExtra($extra))
			throw new \CORE\Error\Exception("Invalid Extra Type", \CORE\Error\DatabaseError\E_INVALID_COLUMN);

		//Generate the column and store it in the "new columns" section
		$column = new Table\Column();
		$column->name = $name;
		$column->null = $null;
		$column->key = $key;
		$column->extra = $extra;
		$column->default = NULL;
		if(is_array($type) && is_int($type[1]))
			$column->type = $type_name."(".$type[1].")";
		else
			$column->type = $type_name;

		//Store the column in "new columns"
		$this->new_columns[] = $column;
	}

	/**
	*This function removes a column from a table
	*/
	public function RemoveColumn()
	{

	}

	/**
	*This function modifies an existing column
	*/
	public function ModifyColumn()
	{

	}

	/**
	*This function finalizes updates made to the table and alters it
	*/
	public function FinalizeUpdates()
	{

	}

	/**
	*This function checks to see if a given column name exists
	*/
	public function ColumnExists($column_name)
	{
		//Iterate through the columns in the table to see if it exists
		foreach($this->columns as $column)
		{
			if($column->name === $column_name)
				return true;
		}

		return false;
	}

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
		if($this->ValidateDatabaseName($table_name))
		{
			$this->table_name = $table_name;
			return false;
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

		$this->columns = array();

		foreach($columns as $column)
		{
			$column_obj = new Table\Column();
			$column_obj->name = $column["Field"];
			$column_obj->type = $column["Type"];
			$column_obj->default = $column["Default"];
			if($column["Null"] === "NO")
				$column_obj->null = Table\NullTypes\NOT_NULL;
			else
				$column_obj->null = NULL;
			if($column["Key"] === "PRI")
				$column_obj->key = Table\KeyTypes\PRIMARY;
			else if($column["Key"] === "UNI")
				$column_obj->key = Table\KeyTypes\UNIQUE;
			else
				$column_obj->key = NULL;
			if($column["Extra"] === "auto_increment")
				$column_obj->extra = Table\ExtraTypes\AUTO_INCREMENT;
			else
				$column_obj->extra = NULL;

			$this->columns[] = $column_obj;
		}

		print_r($this->columns);
	}

	/**
	*This function validates a passed column name
	*/
	protected function ValidateDatabaseName($name)
	{
		//Validate that the table name can actually be used (only letters, numbers, dollar sign, and underscore)
		preg_match("/[0-9a-zA-Z$\_]{1,}/", $name, $match);

		//Make sure we only have one match
		if(count($match) === 1)
		{
			//Make sure the table name is the same as our match
			if($match[0] === $name)
				return true;
		}

		return false;
	}

	/**
	*This function validates a passed data type for a column
	*/
	protected function ValidateDataType($data_type)
	{
		//Get the ColumnType constants
		$constants = \CORE\GetCoreConstants("Database\Table\ColumnTypes");

		//Iterate through the constants to find a matching constant
		foreach($constants as $c_data_type)
		{
			if($c_data_type === $data_type)
				return true;
		}

		return false;
	}

	/**
	*This function validates a column's key value
	*/
	protected function ValidateKey($key_type)
	{
		if($key_type === NULL)
			return true;
		return false;
	}

	/**
	*This function validates extra parameters for a column
	*/
	protected function ValidateExtra($extra_type)
	{
		if($extra_type === NULL)
			return true;

		return false;
	}
}
?>
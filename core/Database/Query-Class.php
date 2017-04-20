<?php
//Set our namespace
namespace CORE\Database;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

class Query {
	//The connection to the database for the query
	protected $connection;

	//Stored data for the query

	function __construct($connection = NULL)
	{
		//If no connection was passed, grab a default one
		if($connection === NULL)
			$this->connection = new Connection();
		//Store the passed connection
		else
			$this->connection = $connection;
	}

	/**
	*This function executes a general query
	*/
	public function Query($query_string, $data_binder = NULL)
	{
		try {
			$result = $this->ExecuteQuery($query_string, $data_binder);
		} catch (\CORE\Error\Exception $e) {
			$e->WriteErrorMessage();
			return false;
		}

		return $result;
	}

	/**
	*This function sets the type of query we are executing, the object contains relevant data to the query
	*/
	public function SetQueryObject()
	{

	}

	/**
	*This function executes the query and gets the result
	*/
	public function ExecuteQuery()
	{

	}

	/*
	=====================================================================================================================================================================
	============================================================       Protected Functions       =========================================================================
	=====================================================================================================================================================================
	*/

	/**
	*This function executes a fully assembled query string
	*/
	protected function ExecuteQuery($query, $data_binder)
	{
		//Prepare the query and check to see if we encountered an error
		$db_connection = $this->connection->GetConnection();
		$stmt = $db_connection->prepare($query);

		//Check to see if we encountered an error
		if($db_connection->error)
			throw new \CORE\Error\Exception("Error preparing query, ".$db_connection->error, \CORE\Error\DatabaseError\E_PREPARE_ERROR);
		if($stmt->error)
			throw new \CORE\Error\Exception("Error preparing query, ".$stmt->error, \CORE\Error\DatabaseError\E_PREPARE_ERROR);

		//Bind data if we have any to bind
		if($data_binder !== NULL)
		{
			//Do stuff to bind data and execute the query here
		}
		else
		{
			//Execute the query and get the result
			return $this->GetSTMTResult($stmt);
		}
	}

	/**
	*This function executes a bound mysql_stmt object and returns an associative array as a result
	*/
	protected function GetSTMTResult($stmt)
	{
		//Execute the query
		$stmt->execute();

		if($stmt->error)
			throw new \CORE\Error\Exception("Error executing query, ".$stmt->error, \CORE\Error\DatabaseError\E_FAILED_QUERY);

		//Get the result object
		$result = $stmt->get_result();
		if($result === false)
			return array();
		$fields = $result->fetch_fields();

		//Assemble data types based on fields
		$data_types = array();
		foreach($fields as $field)
		{
			//Check to make sure the field doesn't already exist
			if(array_key_exists($field->name, $data_types))
			{
				trigger_error("Column name already exists!", \E_USER_WARNING);
				continue;
			}

			$data_types[$field->name] = $field->type;
		}

		//Create our return array
		$results = array();
		$count = 0;

		//Iterate through the results
		while($row = $result->fetch_assoc())
		{
			//Further tests required to determine if this is necessary
			/*
			//Convert data pulled from the query to ensure it's data type matches that of the column
			foreach($row as $field=>$data)
			{
				trigger_error("Data Type: ".gettype($data), \E_USER_NOTICE);
				//Make sure we have a data type for this column
				if(!array_key_exists($field, $data_types))
				{
					trigger_error("Data type for ".$field." could not be found!", \E_USER_WARNING);
					continue;
				}

				//Convert data where necessary
				switch($data_types[$field])
				{
					//Bool
					case 1:
						$row[$field] = boolval($data);
					break;
					//Integers
					case 2:
					case 3:
					case 8:
					case 9:
					case 13:
						$row[$field] = intval($data);
					break;
					//Float value
					case 4:
						$row[$field] = floatval($data);
					break;
					//Double/real value
					case 5:
						$row[$field] = doubleval($data);
					break;
				}
			}
			*/

			//Store the row data and increment count
			$results[$count] = $row;
			$count++;
		}

		return $results;
	}
}
?>
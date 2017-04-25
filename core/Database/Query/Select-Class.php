<?php
//Set our namespace
namespace CORE\Database\Query;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

class Select extends QueryBase {
	//In the form table=>class
	protected $table, $columns;

	function __construct($connection = NULL)
	{
		parent::__construct($connection);

		$this->columns = array();
		$this->table = false;
	}

	/**
	*
	*/
	public function SetTable($table)
	{
		if($this->table !== false)
			throw new \CORE\Error\Exception("Table already set", \CORE\Error\DatabaseError\E_NO_QUERY_TYPE);
		try {

		} catch (\CORE\Error\Exception $e) {
			$e->WriteErrorMessage();
			return false;
		}

		return true;
	}

	/**
	*
	*/
	public function AddColumn($table, $column)
	{
		try {
			//Add the table to this object
			$this->AddTable($table);
			$this->ValidateTableColumn($table, $column);

			//Store the column and table name
			$this->columns[] = $table.".".$column;
		} catch (\CORE\Error\Exception $e) {
			$e->WriteErrorMessage();
			return false;
		}

		return true;
	}
}
?>
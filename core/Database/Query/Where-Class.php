<?php
//Set our namespace
namespace CORE\Database\Query;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

class Where extends QueryBase {

	protected $clause_type;
	protected $columns, $comparisons;

	function __construct($connection = NULL)
	{
		parent::__construct($connection);

		//Set the default clause type to AND
		$this->clause_type = Where\ClauseBinders\AND;

		//Initialize the storage arrays
		$this->columns = array();
		$this->comparisons = array();
	}

	/**
	*This function sets the where clause type (and or or)
	*/
	public function SetClauseType($type)
	{
		//Validate the clause type
		if(!\CORE\ValidateCoreConstant("Database\Where\ClauseBinders", $type))
			throw new \CORE\Error\Exception("Invalid clause type", \CORE\Error\DatabaseError\E_INVALID_CLAUSE);

		//Store the clause type
		$this->clause_type = $type;
	}

	/**
	*This function adds a clause to the where statement, comparing a column value to a passed value
	*/
	public function AddClause($table, $column, $comparison, $value)
	{
		try {
			//Validate the table and our column
			$this->AddTable($table);
			$this->ValidateTableColumn($table, $column);

			//Validate the passed comparison
			if(!\CORE\ValidateCoreConstant("Database\Where\Comparisons", $comparison))
				throw new \CORE\Error\Exception("Invalid comparison type", \CORE\Error\DatabaseError\E_INVALID_COMPARISON);

			//Add the data to our data binder
			$this->binder->AddBoundData($value);

			//Store the passed data in our where clause
			$this->columns[] = $table.".".$column;
			$this->comparisons[] = $comparison;
		} catch (\CORE\Error\Exception $e) {
			$e->WriteErrorMessage();
			return false;
		}

		return true;
	}

	/*
	=====================================================================================================================================================================
	============================================================       Protected Functions       =========================================================================
	=====================================================================================================================================================================
	*/
}
?>
<?php
//Set our namespace
namespace CORE\Database\Query;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

class QueryBase {
	//Object to store tables
	protected $tables;

	function __construct()
	{

	}

	/*
	=====================================================================================================================================================================
	============================================================       Protected Functions       =========================================================================
	=====================================================================================================================================================================
	*/

	/**
	*
	*/
	protected function AddTable()
	{

	}

	/**
	*
	*/
	protected function ValidateTableColumn($table, $column)
	{

	}

	/**
	*
	*/
	protected function AddToBinder($value, $type)
	{

	}
}
?>
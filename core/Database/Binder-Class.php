<?php
//Set our namespace
namespace CORE\Database;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

class Binder {
	protected $types, $data;

	function __construct() 
	{
		$this->types = array();
		$this->data = array();
	}

	/**
	*
	*/
	public function AddBoundData($data)
	{
		switch(gettype($data))
		{
			case "integer":
				$type = "i";
			break;
			case "double":
				$type = "d";
			break;
			case "NULL":
			case "string":
				$type = "s";
			break;
			default:
				throw new \CORE\Error\Exception("Invalid data type when adding bound data", \CORE\Error\DatabaseError\E_INVALID_BIND_TYPE);
			break;
		}

		$this->types[] = $type;
		$this->data[] = $data;
	}

	/**
	*
	*/
	public function GetBindString()
	{
		return implode($this->types);
	}

	/**
	*
	*/
	public function GetBindData()
	{
		return $this->data;
	}
}
?>
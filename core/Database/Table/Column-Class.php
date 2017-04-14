<?php
//Set our namespace
namespace CORE\Database\Table;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

class Column {
	//Data regarding the column
	public $type;
	public $name;
	public $null;
	public $key;
	public $default;
	public $extra;
}
?>
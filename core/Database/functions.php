<?php
//Set our namespace
namespace CORE\Database;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

//Include all database classes and files
require_once "Connection-Class.php";
require_once "Query-Class.php";
require_once "Binder-Class.php";
require_once "Table-Class.php";
require_once "Table/Column-Class.php";

function DefineConstants($settings)
{
	//Define table constants
	define(__NAMESPACE__."\Table\ColumnTypes\VARCHAR", "VARCHAR");
}

function UpdateCoreTables()
{

}
?>
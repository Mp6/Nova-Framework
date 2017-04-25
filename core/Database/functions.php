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
	//Define TEXT data types
	define(__NAMESPACE__."\Table\ColumnTypes\VARCHAR", "VARCHAR");
	define(__NAMESPACE__."\Table\ColumnTypes\CHAR", "CAR");
	define(__NAMESPACE__."\Table\ColumnTypes\TINYTEXT", "TINYTEXT");
	define(__NAMESPACE__."\Table\ColumnTypes\TEXT", "TEXT");
	define(__NAMESPACE__."\Table\ColumnTypes\BLOB", "BLOB");
	define(__NAMESPACE__."\Table\ColumnTypes\MEDIUMTEXT", "MEDIUMTEXT");
	define(__NAMESPACE__."\Table\ColumnTypes\LONGTEXT", "LONGTEXT");
	define(__NAMESPACE__."\Table\ColumnTypes\LONGBLOB", "LONGBLOB");
	//Define NUMBER data types
	define(__NAMESPACE__."\Table\ColumnTypes\TINYINT", "TINYINT");
	define(__NAMESPACE__."\Table\ColumnTypes\SMALLINT", "SMALLINT");
	define(__NAMESPACE__."\Table\ColumnTypes\MEDIUMINT", "MEDIUMINT");
	define(__NAMESPACE__."\Table\ColumnTypes\INT", "INT");
	define(__NAMESPACE__."\Table\ColumnTypes\BIGINT", "BIGINT");
	define(__NAMESPACE__."\Table\ColumnTypes\FLOAT", "FLOAT");
	define(__NAMESPACE__."\Table\ColumnTypes\DOUBLE", "DOUBLE");
	define(__NAMESPACE__."\Table\ColumnTypes\DECIMAL", "DECIMAL");
	//Define DATE data types
	define(__NAMESPACE__."\Table\ColumnTypes\DATE", "DATE");
	define(__NAMESPACE__."\Table\ColumnTypes\DATETIME", "DATETIME");
	define(__NAMESPACE__."\Table\ColumnTypes\TIMESTAMP", "TIMESTAMP");
	define(__NAMESPACE__."\Table\ColumnTypes\TIME", "TIME");
	define(__NAMESPACE__."\Table\ColumnTypes\YEAR", "YEAR");
	//Define NULL types
	define(__NAMESPACE__."\Table\NullTypes\NOT_NULL", "NOT NULL");
	//Define KEY types
	define(__NAMESPACE__."\Table\KeyTypes\PRIMARY", "UNIQUE PRIMARY KEY");
	define(__NAMESPACE__."\Table\KeyTypes\UNIQUE", "UNIQUE");
	//Define EXTRA types
	define(__NAMESPACE__."\Table\ExtraTypes\AUTO_INCREMENT", "AUTO_INCREMENT");

	//Define where constants
	//Comparison Types
	define(__NAMESPACE__."\Where\Comparisons\EQUAL", "=");
	define(__NAMESPACE__."\Where\Comparisons\NOTEQUAL", "<>");
	define(__NAMESPACE__."\Where\Comparisons\GREATER", ">");
	define(__NAMESPACE__."\Where\Comparisons\GREATEREQUAL", ">=");
	define(__NAMESPACE__."\Where\Comparisons\LESSEQUAL", "<=");
	define(__NAMESPACE__."\Where\Comparisons\LESS", "<");
	define(__NAMESPACE__."\Where\Comparisons\LIKE", "LIKE");
	//Bind Types
	define(__NAMESPACE__."\Where\ClauseBinders\AND", "AND");
	define(__NAMESPACE__."\Where\ClauseBinders\OR", "OR");

}

function UpdateCoreTables()
{

}
?>
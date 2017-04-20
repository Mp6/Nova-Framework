<?php
namespace CORE;

//Define a variable to prevent files from being called directly
define(__NAMESPACE__."\INIT", true);

//Parse the settings
$settings = parse_ini_file("config.ini.php", true);

//Include the core code objects
require_once "functions.php";
require_once "Errors/functions.php";
require_once "Database/functions.php";

//Initialize these objects using the stored settings

//Initialize core settings
DefineCoreConstants($settings["Core"]);

//Initialize the error handler
Error\DefineConstants($settings["Error"]);

//Set our error handling function
\set_error_handler('\CORE\Error\ErrorHandler');

//Initialize database constants
$db_settings = $settings["Database"];
Database\DefineConstants($db_settings);

//Initialize the database connection
new Database\Connection($db_settings["username"], $db_settings["password"], $db_settings["server_address"], $db_settings["database"]);

//Check to see if any core database tables need updating
Database\UpdateCoreTables();

$query_obj = new Database\Query();
if($query_obj->Query("DROP TABLE TEST5") === false)
	trigger_error("Failed to drop table", \E_USER_WARNING);
else
	trigger_error("Table dropped succesfully", \E_USER_NOTICE);

//Add new table
$table = new Database\Table("TEST5");
$table->AddColumn("id", array(Database\Table\ColumnTypes\INT, 3), Database\Table\NullTypes\NOT_NULL, Database\Table\KeyTypes\PRIMARY, Database\Table\ExtraTypes\AUTO_INCREMENT);
$table->AddColumn("test1", array(Database\Table\ColumnTypes\VARCHAR, 32), Database\Table\NullTypes\NOT_NULL, Database\Table\KeyTypes\UNIQUE);
$table->AddColumn("test2", Database\Table\ColumnTypes\TEXT);

//Finalize the changes to the table
$table->FinalizeUpdates();

//Test alter columns
$table->AddColumn("modified", Database\Table\ColumnTypes\DATETIME, Database\Table\NullTypes\NOT_NULL);
$table->RemoveColumn("test1");
$table->ModifyColumn("test2", Database\Table\ColumnTypes\TEXT, Database\Table\NullTypes\NOT_NULL);

//Finalize the changes to the table
$table->FinalizeUpdates();

//Unset the database settings
unset($db_settings);
?>
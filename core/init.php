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

$table = new Database\Table("TEST");

//Unset the database settings
unset($db_settings);
?>
<?php
//Set the file's namespace
namespace CORE;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

/**
*This function defines core constants from the config file
*/
function DefineCoreConstants($settings)
{
	//Set date/time formate constants
	define(__NAMESPACE__."\Settings\DateFormat", $settings["date_format"]);
	define(__NAMESPACE__."\Settings\TimeFormat", $settings["time_format"]);
	define(__NAMESPACE__."\Settings\DateTimeFormat", $settings["date_format"]." ".$settings["time_format"]);
}
?>
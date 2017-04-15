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

/**
*This function gets constants with a certain naming convention in the CORE namespace
*/
function GetCoreConstants($namespace)
{
	//Get user defined constants and check to see if they are in the passed namespace
	$return = array();
	foreach(get_defined_constants(true)["user"] as $key=>$value)
	{
		//If in the passed namespace add to the return array
		if(strpos($key, __NAMESPACE__."\\".$namespace) !== false)
			$return[$key] = $value;
	}

	return $return;
}
?>
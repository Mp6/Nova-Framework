<?php
//Set the file's namespace
namespace CORE\Error;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

//Include other error code
require_once "Exception-Class.php";

/**
*This function defines the error namespace settings constants and custom error codes
*
* @post Error constants have been defined for access via the rest of the program
*/
function DefineConstants($settings)
{
	//Define settings
	define(__NAMESPACE__."\Settings\Debug", $settings["debug"]);
	define(__NAMESPACE__."\Settings\Output\Console", $settings["console_output"]);
	define(__NAMESPACE__."\Settings\Output\Window", $settings["window_output"]);
	define(__NAMESPACE__."\Settings\Output\Log", $settings["log_output"]);

	//General error codes
	define(__NAMESPACE__."\GeneralError\E_DEBUG_OUTPUT", 17);

	//Database error codes
	define(__NAMESPACE__."\DatabaseError\E_DB_CONNECT_FAIL", 129);
	define(__NAMESPACE__."\DatabaseError\E_NO_DB_CONNECTION", 130);
	define(__NAMESPACE__."\DatabaseError\E_NO_QUERY_TYPE", 131);
	define(__NAMESPACE__."\DatabaseError\E_NO_TABLE", 132);
	define(__NAMESPACE__."\DatabaseError\E_INVALID_TABLE", 133);
	define(__NAMESPACE__."\DatabaseError\E_COLUMN_NOT_FOUND", 134);
	define(__NAMESPACE__."\DatabaseError\E_PREPARE_ERROR", 135);
	define(__NAMESPACE__."\DatabaseError\E_INVALID_COLUMN", 136);
	define(__NAMESPACE__."\DatabaseError\E_FAILED_QUERY", 137);
	define(__NAMESPACE__."\DatabaseError\E_INVALID_BIND_TYPE", 138);
	define(__NAMESPACE__."\DatabaseError\E_INVALID_COMPARISON", 139);
	define(__NAMESPACE__."\DatabaseError\E_INVALID_CLAUSE", 140);
}

/**
*This function handles errors triggered by the codebase and outputs messages to appropriate locations
*/
function ErrorHandler($error_number, $error_string, $error_file, $error_line, $error_context = false)
{
	//Get the file (not the full directory) that the error occured
	$file_single = explode("/", $error_file);
	$file_single = $file_single[count($file_single) - 1];

	//Variables for tracking the error for proper message generation
	$die = false;
	$type_name = "";

	//Based on the error, provide various outputs
	switch($error_number)
	{
		//Standard PHP Errors
		case \E_ERROR:
			$type_name = "Unhandled Error E_ERROR";
		case \E_WARNING:
			$type_name = "Unhandled Error E_WARNING";
		break;
		case \E_PARSE:
			$type_name = "Unhandled Error E_PARSE";
		break;
		case \E_NOTICE:
			$type_name = "Notice";
		break;
		case \E_CORE_ERROR:
			$type_name = "Unhandled Error E_CORE_ERROR";
		break;
		case \E_CORE_WARNING:
			$type_name = "Unhandled Error E_CORE_WARNING";
		break;
		case \E_COMPILE_ERROR:
			$type_name = "Unhandled Error E_COMPILE_ERROR";
		break;
		case \E_COMPILE_WARNING:
			$type_name = "Unhandled Error E_COMPILE_WARNING";
		break;
		case \E_USER_ERROR:
			$type_name = "Fatal Error";
			$die = true;
		break;
		case \E_USER_WARNING:
			$type_name = "Warning";
		break;
		case \E_USER_NOTICE:
			$type_name = "Notice";
		break;
		case \E_STRICT:
			$type_name = "Fatal Error";
			$die = true;
		break;
		case \E_RECOVERABLE_ERROR:
			$type_name = "Recoverable Fatal Error";
		break;
		case \E_DEPRECATED:
			$type_name = "Deprecated Code Detected";
		break;
		case \E_ALL:
			$type_name = "Unhandled Error E_ALL";
		break;

		//General Errors
		case GeneralError\E_DEBUG_OUTPUT:
			$type_name = "Debug Output";
		break;

		//Database Errors
		case DatabaseError\E_DB_CONNECT_FAIL:
			$type_name = "Database Connection Failed";
		break;
		case DatabaseError\E_NO_DB_CONNECTION:
			$type_name = "No Database Connection Found";
		break;
		case DatabaseError\E_NO_QUERY_TYPE:
			$type_name = "No Query Type Selected";
		break;
		case DatabaseError\E_NO_TABLE:
			$type_name = "No Table Selected";
		break;
		case DatabaseError\E_INVALID_TABLE:
			$type_name = "Table Could Not Be Found";
		break;
		case DatabaseError\E_COLUMN_NOT_FOUND:
			$type_name = "Column Could Not Be Found";
		break;
		case DatabaseError\E_PREPARE_ERROR:
			$type_name = "Failed To Prepare SQL Query";
		break;
		case DatabaseError\E_INVALID_COLUMN:
			$type_name = "Column Not Valid";
		break;
		case DatabaseError\E_FAILED_QUERY:
			$type_name = "Query Failed to Execute";
		break;
		case DatabaseError\E_INVALID_BIND_TYPE:
			$type_name = "Invalid Data Type in Bind Object";
		break;
		case DatabaseError\E_INVALID_COMPARISON:
			$type_name = "Invalid Comparison";
		break;
		case DatabaseError\E_INVALID_CLAUSE:
			$type_name = "Invalid Clause";
		break;

		//Unhandled Error Codes
		default:
			$type_name = "UNKWON ERROR CODE! PANIC!!!";
		break;
	}

	//Prevent the user from seeing any output that is not our error message
	if($die)
		ob_clean();

	//Check to see where we are going to output the error and generate formatted messages
	if(Settings\Output\Console)
	{
		//Replace the illegal character \n in our error string with an escaped string
		$error_string = str_replace("\n", "\\n", $error_string);
		$script_message = ReplaceMessageText($type_name, $error_string, $error_line, $error_file, "===PHP ERROR===\\nSeverity: E_TYPE\\nMessage: E_MESSAGE\\nFile: E_FILE\\nLine: E_LINE\\n\\n");
		OutputScriptMessage($script_message);
	}
	if(Settings\Output\Window)
	{
		$window_message = ReplaceMessageText($type_name, $error_string, $error_line, $error_file, "<b>E_TYPE:</b> E_MESSAGE at line E_LINE in E_FILE");
		OutputWindowMessage($window_message);
	}
	if(Settings\Output\Log)
	{
		$log_message = ReplaceMessageText($type_name, $error_string, $error_line, $error_file, "E_TYPE: E_MESSAGE, in file E_FILE at line E_LINE\n");
		OutputLogMessage($log_message);
	}

	//Check to see if we need to kill the script
	if($die)
	{
		ob_end_flush();
		die();
	}

	return true;
}

/**
*This function replaces certain keys in an error message with the actual values of the message
*
* @return String
*/
function ReplaceMessageText($type_name, $error_string, $error_line, $error_file, $message)
{
	$message = str_replace("E_TYPE", $type_name, $message);
	$message = str_replace("E_MESSAGE", $error_string, $message);
	$message = str_replace("E_LINE", $error_line, $message);
	$message = str_replace("E_FILE", $error_file, $message);

	return $message;
}

/**
*This function outputs a message to the javascript console when a PHP error has occured
*/
function OutputScriptMessage($message)
{
	$message = str_replace("'", "\\'", $message);
	echo "<script>console.log(\"".$message."\");</script>";
}

/**
*This function outputs a message to the browser window when a PHP error has occured
*/
function OutputWindowMessage($message)
{
	echo "<pre>";
	echo $message;
	echo "</pre>";
}

/**
*This function outputs a message to the log file when a PHP error has occured
*/
function OutputLogMessage($message)
{
	//Get the file location of the log file
	$log_location = dirname(__DIR__)."/log.php";

	//Check if the file exists and if not create it
	if(!file_exists($log_location))
		file_put_contents($log_location, "<?php/*\n");

	//Grab the timestamp and output it in the log file
	$timestamp = "[".date(\CORE\Settings\DateTimeFormat)."] ";

	//Put the message into the output log
	file_put_contents($log_location, $timestamp.$message, \FILE_APPEND);
}
?>
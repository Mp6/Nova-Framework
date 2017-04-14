<?php
//Set the file's namespace
namespace CORE\Error;

//Make sure the files are not being accessed directly
if(\CORE\INIT !== true)
	die("Wumbo");

/**
*This class extends the base exception class to be able to write error messages with our error handler
*/
class Exception extends \Exception
{
	function __construct($message, $code = 0, \Exception $previous = NULL)
	{
		parent::__construct($message, $code, $previous);
	}

	/**
	*This function uses the custom ErrorHandler function to write the message based on config.ini settings
	*/
	public function WriteErrorMessage()
	{
		ErrorHandler($this->code, "Exception Caught (".$this->message.")", $this->file, $this->line);
	}
}

?>
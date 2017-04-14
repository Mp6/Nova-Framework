<?php
namespace CORE;

function DefineCoreConstants($settings)
{
	define(__NAMESPACE__."\Settings\DateFormat", $settings["date_format"]);
	define(__NAMESPACE__."\Settings\TimeFormat", $settings["time_format"]);
	define(__NAMESPACE__."\Settings\DateTimeFormat", $settings["date_format"]." ".$settings["time_format"]);
}
?>
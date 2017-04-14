<?php
ob_start();

//Attempt to initialize the CORE system
try {
	require_once "core/init.php";
} catch (CORE\Error\Exception $e) {
	$e->WriteErrorMessage();
	ob_end_flush();
	die();
}

//Execute the request via the URI
try {

} catch (CORE\Error\Exception $e) {

}

ob_end_flush();
?>
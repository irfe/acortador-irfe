<?php
/**
 * error.php
 *
 * @author Simon van Daalen <si5m@toaos.tk>
 * @copyright 2013, http://www.toaos.tk
 * @package error.php
 */

/**
* Settings
*/
$title		= ""; //The title that is displayed next to the error title e.g. (404 not found[ .::. TOAOS])
$redirect	= "/"; //Redirect if the error ID is not set

/**
* Script
*/
if(isset($_GET['id']) && is_numeric($_GET['id'])){
	
	switch ($_GET['id']){
		case '401':
			$code		= '401';
			$msg		= '401 Not authorized';
			$longmsg	= 'You\'re not authorized to view this file.';
		break;
		case '403':
			$code		= '403';
			$msg		= '403 Forbidden';
			$longmsg	= 'You don\'t have permission to access this page.';
		break;
		case '404':
			$code		= '404';
			$msg		= '404 Not found';
			$longmsg	= 'The page that you have requested could not be found.';
		break;
		case '408':
			$code		= '408';
			$msg		= '408 Request timeout';
			$longmsg	= 'Request timeout, try reloading the page.';
		break;
		case '410':
			$code		= '410';
			$msg		= '410 Resource gone';
			$longmsg	= 'There used to be a page here, but no forwarding address is known.';
		break;
		case '500':
			$code		= '500';
			$msg		= '500 Internal server error';
			$longmsg	= 'Internal server error, try reloading the page or go back.';
		break;
		case '503':
			$code		= '503';
			$msg		= '503 Service unavailable';
			$longmsg	= 'Connection Refused.';
		break;
		case '504':
			$code		= '504';
			$msg		= '504 Gateway timeout';
			$longmsg	= 'Gateway timeout, try reloading the page.';
		break;
		case '505':
			$code		= '505';
			$msg		= '505 HTTP version not supported';
			$longmsg	= 'The server does not support the HTTP protocol version used in the request.';
		break;
		default:
			$code		= 'Error';
			$msg		= 'Error';
			$longmsg	= 'Something went wrong.';
		break;
	}
	
	die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>'.$msg.'</title>
</head><body>
<h1>'.$msg.'</h1>
<p>'.$longmsg.'</p>
<hr>
<address>Apache/2.2.24 Server at i.irfe.cl Port 80</address>
</body></html>');
	
} else {
	header('Location: ' . $redirect);
	die;
}

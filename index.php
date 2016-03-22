<?php

	
	// ===================================================================================================
	// Configure some PHP-Settings 
	// ===================================================================================================

	error_reporting(E_ALL);											// Switch on strict error-reporting (we don't want any Notices, Warnings, whatever)
	@set_magic_quotes_runtime(0);										// Turn off magic quotes
	@ini_set('mssql.min_message_severity','17');								// disable some MSSQL-stuff
	@mssql_min_message_severity(17);									//

	// ===================================================================================================
	// CONSTANTS
	// ===================================================================================================

	define('BASEDIR',	realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);				// Set BaseDir

	// ===================================================================================================
	// Include needed files
	// ===================================================================================================

	require_once('includes'.DIRECTORY_SEPARATOR.'inc.config.php');						// Configuration
	require_once('includes'.DIRECTORY_SEPARATOR.'inc.constants.php');					// Constants
	
	require_once('includes'.DIRECTORY_SEPARATOR.'inc.functions.php');					// Common Functions
	require_once('includes'.DIRECTORY_SEPARATOR.'inc.functions.db.php');					// Database functions
	require_once('includes'.DIRECTORY_SEPARATOR.'inc.functions.security.php');				// Security-Functions

	require_once('classes'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'Smarty.class.php');		// Template-class

	// ===================================================================================================
	// Ultra-simple banning
	// ===================================================================================================

	if (isIPBanned())
	{
		header("HTTP/1.1 403 Forbidden");
		exit();
	}

	// ===================================================================================================
	// Pseudo-Cronjobs
	// ===================================================================================================

	pseudoCron();

	// ===================================================================================================
	// This is where the work starts :-)
	// ===================================================================================================

	session_start();

	if (get_magic_quotes_gpc())
	{
	        $_GET		= stripslashes_deep($_GET);
	        $_POST		= stripslashes_deep($_POST);
	        $_COOKIE	= stripslashes_deep($_COOKIE);
	}

	// ===================================================================================================
	// Handle act-param
	// ===================================================================================================

	if ((!isset($_GET['act'])) && (!isset($_POST['act']))) 
		$action		= 'index'; 
	else 
		$action		= ((isset($_POST['act'])) ? $_POST['act'] : $_GET['act']);

	$action		= strtolower(trim(preg_replace('/[^0-9a-z]/i','',$action)));
	
	// ===================================================================================================
	// Assign some global Smarty-variables
	// ===================================================================================================
	
	$oSmarty			= new Smarty();

	assignSmartyVars();
		
	// ===================================================================================================
	// Depending on the passed function, do whatever is needed
	// ===================================================================================================
	
	switch ($action)
	{
		
		// ===========================================================================================
		// Functions related to CAPTCHAs
		// ===========================================================================================

		case 'playcaptcha'	:
		case 'getcaptcha'	:

			require_once('includes'.DIRECTORY_SEPARATOR.'inc.functions.captcha.php');

			if ($action=='playcaptcha')
				captchaPlay();
			else
				captchaShow();
			
			break;

		// ===========================================================================================
		// Admin-Functions
		// ===========================================================================================

		case 'admin'		:

			require_once('pages'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'inc.'.$action.'.php');
			break;

		// ===========================================================================================
		// Graph-Functions
		// ===========================================================================================

		case 'graph'		:

			require_once('pages'.DIRECTORY_SEPARATOR.'inc.'.$action.'.php');
			break;

		// ===========================================================================================
		// Account-Functions
		// ===========================================================================================

		case 'forgot'		:
		case 'account'		:
		case 'activate'		:
		case 'login'		:
		case 'logout'		:
		case 'register'		:

			require_once('pages'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'inc.'.$action.'.php');
			break;

		// ===========================================================================================
		// Normal-Functions
		// ===========================================================================================

		case 'newsview'		:			// news-system
		case 'news'		:
		case 'ranking'		:
		case 'world'		:			// the rest
		case 'rules'		:
		case 'downloads'	:
		case 'index'		:

			require_once('pages'.DIRECTORY_SEPARATOR.'inc.'.$action.'.php');
			break;

		default			:

			die('Unknown page.');

	}

	// ===================================================================================================
	// If $error is true somewhere happened something terrible :-(
	// ===================================================================================================

	if ($error)
        	$oSmarty->display('file:pages/site_error.tpl');

	// ===================================================================================================
	// Some debug-code
	// ===================================================================================================

	if (defined('DEBUG') && (DEBUG == true))
	{

		require_once('classes'.DIRECTORY_SEPARATOR.'dbug'.DIRECTORY_SEPARATOR.'dBug.php');

		$stats['end']		= microtime(true);
		$stats['elapsed']	= microtime(true)-$stats['start'];

		echo '<br /><br /><hr /><br />';

		new dBug($stats);
		echo '<br /><br />';

		new dBug($config);
		echo '<br /><br />';

		if (is_array($_GET) && (count($_GET) > 0))
		{
			new dBug($_GET);
			echo '<br /><br />';
		}

		if (is_array($_POST) && (count($_POST) > 0))
		{
			new dBug($_POST);
			echo '<br /><br />';
		}

		if (is_array($_SESSION) && (count($_SESSION) > 0))
		{
			new dBug($_SESSION);
			echo '<br /><br />';
		}

		if (is_array($_COOKIE) && (count($_COOKIE) > 0))
		{
			new dBug($_COOKIE);
			echo '<br /><br />';
		}

	}

?>
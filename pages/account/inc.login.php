<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.login.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3b      : some minor changes regarding filenames
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	if (!function_exists('validAccountname'))
		require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.validate.php');

	//

	if (isset($_POST['login']))
	{

		// Fill variables

		$username	= trim(isset($_POST['username']) ? $_POST['username'] : '');
		$password	= trim(isset($_POST['password']) ? $_POST['password'] : '');

		$errors		= array();

		// Assign Smarty-Vars

		$oSmarty->assign('form_username',		htmlspecialchars($username));

		// Check variables

		if ($username == '')
			$errors[]	= 'No username given.';
		else
		{
			if (!validAccountname($username))
				$errors[]	= 'Invalid Username.';
		}

		if ($password == '')
			$errors[]	= 'No password given.';
		else
		{
			if (!validPassword($password))
				$errors[]	= 'Invalid Password.';
		}

		// OK, lets see if we have errors or if we can login

		if (count($errors) > 0)
		{

			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> $errors	) );

		}
		else
		{

			if (loginUser($username,$password))
			{
				// User could be logged in -> redirect to startpage
				header('location: '.site_url('account'));
			}
			else
			{

				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> 'Wrong username or password.'	) );

			}

		}

	}

	// Build Breadcrumb-Navigation

	$breadcrumbs	= array();

	$breadcrumbs[]	= array( 'caption'	=> 'Home',
				 'url'		=> $config['script_url']			);

	$breadcrumbs[]	= array( 'caption'	=> 'Login',
				 'url'		=> ''						);

	$oSmarty->assign('breadcrumbs',		$breadcrumbs);

	// Display Template

  	if (!$error)
		$oSmarty->display('file:pages/account/site_login.tpl');

?>
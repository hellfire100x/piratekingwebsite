<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.forgot.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3b      : some minor changes regarding filenames
	// v0.0.2       : fixed some bugs regarding wrong databases
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

	if (!function_exists('loadAccountByName'))
		require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.account.php');

	if (!class_exists('Securimage'))
		require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'securimage'.DIRECTORY_SEPARATOR.'securimage.php');

	// ===================================================================================================

	if (isset($_POST['forgot']))
	{

		// Fill variables

		$username	= trim(isset($_POST['username']) ? $_POST['username'] : '');
		$captcha	= trim(isset($_POST['captcha']) ? $_POST['captcha'] : '');

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

		if ($captcha=='')
			$errors[]	= 'You have\'t entered the captcha-code.';
		else
		{

			// OK, lets see if the entered CAPTCHA is correct

			$oCaptcha	= new Securimage();

			if ($oCaptcha->check($captcha) == false)
				$errors[]	= 'The captcha-code you entered was not correct.';

			unset($oCaptcha);

		}

		// OK, lets see if we have errors or if we can login

		if (count($errors) > 0)
		{

			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> $errors	) );

		}
		else
		{

			$data		= loadAccountByName($username);

			if ($data !== false)
			{

				$remind_ok	= true;

				// Check if we already have a waiting reminder

				$remind		= loadReminderByName($data['name']);

				if ($remind !== false)
				{

					// This function is only useable every 10 minutes to prevent evil people from spamming users

					if ((time()-$remind['used']) < 600)
					{
						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array('The \'Forgot Password\'-function was already used for this account less than 10 minutes ago.',
												       				 'Please try again later.'	) ) );

						$remind_ok	= false;
					}

				}

				if ($remind_ok)
				{

					// Create entry in account_invt (Yeah, we are abusing that table a little bit)

					$code		= createReminderEntry($data['name']);

					if ($code!='')
					{

						// Send eMail to player

						$oSmarty->assign('code',	$code);
						$oSmarty->assign('log_ip',	$_SERVER['REMOTE_ADDR']);
						$oSmarty->assign('accountname',	$data['name']);

						$mail		= $oSmarty->fetch('file:others/mail_forgot.tpl');

						if (sendmail($data['email'],$config['servername'].' - Password Reminder',$mail))
						{
							$oSmarty->assign('message',			array( 'type'		=> 'ok',
													       'messages'	=> array('An eMail was sent to your eMail-adress containing information on how to retrieve a new password.'	) ) );
						}

					}
					else
					{

						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array('Couldn\'t write reminder-code to database.'	) ) );

					}

				}

			}
			else
			{

				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> 'Wrong username.'	) );

			}

		}

	}
	elseif (isset($_GET['code']))
	{

		$code		= $_GET['code'];
		$code		= strtolower(trim(preg_replace('/[^0-9a-f]/i','',$code)));

		if (strlen($code)<>40)
		{
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> 'Invalid reminder-code.'	) );
		}
		else
		{

			$remind		= loadReminderByCode($code);

			if ($remind !== false)
			{

				$pw	= randomPassword();

				if (updatePassword('name',$remind['assignto'],$pw))
				{

					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> 'Your password was changed to: '.$pw	) );

					// remove reminder-entry

					$sql	= 'DELETE FROM '.TABLE_ACCOUNT_INVT.' WHERE UPPER(invt_code) = \''.addslashes_mssql($code).'\'';

					doQuery($sql, DATABASE_ACCOUNT);

				}
				else
				{
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> 'Password could not be updated.'	) );
				}

			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> 'Reminder-code does not exist in database.'	) );
			}
		}
	}

	// Build Breadcrumb-Navigation

	$breadcrumbs	= array();

	$breadcrumbs[]	= array( 'caption'	=> 'Home',
				 'url'		=> $config['script_url']			);

	$breadcrumbs[]	= array( 'caption'	=> 'Password Reminder',
				 'url'		=> ''						);

	$oSmarty->assign('breadcrumbs',		$breadcrumbs);

	// Display Template

  	if (!$error)
		$oSmarty->display('file:pages/account/site_forgot.tpl');

?>
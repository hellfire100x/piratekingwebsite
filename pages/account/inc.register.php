<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.register.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3b      : some minor changes regarding filenames
	// v0.0.2       : added the feature to disable registrations
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	//

	$reg_ok		= false;

	//

	if (!$config['disable_registration'])
	{

		// ===================================================================================================

		if (!function_exists('validAccountname'))
			require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.validate.php');

		if (!function_exists('checkDuplicateAccountName'))
			require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.account.php');

		//

		if (isset($_POST['register']))
		{

			// Looks like someone wants to register

			$username	= trim((isset($_POST['reg_username']) ? $_POST['reg_username'] : ''));
			$password	= trim((isset($_POST['reg_password']) ? $_POST['reg_password'] : ''));
			$password2	= trim((isset($_POST['reg_password2']) ? $_POST['reg_password2'] : ''));
			$email		= trim((isset($_POST['reg_email']) ? $_POST['reg_email'] : ''));

			if ($config['use_captcha'])
				$captcha	= trim((isset($_POST['reg_captcha']) ? $_POST['reg_captcha'] : ''));
			else
				$captcha	= '';

			// Some basic checks...

			$errors		= array();

			if ($username=='')
				$errors[]	= 'You have\'t entered an username.';
			else
			{

				// Validate AccountName -> check inc.validate.php for more details

				if (!validAccountname($username))
					$errors[]	= 'The username you entered is invalid. Please follow the rules listed under the form.';
				else
				{
					if (checkDuplicateAccountName($username))
						$errors[]	= 'The username you entered is already taken.';
					else
					{
						// There was a database-error while checking the email!
						// $error will stay "true" and taken into account a little bit later!
					}
				}

			}

			if ($password=='')
				$errors[]	= 'You have\'t entered a password.';
			else
			{

				// Validate Password -> check inc.validate.php for more details

				if (!validPassword($password))
					$errors[]	= 'The password you entered is invalid. Please follow the rules listed under the form.';

			}

			if ($password2=='')
				$errors[]	= 'You have\'t entered the password-repetition.';
			else
			{

				// Compare PW1+PW2

				if ($password != $password2)
					$errors[]	= 'The two passwords you entered do not match.';

			}

			if ($email=='')
				$errors[]	= 'You have\'t entered an email-address.';
			else
			{

				if (!validEMail($email))
					$errors[]	= 'You have entered an invalid email-address.';
				else
				{

					// check for duplicate emails if they aren't allowed

					if (!$config['allow_dupe_email'])
					{

						$result		= checkDuplicateEMail($email);

						if ($result)
							$errors[]	= 'The email-address you entered is already in use.';
						else
						{
							// There was a database-error while checking the email!
							// $error will stay "true" and taken into account a little bit later!
						}

					}

				}

			}

			if ($config['use_captcha'])
			{

				if ($captcha=='')
					$errors[]	= 'You have\'t entered the captcha-code.';
				else
				{

					// OK, lets see if the entered CAPTCHA is correct

					require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'securimage'.DIRECTORY_SEPARATOR.'securimage.php');

					$oCaptcha	= new Securimage();

					if ($oCaptcha->check($captcha) == false)
						$errors[]	= 'The captcha-code you entered was not correct.';

					unset($oCaptcha);

				}

			}

			// Assign values we need to show old data in form

			$oSmarty->assign('form_username',		htmlspecialchars($username));
			$oSmarty->assign('form_email',			htmlspecialchars($email));

			// OK, lets do whatever needs to be done...

			if ((count($errors)>0) || $error)
			{

				// There was at least 1 error :-( So nothing happens - we display the form again and
				// show a list of all errors.

				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> $errors	) );

			}
			else
			{

				// No errors found :o)
				//
				// Lets create this account...

				if ($config['use_activation'])
				{

					// If the site uses the activation-system we only create an entry in "account_pending"
					// instead of "account_login" !

					$acode		= createPendingAccount($username, $password, $email);

					if ($acode != '')
					{

						$reg_ok		= true;

						// Send eMail to player

						$oSmarty->assign('code',	$acode);
						$oSmarty->assign('log_ip',	$_SERVER['REMOTE_ADDR']);
						$oSmarty->assign('accountname',	$username);

						$mail		= $oSmarty->fetch('file:others/mail_registration.tpl');

						$reg_ok		= sendmail($email,$config['servername'].' - Account Registration',$mail);

						// If the email could not be sent, we'll delete the pending account!!! 
						
						if ($reg_ok === false)
							deletePendingAccount($username);

					}
					else
					{
					}

				}
				else
				{

					// Instant-Registration -> "account_login" is used

					if (createAccount($username, $password, $email))
					{
						$reg_ok		= true;
					}
					else
					{
						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array( 'There was an error creating your account.',
												       				  'Please try again later.' ) ) );
					}

				}

			}

		}

	}
	else
	{
		$oSmarty->assign('message',			array( 'type'		=> 'error',
								       'messages'	=> array( 'Registration is currently disabled' ) ) );
	}

	// Display Template

  	if (!$error)
  	{

  		$oSmarty->assign('show_mail_info',	$config['use_activation']);

		if ($reg_ok)
			$oSmarty->display('file:pages/account/site_register_ok.tpl');
		else
			$oSmarty->display('file:pages/account/site_register.tpl');

	}

?>
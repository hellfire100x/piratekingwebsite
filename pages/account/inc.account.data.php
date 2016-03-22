<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.account.data.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3       : moved all data-related functions from inc.account.php to this file
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	/**
	 * Change password
	 *
	 * @return bool
	 */

	function account_password_change()
	{

		global $config, $oSmarty, $templatefile;

		$current	= trim((isset($_POST['pw_current']) ? $_POST['pw_current'] : ''));
		$password	= trim((isset($_POST['pw_password']) ? $_POST['pw_password'] : ''));
		$password2	= trim((isset($_POST['pw_password2']) ? $_POST['pw_password2'] : ''));
		$captcha	= trim((isset($_POST['pw_captcha']) ? $_POST['pw_captcha'] : ''));

		//

		$errors		= array();

		// There is no validPassword()-check on $current to allow users with old passwords to still change it.

		if ($current=='')
			$errors[]	= 'You have\'t entered your <b>current</b> password.';
		else
		{

			if (strtoupper(md5($current)) != $_SESSION['pass'])
				$errors[]	= 'The <b>current</b> password you entered is wrong.';

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

		// OK, lets do whatever needs to be done...

		if (count($errors)>0)
		{

			// There was at least 1 error :-( So nothing happens - we display the form again and
			// show a list of all errors.

			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> $errors	) );

		}
		else
		{

			// change PW

			$sql		= 'UPDATE '.TABLE_ACCOUNT_LOGIN.' SET password = \''.strtoupper(md5($password)).'\', originalPassword = \''.addslashes_mssql($password).'\' WHERE id = '.(int)$_SESSION['aid'];
			$query		= doQuery($sql, DATABASE_ACCOUNT);

			if ($query !== false)
			{

				// Everything OK :o)

				logoutUser();			// THIS MUST STAY! (or you need to overwrite $_SESSION['pass'] with the new PW!

				$templatefile	= 'site_changepw_ok.tpl';

			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> 'There was a database-erros while changing the password.',
										       			   'Error: '.htmlspecialchars(mssql_get_last_message())		) );
			}

		}

	}

	/**
	 * change eMail-address
	 *
	 * @return bool
	 */

	function account_mail_change()
	{

		global $config, $oSmarty, $templatefile;

		$email		= trim((isset($_POST['email']) ? $_POST['email'] : ''));
		$captcha	= trim((isset($_POST['captcha']) ? $_POST['captcha'] : ''));

		//

		$errors		= array();

		// There is no validPassword()-check on $current to allow users with old passwords to still change it.

		if ($email=='')
			$errors[]	= 'You have\'t entered a new eMail-address.';
		else
		{
			if (!validEMail($email))
				$errors[]	= 'Invalid eMail-address.';

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

		// OK, lets do whatever needs to be done...

		if (count($errors)>0)
		{

			// fill form-var

			$oSmarty->assign('form_email',			htmlspecialchars($email));

			// There was at least 1 error :-( So nothing happens - we display the form again and
			// show a list of all errors.

			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> $errors	) );

		}
		else
		{

			// change PW

			$sql		= 'UPDATE '.TABLE_ACCOUNT_LOGIN.' SET email = \''.addslashes_mssql($email).'\' WHERE id = '.(int)$_SESSION['aid'];
			$query		= doQuery($sql, DATABASE_ACCOUNT);

			if ($query !== false)
			{

				// Everything OK :o)

				$oSmarty->assign('message',			array( 'type'		=> 'ok',
										       'messages'	=> array( 'Your eMail-address was successfully changed.',
										       				  'You can either change your address again or click <a href="'.site_url('account').'">here</a> to return to your account-page.'	) ) );

			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> 'There was a database-erros while changing the email-address.',
										       			   'Error: '.htmlspecialchars(mssql_get_last_message())		) );
			}

		}

	}

?>
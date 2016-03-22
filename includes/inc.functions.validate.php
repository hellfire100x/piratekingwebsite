<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.functions.validate.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.2       : cleanup code, etc.
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	/*
	 * Check if the passed AccountName is valid. Unlike PKO/TOP, pkoSite allows players to use
	 * all those chars available on the virtual keyboard in-game. So they can use more than
	 * just A-Z, 0-9 and _ for the name and password.
	 *
	 * @param string $name Accountname
	 * @return boolean Returns true if the Accountname is valid
	 */

	function validAccountname($name)
	{

		// 5-20 chars long

		if ((strlen($name)<5) || (strlen($name)>20))
			return false;
		else
		{

			// only those chars you see on the virtual keyboard in the login-screen

			$allowed	= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~!@#$%^&*()_+|-=\{}[]:";<>?,./';

			$ok		= true;

			for ($i=0;$i<strlen($name);$i++)
			{
				if (strpos($allowed,$name[$i])===false)
					$ok	= false;
			}

			return $ok;

		}

	}

	/*
	 * Check if the passed password is valid. Unlike PKO/TOP, pkoSite allows players to use
	 * all those chars available on the virtual keyboard in-game. So they can use more than
	 * just A-Z, 0-9 and _ for the name and password.
	 *
	 * @param string $password Password
	 * @return boolean Returns true if the password is valid
	 */

	function validPassword($password)
	{

		// 5-20 chars long

		if ((strlen($password)<5) || (strlen($password)>20))
			return false;
		else
		{

			// only those chars you see on the virtual keyboard in the login-screen

			$allowed	= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~!@#$%^&*()_+|-=\{}[]:";<>?,./';

			$ok		= true;

			for ($i=0;$i<strlen($password);$i++)
			{
				if (strpos($allowed,$password[$i])===false)
					$ok	= false;
			}

			return $ok;

		}

	}

	/*
	 * Simple check if the passed string is a valid email-address.
	 *
	 * @param string $email EMail-address to check
	 * @return boolean Returns true if the email-address is valid
	 */

	function validEMail($email)
	{
		if (strlen($email)>50)
			return false;
		else
			return (preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $email) == 1);
	}

?>
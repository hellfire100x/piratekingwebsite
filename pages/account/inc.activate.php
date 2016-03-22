<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.activate.php
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

	if (!function_exists('checkDuplicateAccountName'))
		require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.account.php');

	// ===================================================================================================

	$act_ok		= false;

	if ($config['use_activation'])
	{

		// Get code from URL

		if (!isset($_GET['code']))
			$code		= '';
		else
			$code		= trim(strtoupper($_GET['code']));

		$code		= strtolower(trim(preg_replace('/[^0-9a-z]/i','',$code)));

		// Check code

		if ($code == '')
		{
		}
		else
		{
			//

			$query		= @doQuery('SELECT COUNT(*) AS rcount FROM '.TABLE_ACCOUNT_PENDING.' WHERE UPPER(actcode) = \''.addslashes_mssql($code).'\'', DATABASE_ACCOUNT);

			if ($query !== false)
			{

				$row			= @mssql_fetch_assoc($query);

				if ((int)$row['rcount']==1)
				{

					// activate account

					if (createAccountFromPending($code) === true)
					{
						$act_ok		= false;
					}
					else
					{
						// account activated
						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array( 'There was an error activating your account.',
												       				  'Error: '.htmlspecialchars(mssql_get_last_message()) ) ) );
					}

				}
				else
				{
					if ((int)$row['rcount']==0)
					{
						// account activated
						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array( 'There was an error activating your account.',
												       				  'Activation-Code not found.' ) ) );
					}
					else
					{
						// account activated
						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array( 'There was an error activating your account.',
												       				  'Duplicate Activation-Code.' ) ) );
					}
				}

			}
			else
			{
				// account activated
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array( 'There was an error activating your account.',
										       				  'Error: '.htmlspecialchars(mssql_get_last_message()) ) ) );
			}

		}

	}
	else
	{
		$oSmarty->assign('message',			array( 'type'		=> 'error',
								       'messages'	=> array( 'This site doesn\'t use the activation-system.' ) ) );
	}

	$oSmarty->assign('activation_ok',		$act_ok);

  	if (!$error)
		$oSmarty->display('file:pages/account/site_activation_ok.tpl');

?>
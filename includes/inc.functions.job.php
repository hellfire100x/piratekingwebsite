<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.functions.jobs.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// Description  : This module contains some jobs that are performed periodically.
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.2       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	/**
	 * Delete all old entries in the account_pending table.
	 *
	 * @return void
	 */

	function jobAccountPending()
	{

		global $config;

		if ($config['delete_pending'] > 0)
		{

			$threshold	= time()-($config['delete_pending'] * 86400);

			$sql		= 'DELETE FROM '.TABLE_ACCOUNT_PENDING.' WHERE create_time <= CONVERT(datetime, \''.date('Y-m-d H:i:s',$threshold).'\', 120)';

		        $query		= doQuery($sql,DATABASE_ACCOUNT);

		}

	}

	/**
	 * Ban all accounts which are not banned but share the same MAC as an already banned account
	 *
	 * @return void
	 */

	function jobAutoban()
	{

		global $config;

		if ($config['cron_autoban'])
		{

			$sql		= 'UPDATE '.TABLE_ACCOUNT_LOGIN.' SET ban = 1 WHERE ISNULL(ban,0) = 0 AND last_login_mac IN (SELECT last_login_mac FROM '.TABLE_ACCOUNT_LOGIN.' WHERE ISNULL(ban,0) > 0)';

		        $query		= doQuery($sql,DATABASE_ACCOUNT);

		}

	}

?>
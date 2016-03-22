<?php

	// ===================================================================================================
	// Package      : pkoSite 
	// File         : inc.admin.misc.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3       : moved some misc functions from inc.admin.php to this file
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================
	
	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	/**
	 * Manage Server-Rules
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_rules()
	{

		global $oSmarty, $templatefile;

		if (isset($_POST['sact']))
		{

			$result		= @file_put_contents('.'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'rules.dat', $_POST['rules']);

			if ($result === false)
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> 'The rules-file could not be saved.'	) );
			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'ok',
										       'messages'	=> array( 'The rules have successfully been saved.',
										       				  'Continue editing it or click <a href="'.site_url('admin').'">here</a> to return to your admin-dashboard.' ) ) );

				$oSmarty->assign('rules',	htmlspecialchars($_POST['rules']));
			}

		}
		else
		{

			if (file_exists(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rules.dat'))
			{
				$rules		= @file(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rules.dat');
				$rules		= implode('',$rules);
			}
			else
				$rules		= '';

			$oSmarty->assign('rules',	htmlspecialchars($rules));

		}

		$templatefile	= 'site_admin_rules.tpl';

	}

	/**
	 * Show Admin-Dashboard
	 *
	 * @return mixed
	 */

	function admin_dashboard()
	{

		global $oSmarty;

		$result		= array();

		// The following code-blocks query the databases for certain stats

		$topguilds	= @doQuery('SELECT COUNT(DISTINCT (last_login_mac)) AS mac_count FROM '.TABLE_ACCOUNT_LOGIN, DATABASE_ACCOUNT);

		if ($topguilds !== false)
		{
			$row			= @mssql_fetch_assoc($topguilds);
			$result['unique_mac']	= $row['mac_count'];
		}
		else
			$result['unique_mac']	= 0;

		$topguilds	= @doQuery('SELECT COUNT(*) AS acc_count FROM '.TABLE_ACCOUNT_LOGIN, DATABASE_ACCOUNT);

		if ($topguilds !== false)
		{
			$row			= @mssql_fetch_assoc($topguilds);
			$result['acc_count']	= $row['acc_count'];
		}
		else
			$result['acc_count']	= 0;

		$topguilds	= @doQuery('SELECT COUNT(*) AS new_acc_count FROM '.TABLE_ACCOUNT_DETAILS.' WHERE (create_time >= \''.date('Ymd H:i:s',(time()-86400)).'\')', DATABASE_ACCOUNT);

		if ($topguilds !== false)
		{
			$row			= @mssql_fetch_assoc($topguilds);
			$result['new_accounts']	= $row['new_acc_count'];
		}
		else
			$result['new_accounts']	= 0;

		// Game-DB

		$accs		= @doQuery('SELECT TOP 1 * FROM '.TABLE_STAT.' ORDER BY track_date DESC', DATABASE_GAME);

		if ($accs !== false)
		{
			$row				= @mssql_fetch_assoc($accs);
			$result['login']		= (((int)$row['login_num'] > 0) ? (int)$row['login_num'] : 0);
			$result['play']			= (((int)$row['play_num'] > 0) ? (int)$row['play_num'] : 0);
		}
		else
		{
			$result['login']		= 0;
			$result['play']			= 0;
		}

		$topguilds	= @doQuery('SELECT COUNT(*) AS new_count FROM '.TABLE_CHARACTER.' AS c LEFT JOIN '.TABLE_ACCOUNT.' AS a ON (a.act_id = c.act_id) WHERE (c.delflag = 0) AND (a.gm = 0) AND (c.operdate >= \''.date('Ymd H:i:s',(time()-86400)).'\')', DATABASE_GAME);

		if ($topguilds !== false)
		{
			$row			= @mssql_fetch_assoc($topguilds);
			$result['new_players']	= (int)$row['new_count'];
		}
		else
			$result['new_players']	= 0;

		$topguilds	= @doQuery('SELECT COUNT(*) AS ply_count FROM '.TABLE_CHARACTER.' AS c LEFT JOIN '.TABLE_ACCOUNT.' AS a ON (a.act_id = c.act_id) WHERE (c.delflag = 0) AND (a.gm = 0)', DATABASE_GAME);

		if ($topguilds !== false)
		{
			$row			= @mssql_fetch_assoc($topguilds);
			$result['players']	= $row['ply_count'];
		}
		else
			$result['players']	= 0;

		$oSmarty->assign('admstats',		$result);

	}

?>
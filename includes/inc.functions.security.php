<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.functions.security.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	//                modified isIPBanned() to use BASEDIR for file()
	// v0.0.3       : added inIPRange() function, modified isIPBanned() to support IP-ranges
	// v0.0.2       : added basic function-headers to all functions, added isIPBanned()
	// v0.0.1c	: changed loginUser to use either ID or account-name to read data
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	/**
	 * Check if a user is logged in.
	 *
	 * @return bool
	 */

	function isLoggedIn()
	{
		if ((isset($_SESSION['aid'])) && ((int)$_SESSION['aid'] > 0))
			return true;
		else
			return false;
	}

	/**
	 * Check if a user is an admin (GM=99)
	 *
	 * @return bool
	 */

	function isAdmin()
	{

		global $config;

		if ((isLoggedIn()) && ((int)$_SESSION['level'] == 99))
		{

			if (count($config['gm_chars']) > 0)
			{
				if (in_array((int)$_SESSION['aid'],$config['gm_chars']))
					return true;
			}
			else
				return true;

		}
		else
			return false;

	}

	/**
	 * Check if a user is GM (any level).
	 *
	 * @return bool
	 */

	function isGM()
	{
		if ((isLoggedIn()) && ((int)$_SESSION['level'] > 0))
			return true;
		else
			return false;
	}

	/**
	 * Check if a user is banned.
	 *
	 * @return bool
	 */

	function isBanned()
	{
		if (isset($_SESSION['ban']))
			return ((int)$_SESSION['ban'] <> 0);
		else
			return false;
	}

	/**
	 * Try to login as a certain user.
	 *
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */

	function loginUser($username, $password)
	{

		global $config;

		// prepare vars for use in query

		$pass	= trim(strtoupper(md5($password)));
		$user	= addslashes_mssql($username);

		// query database for base-account-data

		$query	= doQuery('SELECT * FROM '.TABLE_ACCOUNT_LOGIN.' WHERE ((name = \''.$user.'\') AND (password = \''.$pass.'\'))', DATABASE_ACCOUNT);

		if ($query !== false)
		{

			if (mssql_num_rows($query) == 1)
			{

				$row 			= mssql_fetch_assoc($query);

				$_SESSION['aid']	= (int)$row['id'];
				$_SESSION['ban']	= (int)$row['ban'];
				$_SESSION['email']	= (isset($row['email']) ? $row['email'] : '');
				$_SESSION['pass']	= trim(strtoupper($row['password']));
				$_SESSION['name']	= $row['name'];

				// switch to GameDB to retrieve account-level

				if ($config['max_compatibility_mode'])
					$query	= doQuery('SELECT * FROM '.TABLE_ACCOUNT.' WHERE act_name = \''.addslashes_mssql($row['name']).'\'', DATABASE_GAME);
				else
					$query	= doQuery('SELECT * FROM '.TABLE_ACCOUNT.' WHERE act_id = '.(int)$row['id'], DATABASE_GAME);

				if ($query !== false)
				{

					$row 			= mssql_fetch_assoc($query);

					$_SESSION['level']	= (int)$row['gm'];

				}
				else
					$_SESSION['level']	= 0;

				return true;

			}

		}
		else
		{
			$_SESSION['aid']	= 0;
			$_SESSION['ban']	= 0;
			$_SESSION['email']	= '';
			$_SESSION['pass']	= '';
			$_SESSION['name']	= '';
			$_SESSION['level']	= 0;
		}

		return false;

	}

	/**
	 * Try to log the current user out.
	 *
	 * @return void
	 */

	function logoutUser()
	{
		session_unset();
		$_SESSION	= array();
	}

	/**
	 * Basic referer-check to "protect" some pages from being called from the outside.
	 *
	 * @return bool
	 */

	function checkReferer()
	{

		if (strtolower(substr($_SERVER['HTTP_HOST'],0,4))!=='http')
			$host		= parse_url('http://'.$_SERVER['HTTP_HOST']);
		else
			$host		= parse_url($_SERVER['HTTP_HOST']);

		if ((isset($_SERVER['HTTP_REFERER'])) && (trim($_SERVER['HTTP_REFERER'])!=''))
		{

			if (strtolower(substr($_SERVER['HTTP_REFERER'],0,4))!=='http')
				$referer	= parse_url('http://'.$_SERVER['HTTP_REFERER']);
			else
				$referer	= parse_url($_SERVER['HTTP_REFERER']);

			$rdomain	= strtolower((isset($referer['host']) ? $referer['host'] : ''));
			$hdomain	= strtolower((isset($host['host']) ? $host['host'] : ''));

			if (strlen($rdomain)>=strlen($hdomain))
			{
				if (substr($hdomain,-strlen($rdomain))==$hdomain)
					return true;
			}

		}


		return false;

	}

	/**
	 * Check if the user is in our IP-banlist.
	 *
	 * @return bool
	 */

	function isIPBanned()
	{

		if (file_exists(BASEDIR.'data'.DIRECTORY_SEPARATOR.'ban.dat'))
		{

			$ips	= @file(BASEDIR.'data'.DIRECTORY_SEPARATOR.'ban.dat');

			if ($ips !== false)
			{

				$parts		= explode('.',$_SERVER['REMOTE_ADDR']);

				if (count($parts)==4)
				{

					$client		= (int)(ltrim($parts[0],'0')).'.'.(int)(ltrim($parts[1],'0')).'.'.(int)(ltrim($parts[2],'0')).'.'.(int)(ltrim($parts[3],'0'));

					foreach ($ips as $curip)
					{

						$curip		= trim($curip);

						if ((substr($curip,0,2)!=='//') && ($curip != ''))
						{
							$parts		= explode('.',$curip);
							if (count($parts)==4)
							{
								return inIPRange($client,$curip);
							}
						}

					}

				}

			}

		}

		return false;

	}

	/**
	 * Very simple function to check if a given IP belongs to a certain IP-range.
	 *
	 * @param $ip
	 * @param $range
	 * @return bool
	 */

	function inIPRange($ip,$range)
	{

		// prepare everything -> basically this rewrites the ranges. For example 1.[2-5].3.* would become 1-1.2-5.3-3.0-255
		// that format makes it easier to check the ip :-)

		$range		= str_replace(' ','',$range);
		$range		= str_replace('[','',$range);
		$range		= str_replace(']','',$range);
		$range		= str_replace('*','0-255',$range);

		$ratoms		= explode('.',$range);

		foreach ($ratoms as $key=>$value)
		{
			if (strpos($value,'-')===false)
				$ratoms[$key]	= $value.'-'.$value;
		}

		$iatoms		= explode('.',$ip);

		// Check...

		$matches	= 0;

		for ($i=0;$i<=3;$i++)
		{
			list($from,$to)		= split('-',$ratoms[$i]);
			if (((int)(ltrim($iatoms[$i],'0')) >= $from) && ((int)(ltrim($iatoms[$i],'0')) <= $to))
				$matches++;
		}

		return ($matches == 4);

	}

?>
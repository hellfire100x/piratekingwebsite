<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.functions.account.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// Description  : This file contains all functions related to account-creation.
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3b      : some minor changes regarding filenames, added deletePendingAccount()
	// v0.0.3       : fixed bug in createAccount() (thx to insider)
	// v0.0.2       : added basic function-headers to all functions, switched code to use TABLE_*
	//		  constants instead of table-names and DATABASE_ constants for selectDB(),
	//		  rewrote some SQL-queries to always return all dates as UNIX timestamps,
	//		  fixed a bug n0aX found in createAccountFromPending() which should fix
	//		  several bugs (Invalid object name 'account_pending', The statement has been
	//		  terminated)
	// v0.0.1c      : changed createAccount() to create an entry in the "account"-table in the GameDB!
	//		  added support for GM-level for GM-Servers
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// Load the MSSQL-functions if needed

	if (!function_exists('selectDB'))
		require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.db.php');

	/**
	 * Creates a record in the account_login table
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $email
	 * @return string
	 */

	function createAccount($username, $password, $email)
	{

		global $error,$oSmarty,$config;

		// define all DB-fields we need

		$data			= array();

		$data[]			= array( 'field'	=> 'name',
						 'value'	=> $username,
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'password',
						 'value'	=> strtoupper(md5($password)),
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'originalpassword',
						 'value'	=> $password,
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'sid',
						 'value'	=> 0,
						 'type'		=> 'i'		);

		$data[]			= array( 'field'	=> 'login_status',
						 'value'	=> 0,
						 'type'		=> 'i'		);

		$data[]			= array( 'field'	=> 'enable_login_tick',
						 'value'	=> 0,
						 'type'		=> 'i'		);

		$data[]			= array( 'field'	=> 'login_group',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'last_login_time',
						 'value'	=> time(),
						 'type'		=> 'd'		);

		$data[]			= array( 'field'	=> 'last_logout_time',
						 'value'	=> time(),
						 'type'		=> 'd'		);

		$data[]			= array( 'field'	=> 'last_login_ip',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'enable_login_time',
						 'value'	=> time(),
						 'type'		=> 'd'		);

		$data[]			= array( 'field'	=> 'total_live_time',
						 'value'	=> 0,
						 'type'		=> 'i'		);

		$data[]			= array( 'field'	=> 'last_login_mac',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'ban',
						 'value'	=> 0,
						 'type'		=> 'i'		);

		$data[]			= array( 'field'	=> 'email',
						 'value'	=> $email,
						 'type'		=> 's'		);

		// Insert data into AccountServer-database

		$sql		= getInsert(TABLE_ACCOUNT_LOGIN, $data);
	        $query		= doQuery($sql, DATABASE_ACCOUNT);

	        if ($query !== false)
	        {

			// OK, we have to check what account-id this entry got

			if ($config['max_compatibility_mode'])
			{
				// in this mode, we'll just get the id from account_login by name and use it to insert into account
		        	$query		= doQuery('SELECT id FROM '.TABLE_ACCOUNT_LOGIN.' WHERE name = \''.addslashes_mssql($username).'\'',DATABASE_ACCOUNT);
		        }
			else
			{
				// in this mode, we'll just get the max id from account and add 1 to it
		        	$query		= doQuery('SELECT (MAX(act_id) + 1) AS id FROM '.TABLE_ACCOUNT,DATABASE_GAME);
			}

		        if ($query !== false)
		        {

				$row	= mssql_fetch_assoc($query);

				$id	= (int)$row['id'];

				// Create account-entry in GameDB

				// First, lets decide what GM-Level the new user gets.
				//
				//     0 -> regular player
				//  1-20 -> move, call, goto, hide, kick, unhide will work, nothing else
				// 21-30 -> same permissions as 1 but "HD" is displayed above players head instead of "GM"
				// 31-98 -> move, call, goto, hide, kick, unhide will work, nothing else
				//    99 -> super-gm who can do everything he wannts to do

				if ($config['is_gm_server'])
				{
					$gm	= (int)$config['new_gm_level'];
					$gm	= (($gm < 0) ? 0 : (($gm > 99) ? 99 : $gm));
				}
				else
					$gm	= 0;

				// Now we create the new record

				if ($id > 0)
				{

				        $query		= doQuery('INSERT INTO account (act_id, act_name, gm) VALUES ('.$id.',\''.addslashes_mssql($username).'\','.$gm.')',DATABASE_GAME);

					if ($query !== false)
						return true;

				}

		        }

	        }
	        else
	        {
			$error	= true;
	                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
	        }

		return false;

	}

	/**
	 * Creates a record in the account_pending table
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $email
	 * @return string
	 */

	function createPendingAccount($username, $password, $email)
	{

		global $error,$oSmarty;

		// create activation code

		$activation		= strtoupper(sha1(microtime()));

		// define all DB-fields we need

		$data			= array();

		$data[]			= array( 'field'	=> 'name',
						 'value'	=> $username,
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'password',
						 'value'	=> $password,
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'squestion',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'answer',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'email',
						 'value'	=> $email,
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'gender',
						 'value'	=> 0,
						 'type'		=> 'i'		);

		$data[]			= array( 'field'	=> 'credit',
						 'value'	=> 0,
						 'type'		=> 'i'		);

		$data[]			= array( 'field'	=> 'truename',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'birthday',
						 'value'	=> 0,
						 'type'		=> 'i'		);

		$data[]			= array( 'field'	=> 'contact',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'country',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'ipaddr',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'ip2country',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'actcode',
						 'value'	=> $activation,
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'invtcode',
						 'value'	=> '',
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'create_time',
						 'value'	=> time(),
						 'type'		=> 'd'		);

		// Insert data into database

		$sql		= getInsert(TABLE_ACCOUNT_PENDING,$data);
	        $query		= doQuery($sql,DATABASE_ACCOUNT);

	        if ($query !== false)
	        {
			return $activation;
	        }
	        else
	        {
			$error	= true;
	                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
	        }

		return '';

	}

	/**
	 * Creates a record in the account_login table from an record in the account_pending table
	 *
	 * @param string $code
	 * @return bool
	 */

	function createAccountFromPending($code)
	{

		// prepare code

		$code	= trim(strtoupper($code));

		// Check account_pending for record

		$sql	= 'SELECT * FROM '.TABLE_ACCOUNT_PENDING.' WHERE UPPER(actcode) = \''.addslashes_mssql($code).'\'';
		$query	= doQuery($sql,DATABASE_ACCOUNT);

		if ($query !== false)
		{

			$row	= mssql_fetch_assoc($query);

			// Create Account in account-table of GameDB (DB-context switches!)

			$result	= createAccount($row['name'], $row['password'], $row['email']);

			$sql	= 'DELETE FROM '.TABLE_ACCOUNT_PENDING.' WHERE UPPER(actcode) = \''.addslashes_mssql($code).'\'';

			$query	= doQuery($sql,DATABASE_ACCOUNT);

			return $query;

		}

		return false;

	}

	/**
	 * Checks if an email is already used.
	 *
	 * @param string $email
	 * @return bool
	 */

	function checkDuplicateEMail($email)
	{

		global $oSmarty, $error;

		// prepare eMail

		$email		= trim(strtoupper($email));

		// Query DB

		$sql	= 'SELECT * FROM '.TABLE_ACCOUNT_LOGIN.' WHERE UPPER(email) = \''.addslashes_mssql($email).'\'';
		$query	= doQuery($sql,DATABASE_ACCOUNT);

		if ($query !== false)
		{

			if (mssql_num_rows($query) > 0)
				return true;
			else
			{

				// Check account_pending, too

				$sql	= 'SELECT * FROM '.TABLE_ACCOUNT_PENDING.' WHERE UPPER(email) = \''.addslashes_mssql($email).'\'';
				$query	= doQuery($sql,DATABASE_ACCOUNT);

				if ($query !== false)
				{

					if (mssql_num_rows($query) > 0)
						return true;

				}
				else
				{
					$error	= true;
			                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
				}

			}

		}
		else
		{
			$error	= true;
	                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
		}

		return false;

	}

	/**
	 * Checks if an accountname is already taken.
	 *
	 * @param string $name
	 * @return bool
	 */

	function checkDuplicateAccountName($name)
	{

		global $oSmarty, $error;

		//

		$name	= strtoupper(trim($name));

		//

		$sql	= 'SELECT COUNT(*) AS rcount FROM '.TABLE_ACCOUNT_LOGIN.' WHERE UPPER(name) = \''.addslashes_mssql($name).'\'';
		$query	= doQuery($sql,DATABASE_ACCOUNT);

		if ($query !== false)
		{

			$row	= mssql_fetch_assoc($query);

			if ((int)$row['rcount'] > 0)
				return true;
			else
			{

				// Check account_pending, too

				$sql	= 'SELECT COUNT(*) AS rcount FROM '.TABLE_ACCOUNT_PENDING.' WHERE UPPER(name) = \''.addslashes_mssql($name).'\'';
				$query	= doQuery($sql,DATABASE_ACCOUNT);

				if ($query !== false)
				{

					$row	= mssql_fetch_assoc($query);

					if ((int)$row['rcount'] > 0)
						return true;

				}
				else
				{
					$error	= true;
			                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
				}

			}


		}
		else
		{
			$error	= true;
	                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
		}

		return false;

	}

	/**
	 * Loads the account-data from the account_login table by the given account-id.
	 *
	 * @param string $aid
	 * @return mixed
	 */

	function loadAccount($aid)
	{

		// Check account_pending, too

		$sql	= '
			SELECT
				id, name, password, originalpassword, sid, login_status,
				enable_login_tick, login_group, DATEDIFF(s,\'1970-01-01 00:00:00\', last_login_time) AS last_login_time,
				DATEDIFF(s,\'1970-01-01 00:00:00\', last_logout_time) AS last_logout_time, last_login_ip,
				DATEDIFF(s,\'1970-01-01 00:00:00\', enable_login_time) AS enable_login_time, total_live_time, last_login_mac,
				ISNULL(ban,0) AS ban, ISNULL(email,\'\') AS email
			FROM
				'.TABLE_ACCOUNT_LOGIN.'
			WHERE
				id = '.(int)$aid;

		$query	= doQuery($sql,DATABASE_ACCOUNT);

		if ($query !== false)
		{
			$row	= mssql_fetch_assoc($query);
			return $row;
		}

		return false;

	}

	/**
	 * Loads the account-data from the account_login table by the given account-name.
	 *
	 * @param string $name
	 * @return mixed
	 */

	function loadAccountByName($name)
	{

		//

		$name	= trim(strtoupper($name));

		// Check account_pending, too

		$sql	= '
			SELECT
				id, name, password, originalpassword, sid, login_status,
				enable_login_tick, login_group, DATEDIFF(s,\'1970-01-01 00:00:00\', last_login_time) AS last_login_time,
				DATEDIFF(s,\'1970-01-01 00:00:00\', last_logout_time) AS last_logout_time, last_login_ip,
				DATEDIFF(s,\'1970-01-01 00:00:00\', enable_login_time) AS enable_login_time, total_live_time, last_login_mac,
				ISNULL(ban,0) AS ban, ISNULL(email,\'\') AS email
			FROM
				'.TABLE_ACCOUNT_LOGIN.'
			WHERE
				UPPER(name) = \''.addslashes_mssql($name).'\'';

		$query	= doQuery($sql,DATABASE_ACCOUNT);

		if (($query !== false) && (mssql_num_rows($query)==1))
		{
			$row	= mssql_fetch_assoc($query);
			return $row;
		}

		return false;

	}

	/**
	 * Creates an entry in the account_invt table (which we abuse for our password-reminder)
	 *
	 * @param string $name
	 * @return mixed
	 */

	function createReminderEntry($name)
	{

		$code			= strtoupper(sha1(microtime()));

		// define all DB-fields we need

		$data			= array();

		$data[]			= array( 'field'	=> 'invt_code',
						 'value'	=> $code,
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'assignto',
						 'value'	=> $name,
						 'type'		=> 's'		);

		$data[]			= array( 'field'	=> 'used',
						 'value'	=> time(),
						 'type'		=> 'i'		);

		// Insert data into database

		$sql		= getInsert(TABLE_ACCOUNT_INVT,$data);
	        $query		= doQuery($sql,DATABASE_ACCOUNT);

	        if ($query !== false)
	        {
			return $code;
	        }
	        else
	        {
			$error	= true;
	                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
	        }

		return '';

	}

	/**
	 * Loads an entry from account_invt by the given accountname
	 *
	 * @param string $name
	 * @return mixed
	 */

	function loadReminderByName($name)
	{

		// prepare name

		$name	= trim(strtoupper($name));

		// Check account_pending, too

		$sql	= 'SELECT * FROM '.TABLE_ACCOUNT_INVT.' WHERE UPPER(assignto) = \''.addslashes_mssql($name).'\'';
		$query	= doQuery($sql,DATABASE_ACCOUNT);

		if (($query !== false) && (mssql_num_rows($query)==1))
		{
			$row	= mssql_fetch_assoc($query);
			return $row;
		}

		return false;

	}

	/**
	 * Loads an entry from account_invt by the given reminder-code.
	 *
	 * @param string $name
	 * @return mixed
	 */

	function loadReminderByCode($code)
	{

		// prepare code

		$code	= trim(strtoupper($code));

		// Check account_pending, too

		$sql	= 'SELECT * FROM '.TABLE_ACCOUNT_INVT.' WHERE UPPER(invt_code) = \''.addslashes_mssql($code).'\'';
		$query	= doQuery($sql,DATABASE_ACCOUNT);

		if (($query !== false) && (mssql_num_rows($query)==1))
		{
			$row	= mssql_fetch_assoc($query);
			return $row;
		}

		return false;

	}

	/**
	 * Update the account-password in account_login for a given account.
	 *
	 * @param string $mode
	 * @param string $account
	 * @param string $password
	 * @return mixed
	 */

	function updatePassword($mode,$account,$password)
	{

		$mode		= trim(strtolower($mode));

		// build first part of the UPDATe-statement

		$sql	= '
			UPDATE
				'.TABLE_ACCOUNT_LOGIN.'
			SET
				password = \''.strtoupper(md5(trim($password))).'\',
				originalPassword = \''.addslashes_mssql(trim($password)).'\'
			WHERE
				';

		// and now the "where"

		if ($mode=='id')
		{
			$sql	= $sql.'id = '.(int)$account;
		}
		elseif ($mode=='name')
		{
			$sql	= $sql.'name = \''.addslashes_mssql($account).'\'';
		}
		else
			die('Oops.');

		// perform query

		$query	= doQuery($sql,DATABASE_ACCOUNT);

		// return result

		return $query;

	}

	/**
	 * Delete a record from the account_pending table
	 *
	 * @param string $username
	 * @return string
	 */

	function deletePendingAccount($username)
	{

		$sql		= 'DELETE FROM '.TABLE_ACCOUNT_PENDING.' WHERE name = \''.addslashes_mssql($username).'\'';
	        $query		= doQuery($sql,DATABASE_ACCOUNT);

	        return ($query === true);

	}

?>
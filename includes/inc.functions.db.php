<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.functions.db.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	//                modified doQuery() to use BASEDIR for file-operations
	// v0.0.3       : added debug-code and better error-handling to selectDB()
	// v0.0.2       : added basic function-headers to all functions, added $db-parameter do doQuery,
	//		  added logging to doQuery()
	// v0.0.1b      : changed error-handling on connect
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
	 * Switch the database-context to either Accountserver or GameDB.
	 *
	 * @param string $db
	 * @return string
	 */

	function selectDB($db)
	{

		global $connections,$config;

		if (!isset($connections[$db]))
			die('Core-Error: Invalid database in selectDB()');

		if ($connections[$db]===false)
		{

			$connections[$db]	= @mssql_connect($config['db'][$db]['host'],$config['db'][$db]['user'],$config['db'][$db]['pass']);

			if ($connections[$db] === false)
			{

				$msg		= mssql_get_last_message();

				echo '<b>Core-Error</b>: Failed to connect to database!<br />';

				if (trim($msg)!='')
					echo 'Error: '.htmlspecialchars($msg);
				else
				{

					if (trim(strtolower($config['db'][$db]['host'])) == '(local)')
						$config['db'][$db]['host']	= 'localhost';

					// Lets see if we can establish a connection to the db-server

					$file      = @fsockopen ($config['db'][$db]['host'], 80, $errno, $errstr, 10);

					if (!$file)
						$status = -1;  // Site is down
					else
					{
						$status	= 0;
						fclose($file);
					}

					if ($status == -1)
						echo 'Error: #'.$errno.', '.htmlspecialchars($errstr).'';
					else
						echo 'Error: Please check if MSSQL-service is running <b>and</b> reachable (firewall, etc.).';

				}

				if (DEBUG)
				{
					echo '<br /><br />';
					echo '<b>Connection-Details</b>:<br /><br />';
					echo '<table width="400">';
					echo '<tr><td>Host:</td><td>'.htmlspecialchars($config['db'][$db]['host']).'</td></tr>';
					echo '<tr><td>User:</td><td>'.htmlspecialchars($config['db'][$db]['user']).'</td></tr>';
					echo '<tr><td>Password:</td><td>'.htmlspecialchars($config['db'][$db]['pass']).'</td></tr>';
					echo '<tr><td>Database:</td><td>'.htmlspecialchars($config['db'][$db]['db']).'</td></tr>';
					echo '</table>';
				}

				die('');

			}

		}

		if ($connections[$db]!==false)
			mssql_select_db($config['db'][$db]['db']);

	}

	/**
	 * This is mostly a wrapper for mssql_query which logs queries.
	 *
	 * @param string $query
	 * @return mixed
	 */

	function doQuery($query,$db='')
	{

		global $stats;

		if (DEBUG)
		{
			$stats['queries'][]	= $query;
			$stats['querycount']	= $stats['querycount']+1;	// Number of queries
		}

		// change DB if neccessary

		if ($db!=='')
			selectDB($db);

		// perform query

		$result	= @mssql_query($query);

		if (DEBUG)
		{

			$fh	= @fopen(BASEDIR.'data'.DIRECTORY_SEPARATOR.'dblog.txt','a');
			@fputs($fh,$query."\n");

			if ($result !== true)
			{

				$error		= mssql_get_last_message();

				if ((stristr($error,'Changed database context') === false) && (stristr($error,'Datenbankkontext wurde auf') === false))
				{
					@fputs($fh,"\n".'ERROR: '.mssql_get_last_message()."\n");
				}

			}

			@fputs($fh,"\n\n".str_repeat('=',80)."\n\n\n");

			@fclose($fh);

		}

		//

		return $result;

	}

	/**
	 *  Very simple function to create an INSERT-statement from an array
	 *
	 * @param string $table
	 * @param string $data
	 * @return string
	 */

	function getInsert($table,$data)
	{

		$fields			= array();
		$values			= array();

		foreach ($data as $curdata)
		{

			$fields[]	= $curdata['field'];

			switch (strtolower($curdata['type']))
			{
				case 'i'	:
					$values[]	= (int)$curdata['value'];
					break;
				case 's'	:
					$values[]	= '\''.addslashes_mssql($curdata['value']).'\'';
					break;
				case 'd'	:
					$values[]	= 'CONVERT(datetime, \''.date('Y-m-d H:i:s',(int)$curdata['value']).'\', 120)';
					break;
			}

		}

		return 'INSERT INTO '.$table.' ('.implode(', ',$fields).') VALUES ('.implode(', ',$values).')';

	}

	/**
	 * A n addslashes-function for mssql as mssql uses different escape-chars.
	 *
	 * @param string $str
	 * @param string $inlike
	 * @param string $escape
	 * @return string
	 */

	function addslashes_mssql($str,$inlike=false,$escape='!')
	{

		if (is_array($str))
		{
			foreach($str AS $id => $value) {
				$str[$id] = addslashes_mssql($value,$inlike);
			}
		}
		else
		{

			$str	= str_replace("'", "''", $str);

			if ($inlike)
			{
				$str	= str_replace($escape, $escape.$escape, $str);
				$str	= str_replace('%', $escape.'%', $str);
				$str	= str_replace('[', $escape.'[', $str);
				$str	= str_replace(']', $escape.']', $str);
				$str	= str_replace('_', $escape.'_', $str);
			}

		}

		return $str;

	}

?>
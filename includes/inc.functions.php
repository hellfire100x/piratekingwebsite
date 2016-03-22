<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.functions.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	//                modified loadStats() and pseudoCron to use BASEDIR for file-operations
	// v0.0.3b      : some minor changes regarding filenames, some code-changes in sendmail to return
	//		  error-messages.
	// v0.0.3       : just a minor code-change in sendmail()
	// v0.0.2       : added basic function-headers to all functions, cleaned up some code, added
	//		  pseudoCron(), added better error-handling to sendmail(), remove "LEFT JOIN account"
	//		  from non-gm queries (not needed)
	// v0.0.1d      : added number of guilds to loadStats
	// v0.0.1c      : added job-distribution to loadStats
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
	 * Returns a translated job-name.
	 *
	 * Btw. it seems to be stupid to write an extra function which imports the vars from inc.config.php - but its
	 * done like this on purpose to keep nearly all config-stuff in inc-config.php and not spread the data over
	 * tons of files.
	 *
	 * @param string $job
	 * @return string
	 */

	function getJobName($job)
	{
		global $config;

		if (isset($config['jobs'][$job]))
			return $config['jobs'][$job];
		else
			return $job;
	}

	/**
	 * Returns the filename for the picture of a given char and job.
	 *
	 * @param string $char
	 * @param string $job
	 * @return string
	 */

	function getCharPicture($char,$job)
	{

		global $config;

		$fn	= '';
		$pre	= '';
		$post	= '';

		if (isset($config['chars'][$char]))
			$pre	= strtolower($config['chars'][$char]);

		if (isset($config['jobs'][$job]))
			$post	= strtolower($config['jobs'][$job]);
		else
		{
			if (in_array($job,$config['jobs']))
				$post	= strtolower($job);
		}

		if (!empty($pre) && !empty($post))
			$fn	= $pre.'_'.$post.'.gif';
		else
		{
			if (!empty($pre))
				$fn	= $pre.'_newbie.gif';
			else
				$fn	= 'unknown.gif';
		}

		return strtolower(str_replace(' ','_',$fn));

	}

	/**
	 * This little function queries the database for some statistics.
	 *
	 * @return mixed
	 */

	function loadStats()
	{

		global $config;

		$cached		= false;

		// Do we have cached stats?

		if (file_exists(BASEDIR.'data'.DIRECTORY_SEPARATOR.'stats.dat'))
		{

			// Yeah, lets see how hold they are

			clearstatcache();

			$dummy	= filemtime(BASEDIR.'data'.DIRECTORY_SEPARATOR.'stats.dat');

			if ((time()-$dummy) > $config['cache_stats'])						// 300s = 5 Minutes
				@unlink(BASEDIR.'data'.DIRECTORY_SEPARATOR.'stats.dat');
			else
			{
				$stats	= file_get_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'stats.dat');
				$stats	= unserialize($stats);
				$cached	= true;
			}

		}

		// Do what needs to be done ;-)

		if (!$cached)
		{

			// Load the MSSQL-functions if needed

			if (!function_exists('selectDB'))
				require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.db.php');

			// On GM-Servers all accounts and chars are counted, on non-gm servers the gm-chars&accounts are ignored

			if ($config['is_gm_server'])
			{
				$queries	= array( 'chars'	=> 'SELECT COUNT(*) AS rcount FROM character AS c WHERE (c.delflag = 0)',
							 'accounts'	=> 'SELECT COUNT(*) AS rcount  FROM account',
							 'jobs'		=> 'SELECT c.job, COUNT(*) AS rcount FROM character AS c WHERE (c.delflag = 0) GROUP BY c.job',
							 'guilds'	=> 'SELECT COUNT(*) AS rcount FROM guild AS g LEFT JOIN character AS c ON (c.cha_id = g.leader_id) WHERE (g.member_total > 0)' );
			}
			else
			{
				$queries	= array( 'chars'	=> 'SELECT COUNT(*) AS rcount  FROM character AS c LEFT JOIN account AS a ON (a.act_id = c.act_id) WHERE ((c.delflag = 0) AND (a.gm = 0))',
							 'accounts'	=> 'SELECT COUNT(*) AS rcount  FROM account WHERE gm = 0',
							 'jobs'		=> 'SELECT c.job, COUNT(*) AS rcount FROM character AS c LEFT JOIN account AS a ON (a.act_id = c.act_id) WHERE ((c.delflag = 0) AND (a.gm = 0)) GROUP BY c.job',
			        			 'guilds'	=> 'SELECT COUNT(*) AS rcount FROM guild AS g LEFT JOIN character AS c ON (c.cha_id = g.leader_id) LEFT JOIN account AS a ON (a.act_id = c.act_id) WHERE (g.member_total > 0) AND (a.gm = 0)' );

			}

			$queries['online']	= 'SELECT TOP 1 play_num AS rcount FROM '.TABLE_STAT.' ORDER BY track_date DESC';

			// Query data

			$result		= array();

			foreach ($queries as $key=>$query)
			{

			        $qresult	= doQuery($query, DATABASE_GAME);

			        if ($qresult !== false)
			        {

			        	$row		= mssql_fetch_assoc($qresult);

			        	if ($key=='jobs')
			        	{

			        		$result['jobs'][trim(strtolower(str_replace(' ','',getJobName($row['job']))))]	= (int)$row['rcount'];

				        	while ($row = mssql_fetch_assoc($qresult))
				        	{
				        		$result['jobs'][trim(strtolower(str_replace(' ','',getJobName($row['job']))))]	= (int)$row['rcount'];
				        	}

			        	}
			        	else
			        		$result[$key]	= (int)$row['rcount'];

				}

			}

			// Get Online-Record

			$sql		= 'SELECT TOP 1 CONVERT(varchar, track_date, 120) AS track_date, play_num FROM '.TABLE_STAT.' ORDER BY play_num DESC, track_date DESC';

		        $qresult	= doQuery($sql, DATABASE_GAME);

		        if ($qresult !== false)
		        {

		        	$row		= mssql_fetch_assoc($qresult);

				$result['record']	= array( 'timestamp'	=> strtotime($row['track_date']),
								 'online'	=> (int)$row['play_num']	);

			}
			else
			{
				// Fill with 0 so we can identify it in the template
				$result['record']	= array( 'timestamp'	=> 0,
								 'online'	=> 0		);
			}

			// cache data

			$stats			= $result;

			@file_put_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'stats.dat',serialize($stats));

		}

		return $stats;

	}

	/**
	 * Returns the charname for a given char-class.
	 *
	 * @param integer $char
	 * @return string
	 */

	function getCharName($char)
	{

		global $config;
		return ((isset($config['chars'][$char])) ? $config['chars'][$char] : 'Unknown');

	}

	/**
	 * Simple wrapper for the SwiftMailer-class and the mail()-function of PHP. Sends an email ;-)
	 *
	 * @param mixed $recipients
	 * @param string $subject
	 * @param string $message
	 * @return bool
	 */

	function sendmail($recipients, $subject, $message)
	{

		global $config,$oSmarty;

		switch ($config['mail']['mode'])
		{

			case 'smtp'	:

				// Load in the files we'll need

				require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'swift'.DIRECTORY_SEPARATOR.'Swift.php');
				require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'swift'.DIRECTORY_SEPARATOR.'Swift'.DIRECTORY_SEPARATOR.'Connection'.DIRECTORY_SEPARATOR.'SMTP.php');

				// Send mail

				try
				{

					// Define everything we need

					if (isset($config['mail']['use_ssl']) && ($config['mail']['use_ssl']==true))
						$oSMTP		=& new Swift_Connection_SMTP($config['mail']['server'], (int)$config['mail']['port'], Swift_Connection_SMTP::ENC_TLS);
					else
						$oSMTP		=& new Swift_Connection_SMTP($config['mail']['server'], (int)$config['mail']['port']);

					// Authentification

					if (isset($config['mail']['username']))
						$oSMTP->setUsername($config['mail']['username']);

					if (isset($config['mail']['password']))
						$oSMTP->setpassword($config['mail']['password']);

					// SwiftMailer-Object

					$oSwift		=& new Swift($oSMTP);

					// Create the message

					$oMessage	=& new Swift_Message($subject, $message);

					if (isset($config['mail']['email']))
						$oMessage->setFrom($config['mail']['email']);

					// Send mail
					$oSwift->send($oMessage, $recipients, $config['mail']['email']);

					// The next line is only executed if no exception is thrown
					return true;

				}
				catch (Swift_ConnectionException $e)
				{
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'There was a problem communicating with the SMTP-Server.',
											       				  'Error-Text: '.$e->getMessage() ) ) );
					return false;
				}
				catch (Swift_Message_MimeException $e)
				{
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'There was an unexpected problem building the email.',
											       				  'Error-Text: '.$e->getMessage() ) ) );
					return false;
				}

				break;

			case 'php'	:

				foreach ($recipients as $recipient)
				{

					$headers	= 'From: ' . $config['mail']['email'] . "\r\n" .
							  'Reply-To: ' . $config['mail']['email'] . "\r\n" .
							  'X-Mailer: PHP/' . phpversion();

    					$result		= mail($recipient, $subject , $message , $headers);

    					if (!$result)
					{
						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array( 'There was an unexpected problem sending the email.' ) ) );
						return false;
					}

				}

				return true;

				break;

		}

	}

	/**
	 * stripslashes()-replacement that can be used on arrays.
	 *
	 * @param mixed $value
	 * @return mixed
	 */

	function stripslashes_deep($value)
	{
		if(isset($value))
		{
			$value = is_array($value) ?
				array_map('stripslashes_deep', $value) :
				stripslashes($value);
		}
		return $value;
	}

	/**
	 * This is mostly here for later enhancements. This function returns the complete url for a given action.
	 *
	 * @param mixed $action
	 * @param mixed $parameters
	 * @return string
	 */

	function site_url($action='',$parameters=null)
	{

		global $config;

		$result		= $config['script_url'];

		if (trim($action)!=='')
			$result		.= '?act='.$action;

		if ((is_array($parameters)) && (count($parameters)>0))
		{
			foreach ($parameters as $key=>$value)
			{
				$result		.= '&'.$key.'='.$value;
			}
		}

		return $result;

	}

	/**
	 * This assigns some common variables to Smarty!
	 *
	 * This is done on purpose as I didn't want to expose the $config-Array to Smarty directly.
	 *
	 * @return void
	 */

	function assignSmartyVars()
	{

		global $oSmarty, $config;

		$oSmarty->assign('servername',			$config['servername']);
		$oSmarty->assign('slogan',			$config['slogan']);

		$oSmarty->assign('rates',			array( 'exp'		=> $config['rates']['exp'],
								       'pexp'		=> $config['rates']['pexp'],
								       'fairy'		=> $config['rates']['fairy'],
								       'drop'		=> $config['rates']['drop'],
								       'ship'		=> $config['rates']['ship']	));

		$oSmarty->assign('forum_url',			$config['forum']);

		$oSmarty->assign('allow_registration',		!$config['disable_registration']);

		$oSmarty->assign('webim_found',			$config['use_webim']);

		$oSmarty->assign('show_captcha',		$config['use_captcha']);

		$oSmarty->assign('allow_dupe_email',		$config['allow_dupe_email']);

		$oSmarty->assign('scriptname',			$config['scriptname']);

		$oSmarty->assign('base_url',			$config['base_url']);

		$oSmarty->assign('script_url',			$config['base_url'].$config['scriptname']);

		$oSmarty->assign('user',			array( 'is_logged_in'	=> isLoggedIn(),
								       'is_gm'		=> isGM(),
								       'is_admin'	=> isAdmin(),
								       'name'		=> ((isset($_SESSION['name'])) ? htmlspecialchars($_SESSION['name']) : 'Guest'),
								       ));

		$oSmarty->assign('stats',			loadStats());

		$oSmarty->assign('time_offset',			$config['time_offset']);

		$oSmarty->assign('toplist',			array( 'privatia'	=> $config['toplists']['privatia'] ));

		$oSmarty->assign('script',			array( 'version'	=> SCRIPT_VERSION,
								       'release'	=> SCRIPT_RELEASE_DATE,
								       'powered_by'	=> SCRIPT_POWERED_BY
								       ));

		$oSmarty->assign('enable_guild_admin',		$config['enable_guild_admin']);

	}

	/**
	 * Simple function to format a filesize. Converts bytes to KB/MB/GB/TB. Taken from the PHP-manual.
	 *
	 * @param integer $size
	 * @return string
	 */

	function format_filesize($size)
	{

		if($size / 1099511627776 > 1) {
			return round($size/1099511627776, 1) . ' TB';
		} elseif($size / 1073741824 > 1) {
			return round($size/1073741824, 1) . ' GB';
		} elseif($size / 1048576 > 1) {
			return round($size/1048576, 1) . ' MB';
		} elseif($size / 1024 > 1) {
			return round($size/1024, 1) . ' KB';
		} elseif($size > 1) {
			return round($size, 1) . ' bytes';
		} else {
			return 'unknown';
		}

	}

	/**
	 * Simple function to format a timestamp. Converts seconds to hours, minutes, days, whatever. Taken from the PHP-manual.
	 *
	 * @param integer $timestamp
	 * @param integer $num_times
	 * @return string
	 */

	function readable_time($timestamp, $num_times = 2)
	{

		// initialize variables

		$times		= array( 31536000	=> 'year',
					 2592000	=> 'month',
					 604800		=> 'week',
					 86400		=> 'day',
					 3600		=> 'hour',
					 60		=> 'minute',
					 1		=> 'second'	);

		$now		= time();

		$secs		= ((($now - $timestamp) > 0) ? ($now - $timestamp) : 1);

		$count		= 0;
		$time		= '';

		//

		foreach ($times AS $key => $value)
		{

			if ($secs >= $key)
			{
				// time found
				$s		= '';
				$time		.= floor($secs / $key);

				if ((floor($secs / $key) != 1))
					$s = 's';

				$time		.= ' ' . $value . $s;
				$count++;
				$secs		= $secs % $key;

				if ($count > $num_times - 1 || $secs == 0)
					break;
				else
					$time	.= ', ';
			}

		}

		return $time;

	}

	/**
	 * Simple function to create an image with a given string. Used to display some error-messages.
	 *
	 * @param string $msg
	 * @param integer $width
	 * @param integer $height
	 * @return image
	 */

	function errorImage($msg,$width=480,$height=310)
	{
		header("Content-type: image/png");
		$im			= @ImageCreate ($width, $height);
		$background_color	= ImageColorAllocate ($im, 255, 255, 255);
		$text_color		= ImageColorAllocate ($im, 233, 14, 91);
		ImageString ($im, 4, 5, 5, $msg, $text_color);
		ImagePNG ($im);
	}

	/**
	 * This function searches for a file and returns its real name. This is useful if you want to find
	 * out the real upper-/lowercase name of a file. (Currently unused!)
	 *
	 * @param string $path
	 * @param string $filename
	 * @return string
	 */

	function findFile($path,$filename)
	{

		$oDir	= dir($path);

		while (false !== ($entry = $oDir->read()))
		{
			if (strtolower($filename) == strtolower($entry))
			{
				$oDir->close();
				return trim($entry);
			}
		}

		$oDir->close();
		return '';

	}

	/**
	 * Return a random password.
	 *
	 * @param integer $length
	 * @return string
	 */

	function randomPassword($length=7)
	{

		$chars		= '!_abcdefghijkmnopqrstuvwxyz023456789';
		$i		= 0;
		$pass		= '' ;

		srand((double)microtime()*1000000);

		while ($i <= $length)
		{
			$num	= rand() % 36;
			$tmp	= substr($chars, $num, 1);
			$pass	= $pass . $tmp;
			$i++;
		}

		return $pass;

	}

	/**
	 * Pseudo-Cronjobs
	 *
	 * @return void
	 */

	function pseudoCron()
	{

		global $config;

		// Only in 10% of all calls we'll check if we have to do something

		if (mt_rand(0,100) < 10)
		{

			if (file_exists(BASEDIR.'data'.DIRECTORY_SEPARATOR.'cron.dat'))
			{

				//

				if (!function_exists('jobAccountPending'))
					require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.job.php');

				//

				$dummy		= @filemtime(BASEDIR.'data'.DIRECTORY_SEPARATOR.'cron.dat');

				$jobs		= @unserialize(@file_get_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'cron.dat'));

				if (is_array($jobs))
				{

					foreach ($jobs as $job=>$curjob)
					{

						if ((time() - $curjob['last_call']) >= $curjob['interval'])
						{

							switch (strtolower(trim($job)))
							{

								case 'cleanup_pending'	:

									$jobs[$job]['last_call']	= time();
									jobAccountPending();
									break;

								case 'autoban'		:

									$jobs[$job]['last_call']	= time();
									jobAutoban();
									break;

							}

						}

					}

				}

			}
			else
			{

				$jobs				= array();

				$jobs['cleanup_pending']	= array( 'last_call'	=> 0,
									 'interval'	=> 43200 );		// roughly every 12h

				$jobs['autoban']		= array( 'last_call'	=> 0,
									 'interval'	=> 3600 );		// roughly every hour

			}

			@file_put_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'cron.dat',@serialize($jobs));

		}

	}

?>
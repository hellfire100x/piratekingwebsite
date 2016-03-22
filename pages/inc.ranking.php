<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.ranking.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	//                added caching so rankings aren't queried on each call
	// v0.0.2       : changed all non-gm-queries (old version JOINed account which isn't neccessary)
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================
	// Lets check what kind of ranking the user wants to see
	// ===================================================================================================

	if (isset($_GET['rank']))
		$rank		= $_GET['rank'];

	$rank	= strtolower(trim(preg_replace('/[^0-9a-z]/i','',$rank)));

	// The following code prevents two things:
	//
	// 1) invalid "rank" parameter
	// 2) displaying of richest player if its disabled in config
	//
	// Fallback is always "exp" :-)

	if ((!in_array($rank,array('exp','gold','guild'))) || (($config['top_richest'] == 0) && ($rank == 'gold')))
		$rank		= 'exp';

	// ===================================================================================================
	// Build Breadcrumb-Navigation, 1st part ;-)
	// ===================================================================================================

	$breadcrumbs	= array();

	$breadcrumbs[]	= array( 'caption'	=> 'Home',
				 'url'		=> $config['script_url']			);

	//

	$oSmarty->assign('show_richest',	($config['top_richest'] > 0));

	// Decide what to display

	switch ($rank)
	{

		case 'exp'	:

			$cached		= false;

			// Do we have cached rankings?
	
			if (file_exists(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_exp.dat'))
			{
	
				// Yeah, lets see how hold they are
	
				clearstatcache();
	
				$dummy	= filemtime(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_exp.dat');
	
				if ((time()-$dummy) > $config['cache_rankings_exp'])
					@unlink(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_exp.dat');
				else
				{
					$players	= file_get_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_exp.dat');
					$players	= unserialize($players);
					$cached		= true;
				}
	
				if (isset($players))
					$oSmarty->assign('players',	$players);
	
			}
	
			if (!$cached)
			{
	
				// Query data from database
				//
				// Btw. the query takes the negative exp into account that happens when a player reaches Lv80! So even they will be displayed
				// in the right order!
	
				if ($config['is_gm_server'])
					$topmembers	= doQuery('SELECT TOP '.$config['top_players'].' g.guild_id, c.cha_name, c.icon, c.degree, c.job, CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END AS exp, CASE WHEN (g.guild_id = 0) THEN \'\' ELSE guild_name END AS guild_name FROM '.TABLE_CHARACTER.' AS c LEFT JOIN '.TABLE_GUILD.' AS g ON ((c.guild_id = g.guild_id) AND (c.guild_stat = 0)) WHERE (c.delflag = 0) ORDER BY CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END DESC', DATABASE_GAME);
				else
					$topmembers	= doQuery('SELECT TOP '.$config['top_players'].' g.guild_id, c.cha_name, c.icon, c.degree, c.job, CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END AS exp, CASE WHEN (g.guild_id = 0) THEN \'\' ELSE guild_name END AS guild_name FROM '.TABLE_CHARACTER.' AS c LEFT JOIN '.TABLE_ACCOUNT.' AS a ON (a.act_id = c.act_id) LEFT JOIN '.TABLE_GUILD.' AS g ON ((c.guild_id = g.guild_id) AND (c.guild_stat = 0)) WHERE ((c.delflag = 0) AND (a.gm = 0)) ORDER BY CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END DESC', DATABASE_GAME);
	
				if ($topmembers !== false)
				{
	
					$pos		= 1;
					$players	= array();
	
					while ($row = mssql_fetch_assoc($topmembers))
					{
	
						// move data into a new array and "htmlspecialchars" it so HTML won't break ;-)
	
						$players[$pos]	= array( 'name'		=> htmlspecialchars($row['cha_name']),
									 'level'	=> $row['degree'],
									 'exp'		=> $row['exp'],
									 'guild'	=> htmlspecialchars(trim($row['guild_name'])),
									 'char'		=> getCharName($row['icon']),
									 'job'		=> htmlspecialchars(getJobName($row['job']))		);
	
						$pos++;
	
					}
	
					$oSmarty->assign('players',	$players);
					
					@file_put_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_exp.dat',serialize($players));
	
				}
			        else
			        {
			        	$error	= true;
			                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
			        }
	
			}

			$breadcrumbs[]	= array( 'caption'	=> 'Top Pirates',
						 'url'		=> ''						);

			$template	= 'site_rank_exp';

			break;

		case 'gold'	:

			$cached		= false;

			// Do we have cached rankings?
	
			if (file_exists(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_gold.dat'))
			{
	
				// Yeah, lets see how hold they are
	
				clearstatcache();
	
				$dummy	= filemtime(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_gold.dat');
	
				if ((time()-$dummy) > $config['cache_rankings_gold'])
					@unlink(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_gold.dat');
				else
				{
					$players	= file_get_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_gold.dat');
					$players	= unserialize($players);
					$cached		= true;
				}
	
				if (isset($players))
					$oSmarty->assign('players',	$players);
	
			}

			if (!$cached)
			{
	
				// Query data from database
				//
				// Btw. the query takes the negative exp into account that happens when a player reaches Lv80! So even they will be displayed
				// in the right order!
	
				if ($config['is_gm_server'])
					$topmembers	= doQuery('SELECT TOP '.$config['top_richest'].' g.guild_id, c.gd, c.cha_name, c.icon, c.degree, c.job, CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END AS exp, CASE WHEN (g.guild_id = 0) THEN \'\' ELSE guild_name END AS guild_name FROM '.TABLE_CHARACTER.' AS c LEFT JOIN '.TABLE_GUILD.' AS g ON ((c.guild_id = g.guild_id) AND (c.guild_stat = 0)) WHERE (c.delflag = 0) ORDER BY gd DESC', DATABASE_GAME);
				else
					$topmembers	= doQuery('SELECT TOP '.$config['top_richest'].' g.guild_id, c.gd, c.cha_name, c.icon, c.degree, c.job, CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END AS exp, CASE WHEN (g.guild_id = 0) THEN \'\' ELSE guild_name END AS guild_name FROM '.TABLE_CHARACTER.' AS c LEFT JOIN '.TABLE_ACCOUNT.' AS a ON (a.act_id = c.act_id) LEFT JOIN '.TABLE_GUILD.' AS g ON ((c.guild_id = g.guild_id) AND (c.guild_stat = 0)) WHERE ((c.delflag = 0) AND (a.gm = 0)) ORDER BY gd DESC', DATABASE_GAME);
	
				if ($topmembers !== false)
				{
	
					$pos		= 1;
					$players	= array();
	
					while ($row = mssql_fetch_assoc($topmembers))
					{
	
						// move data into a new array and "htmlspecialchars" it so HTML won't break ;-)
	
						$players[$pos]	= array( 'name'		=> htmlspecialchars($row['cha_name']),
									 'level'	=> $row['degree'],
									 'gold'		=> $row['gd'],
									 'guild'	=> htmlspecialchars(trim($row['guild_name'])),
									 'char'		=> getCharName($row['icon']),
									 'job'		=> htmlspecialchars(getJobName($row['job']))		);
	
						$pos++;
	
					}
	
					$oSmarty->assign('players',	$players);

					@file_put_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_gold.dat',serialize($players));
	
				}
			        else
			        {
			        	$error	= true;
			                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
			        }

			}

			$breadcrumbs[]	= array( 'caption'	=> 'Richest Pirates',
						 'url'		=> ''						);

			$template	= 'site_rank_gold';

			break;

		case 'guild'	:

			$cached		= false;

			// Do we have cached rankings?
	
			if (file_exists(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_guild.dat'))
			{
	
				// Yeah, lets see how hold they are
	
				clearstatcache();
	
				$dummy	= filemtime(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_guild.dat');
	
				if ((time()-$dummy) > $config['cache_rankings_guild'])
					@unlink(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_guild.dat');
				else
				{
					$guilds		= file_get_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_guild.dat');
					$guilds		= unserialize($guilds);
					$cached		= true;
				}
	
				if (isset($guilds))
					$oSmarty->assign('guilds',	$guilds);
	
			}

			if (!$cached)
			{

			        // Query data from database
	
				if ($config['is_gm_server'])
				        $topguilds      = doQuery('SELECT TOP '.$config['top_guilds'].' g.guild_name, g.motto, c.cha_name FROM '.TABLE_GUILD.' AS g LEFT JOIN '.TABLE_CHARACTER.' AS c ON (c.cha_id = g.leader_id) WHERE (g.member_total > 0) ORDER BY g.member_total DESC', DATABASE_GAME);
				else
				        $topguilds      = doQuery('SELECT TOP '.$config['top_guilds'].' g.guild_name, g.motto, c.cha_name FROM '.TABLE_GUILD.' AS g LEFT JOIN '.TABLE_CHARACTER.' AS c ON (c.cha_id = g.leader_id) LEFT JOIN '.TABLE_ACCOUNT.' AS a ON (a.act_id = c.act_id) WHERE (g.member_total > 0) AND (a.gm = 0) ORDER BY g.member_total DESC', DATABASE_GAME);
	
			        if ($topguilds !== false)
			        {
	
			                $pos            = 1;
			                $guilds         = array();
	
			                while ($row = mssql_fetch_assoc($topguilds))
			                {
	
			                        // move data into a new array and "htmlspecialchars" it so HTML won't break ;-)
	
			                        $guilds[$pos]           = array( 'name'         => htmlspecialchars($row['guild_name']),
			                                                         'motto'        => htmlspecialchars(trim($row['motto'])),
			                                                         'leader'       => htmlspecialchars($row['cha_name'])           );
	
			                        $pos++;
	
			                }
	
			                $oSmarty->assign('guilds',      $guilds);
	
					@file_put_contents(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rankings_guild.dat',serialize($guilds));

			        }
			        else
			        {
			        	$error	= true;
			                $oSmarty->assign('error',       htmlspecialchars(mssql_get_last_message()));
			        }

			}

			$breadcrumbs[]	= array( 'caption'	=> 'Top Guides',
						 'url'		=> ''						);

			$template	= 'site_rank_guilds';

			break;

	}

	$oSmarty->assign('breadcrumbs',		$breadcrumbs);

      // Output everything

  	if (!$error)
		$oSmarty->display('file:pages/'.$template.'.tpl');

?>
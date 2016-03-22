<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.account.character.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3       : moved all character-related functions from inc.account.php to this file
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	/**
	 * Prepare list of all chars of the current user.
	 *
	 * @return bool
	 */

	function account_characters()
	{

		global $config, $oSmarty;

		// Load chars for this Account

		if ($config['max_compatibility_mode'])
		{
			$sql	= '
					SELECT
						c.cha_id, c.cha_name, c.icon, c.act_id, c.job, c.degree, c.hp, c.sp,
						CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END AS exp,
						c.ap, c.tp, c.gd, c.map, c.map_x, c.map_y, c.look, c.skillbag, c.shortcut,
						c.mission, c.misrecord, c.mistrigger, c.miscount, c.birth, c.str, c.dex,
						c.agi, c.con, c.sta,
						CASE WHEN (g.guild_id = 0) THEN \'\' ELSE guild_name END AS guild_name
					FROM
						'.TABLE_CHARACTER.' AS c
					LEFT JOIN
						'.TABLE_GUILD.' AS g ON ((c.guild_id = g.guild_id) AND (c.guild_stat = 0))
					LEFT JOIN
						'.TABLE_ACCOUNT.' AS a ON (c.act_id = a.act_id)
					WHERE
						(a.act_name	= \''.addslashes_mssql($_SESSION['name']).'\') AND
						(c.delflag	= 0)
					ORDER BY
						c.cha_name';
		}
		else
		{
			$sql	= '
				SELECT
					c.cha_id, c.cha_name, c.icon, c.act_id, c.job, c.degree, c.hp, c.sp,
					CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END AS exp,
					c.ap, c.tp, c.gd, c.map, c.map_x, c.map_y, c.look, c.skillbag, c.shortcut,
					c.mission, c.misrecord, c.mistrigger, c.miscount, c.birth, c.str, c.dex,
					c.agi, c.con, c.sta,
					CASE WHEN (g.guild_id = 0) THEN \'\' ELSE guild_name END AS guild_name
				FROM
					'.TABLE_CHARACTER.' AS c
				LEFT JOIN
					'.TABLE_GUILD.' AS g ON ((c.guild_id = g.guild_id) AND (c.guild_stat = 0))
				WHERE
					(c.act_id	= '.(int)$_SESSION['aid'].') AND
					(c.delflag	= 0)
				ORDER BY
					c.cha_name';
		}

	        $chars		= doQuery($sql, DATABASE_GAME);

	        if ($chars !== false)
	        {

	                $characters	= array();

	                while ($row = mssql_fetch_assoc($chars))
	                {

	                        // move data into a new array and "htmlspecialchars" it so HTML won't break ;-)

				$characters[]	= array( 'name'		=> htmlspecialchars($row['cha_name']),
							 'level'	=> (int)$row['degree'],
							 'guild'	=> htmlspecialchars($row['guild_name']),
							 'exp'		=> (int)$row['exp'],
							 'char'		=> htmlspecialchars($config['chars'][$row['icon']]),
							 'job'		=> htmlspecialchars(getJobName($row['job'])),
							 'id'		=> (int)$row['cha_id'],
							 'picture'	=> getCharPicture($row['icon'],$row['job']),
							 'gold'		=> (int)$row['gd'],
							 'ap'		=> (int)$row['ap'],
							 'tp'		=> (int)$row['tp'],
							 );

			}

			$oSmarty->assign('characters',	$characters);

			return true;

		}

		return false;

	}

	/**
	 * Prepare detail-view of a certain char
	 *
	 * @return bool
	 */

	function account_character_display($cid)
	{

		global $config, $oSmarty;

		// Load char

		if ($config['max_compatibility_mode'])
		{
			$sql	= '
				SELECT
					c.cha_id, c.cha_name, c.icon, c.act_id, c.job, c.degree, c.hp, c.sp,
					CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END AS exp,
					c.ap, c.tp, c.gd, c.map, c.map_x, c.map_y, c.look, c.skillbag, c.shortcut,
					c.mission, c.misrecord, c.mistrigger, c.miscount, c.birth, c.str, c.dex,
					c.agi, c.con, c.sta, c.luk, a.act_name,
					CASE WHEN (g.guild_id = 0) THEN \'\' ELSE guild_name END AS guild_name
				FROM
					'.TABLE_CHARACTER.' AS c
				LEFT JOIN
					'.TABLE_GUILD.' AS g ON ((c.guild_id = g.guild_id) AND (c.guild_stat = 0))
				LEFT JOIN
					'.TABLE_ACCOUNT.' AS a ON (c.act_id = a.act_id)
				WHERE
					(a.act_name	= \''.addslashes_mssql($_SESSION['name']).'\') AND
					(c.cha_id	= '.(int)$cid.') AND
					(c.delflag	= 0)
				ORDER BY
					c.cha_name';
		}
		else
		{
			$sql	= '
				SELECT
					c.cha_id, c.cha_name, c.icon, c.act_id, c.job, c.degree, c.hp, c.sp,
					CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END AS exp,
					c.ap, c.tp, c.gd, c.map, c.map_x, c.map_y, c.look, c.skillbag, c.shortcut,
					c.mission, c.misrecord, c.mistrigger, c.miscount, c.birth, c.str, c.dex,
					c.agi, c.con, c.sta, c.luk,
					CASE WHEN (g.guild_id = 0) THEN \'\' ELSE guild_name END AS guild_name
				FROM
					'.TABLE_CHARACTER.' AS c
				LEFT JOIN
					'.TABLE_GUILD.' AS g ON ((c.guild_id = g.guild_id) AND (c.guild_stat = 0))
				WHERE
					(c.act_id	= '.(int)$_SESSION['aid'].') AND
					(c.cha_id	= '.(int)$cid.') AND
					(c.delflag	= 0)
				ORDER BY
					c.cha_name';
		}

	        $chars		= doQuery($sql, DATABASE_GAME);

	        if ($chars !== false)
	        {

			if (mssql_num_rows($chars) == 1)
			{

		                $row		= mssql_fetch_assoc($chars);

				if ($config['max_compatibility_mode'])
					$own_char	= (trim(strtolower($_SESSION['name'])) == trim(strtolower($row['act_name'])));
				else
					$own_char	= ((int)$_SESSION['aid'] == (int)$row['act_id']);

				if ($own_char)
				{

					$character	= array( 'name'		=> htmlspecialchars($row['cha_name']),
								 'level'	=> (int)$row['degree'],
								 'guild'	=> htmlspecialchars($row['guild_name']),
								 'exp'		=> (int)$row['exp'],
								 'char'		=> htmlspecialchars($config['chars'][$row['icon']]),
								 'job'		=> htmlspecialchars(getJobName($row['job'])),
								 'id'		=> (int)$row['cha_id'],
								 'picture'	=> getCharPicture($row['icon'],$row['job']),
								 'gold'		=> (int)$row['gd'],
								 'ap'		=> (int)$row['ap'],
								 'tp'		=> (int)$row['tp'],
								 'str'		=> (int)$row['str'],
								 'con'		=> (int)$row['con'],
								 'spr'		=> (int)$row['sta'],
								 'acc'		=> (int)$row['dex'],
								 'agi'		=> (int)$row['agi'],
								 'luk'		=> (int)$row['luk'],
								 );

					$oSmarty->assign('character',	$character);

					return true;

				}
				else
				{
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'What makes you think you can view another players char?' ) ) );
				}
			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array( 'Invalid Char-ID.' ) ) );
			}

		}
		else
		{
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Database error while reading character.' ) ) );
		}

		return false;

	}

?>
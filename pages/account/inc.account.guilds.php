<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.account.guilds.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3       : moved all guild-related functions from inc.account.php to this file
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	/**
	 * Prepare list of all guilds the current player is leader of.
	 *
	 * @return bool
	 */

	function account_guild_list()
	{

		global $config, $oSmarty;

		// Get all guilds where this user is leader

		$sql		= '						SELECT
							g.guild_id, g.guild_name, g.motto, g.member_total, g.try_total
						FROM
							'.TABLE_ACCOUNT.' AS a
						LEFT JOIN
							'.TABLE_CHARACTER.' AS c ON (a.act_id = c.act_id)
						LEFT JOIN
							'.TABLE_GUILD.' AS g ON (g.leader_id = c.cha_id)
						WHERE
							(c.delflag = 0)
							AND (ISNULL(g.guild_id,0) > 0)';


		if ($config['max_compatibility_mode'])
		{
			$sql		= $sql.'
							AND (a.act_name = \''.addslashes_mssql($_SESSION['name']).'\')';
		}
		else
		{
			$sql		= $sql.'
							AND (a.act_id = '.(int)$_SESSION['aid'].')';
		}

		// query database

	        $guild		= doQuery($sql, DATABASE_GAME);

	        if ($guild !== false)
	        {

	                $guilds		= array();

	                while ($row = mssql_fetch_assoc($guild))
	                {

	                        // move data into a new array and "htmlspecialchars" it so HTML won't break ;-)

				$guilds[]	= array( 'name'		=> htmlspecialchars($row['guild_name']),
							 'id'		=> (int)$row['guild_id'],
							 'motto'	=> htmlspecialchars($row['motto']),
							 'members'	=> (int)$row['member_total'],
							 'applicants'	=> (int)$row['try_total']			);

			}

			$oSmarty->assign('guilds',	$guilds);

			return true;

		}

		return false;

	}

	/**
	 * Prepare list of all membera/applicants of a guild, etc.
	 *
	 * @param int $id
	 * @return bool
	 */

	function account_guild_view($id)
	{

		global $config, $oSmarty;

		// Get all guilds where this user is leader

		$sql		= '						SELECT
							g.guild_id, g.guild_name, g.motto, g.member_total, g.try_total
						FROM
							'.TABLE_ACCOUNT.' AS a
						LEFT JOIN
							'.TABLE_CHARACTER.' AS c ON (a.act_id = c.act_id)
						LEFT JOIN
							'.TABLE_GUILD.' AS g ON (g.leader_id = c.cha_id)
						WHERE
							(c.delflag = 0)
							AND (ISNULL(g.guild_id,0) = '.(int)$id.')';


		if ($config['max_compatibility_mode'])
		{
			$sql		= $sql.'
							AND (a.act_name = \''.addslashes_mssql($_SESSION['name']).'\')';
		}
		else
		{
			$sql		= $sql.'
							AND (a.act_id = '.(int)$_SESSION['aid'].')';
		}

		// query database

	        $guild		= doQuery($sql, DATABASE_GAME);

	        if ($guild !== false)
	        {

			if (mssql_num_rows($guild)==1)
			{

				$gdata		= mssql_fetch_assoc($guild);

				$members	= array();
				$applicants	= array();

				$sql		= '
						SELECT
							*
						FROM
							'.TABLE_CHARACTER.' AS c
						WHERE
							(c.delflag = 0) AND
							(c.guild_id = '.$id.')
						ORDER BY
							CASE WHEN (c.exp < 0) THEN (c.exp+4294967296) ELSE c.exp END DESC';

			        $chars		= doQuery($sql, DATABASE_GAME);

			        if ($chars !== false)
			        {

			                while ($row = mssql_fetch_assoc($chars))
			                {

			                        // move data into a new array and "htmlspecialchars" it so HTML won't break ;-)

						if ((int)$row['guild_stat'] == 1)
							$applicants[]	= array( 'id'		=> (int)$row['cha_id'],
										 'name'		=> htmlspecialchars($row['cha_name']),
										 'level'	=> (int)$row['degree'],
										 'char'		=> htmlspecialchars($config['chars'][$row['icon']]),
										 'job'		=> htmlspecialchars(getJobName($row['job'])),
										 'status'	=> (((int)$row['mem_addr'] > 0) ? '<span style="color:#090;">online</span>' : '<span style="color:#900;">offline</span>')	 );
						else
							$members[]	= array( 'id'		=> (int)$row['cha_id'],
										 'name'		=> htmlspecialchars($row['cha_name']),
										 'level'	=> (int)$row['degree'],
										 'char'		=> htmlspecialchars($config['chars'][$row['icon']]),
										 'job'		=> htmlspecialchars(getJobName($row['job'])),
										 'online'	=> (((int)$row['mem_addr'] > 0) ? true : false) ) ;

					}

			        }

				$oSmarty->assign('members',	$members);
				$oSmarty->assign('applicants',	$applicants);
				$oSmarty->assign('guild',	$gdata);


			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> 'Invalid Guild-ID.' ) );
			}

			return true;

		}

		return false;

	}

	/**
	 * Approve a certain char. Yeah, I know - its looking complicated - but thats only because i'm taking a lot
	 * of security measures to prevent abuse of this function.
	 *
	 * @param string $id
	 * @return bool
	 */

	function account_guild_approve($id)
	{

		global $config, $oSmarty;

		// First we'll load all data we need from that particular character
		// We DO NOT care at this point if the user is online!

		$sql		= 'SELECT cha_name, guild_id, guild_stat FROM '.TABLE_CHARACTER.' WHERE cha_id = '.(int)$id;

	        $char		= doQuery($sql, DATABASE_GAME);

	        if (($char !== false) && (mssql_num_rows($char) == 1))
	        {

			$curchar	= mssql_fetch_assoc($char);

			// Lets see if this is a valid operation for this char

			if ($curchar['guild_stat'] == 1)
			{

				// OK, he is applying, check if the current website-user is guild-leader of that guild

				$sql		= 'SELECT * FROM '.TABLE_ACCOUNT.' AS a LEFT JOIN '.TABLE_CHARACTER.' AS c ON (a.act_id = c.act_id) LEFT JOIN '.TABLE_GUILD.' AS g ON (g.leader_id = c.cha_id) WHERE (c.delflag = 0) AND (ISNULL(g.guild_id,0) = '.(int)$curchar['guild_id'].')';

				if ($config['max_compatibility_mode'])
					$sql		= $sql.' AND (a.act_name = \''.addslashes_mssql($_SESSION['name']).'\')';
				else
					$sql		= $sql.' AND (a.act_id = '.(int)$_SESSION['aid'].')';

			        $guild		= doQuery($sql, DATABASE_GAME);

			        if ($guild !== false)
			        {
					if (mssql_num_rows($guild) == 1)
					{

						// OK, everything is fine so far. Update the status.
						// To prevent modifying online chars, we'll now add mem_addr to the WHERE :o)

						$sql		= 'UPDATE '.TABLE_CHARACTER.' SET guild_stat = 0 WHERE ((mem_addr = 0) AND (cha_id = '.(int)$id.'))';

					        $approve	= doQuery($sql, DATABASE_GAME);

					        if ($approve !== false)
					        {

							// Check if we have been successful

							$rcount		= doQuery('SELECT COUNT(*) AS rows FROM '.TABLE_CHARACTER.' WHERE ((cha_id = '.(int)$id.') AND (guild_stat = 0))', DATABASE_GAME);
							$rows		= mssql_result($rcount, 0, 'rows');

							if ($rows == 1)
							{

								$oSmarty->assign('message',			array( 'type'		=> 'ok',
														       'messages'	=> array( 'Character has been added to your guild.' ) ) );

								// Fix totals

								$sql		= 'UPDATE '.TABLE_GUILD.' SET try_total = try_total - 1, member_total = member_total + 1 WHERE guild_id = '.(int)$char['guild_id'];
							        $counter	= doQuery($sql, DATABASE_GAME);

								//

								return true;

							}
							else
							{
								$oSmarty->assign('message',			array( 'type'		=> 'error',
														       'messages'	=> array( 'Character is online. Please try again when he is offline again.' ) ) );
							}

						}
						else
						{
							$oSmarty->assign('message',			array( 'type'		=> 'error',
													       'messages'	=> array( 'Database error while updating char-details.' ) ) );
						}

					}
					else
					{
						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array( 'Error approving char - there was a problem verifying your leader-status.' ) ) );
					}
			        }
			        else
			        {
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Database error while reading leader-details.' ) ) );
			        }

			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array( 'Error approving char - invalid status.' ) ) );
			}

		}
		else
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Database error while reading char-details.' ) ) );

		return false;

	}

	/**
	 * Decline a certain char. Yeah, I know - its looking complicated - but thats only because i'm taking a lot
	 * of security measures to prevent abuse of this function.
	 *
	 * @param string $id
	 * @return bool
	 */

	function account_guild_decline($id)
	{

		global $config, $oSmarty;

		// First we'll load all data we need from that particular character
		// We DO NOT care at this point if the user is online!

		$sql		= 'SELECT cha_name, guild_id, guild_stat FROM '.TABLE_CHARACTER.' WHERE cha_id = '.(int)$id;

	        $char		= doQuery($sql, DATABASE_GAME);

	        if (($char !== false) && (mssql_num_rows($char) == 1))
	        {

			$curchar	= mssql_fetch_assoc($char);

			// Lets see if this is a valid operation for this char

			if ($curchar['guild_stat'] == 1)
			{

				// OK, he is applying, check if the current website-user is guild-leader of that guild

				$sql		= 'SELECT * FROM '.TABLE_ACCOUNT.' AS a LEFT JOIN '.TABLE_CHARACTER.' AS c ON (a.act_id = c.act_id) LEFT JOIN '.TABLE_GUILD.' AS g ON (g.leader_id = c.cha_id) WHERE (c.delflag = 0) AND (ISNULL(g.guild_id,0) = '.(int)$curchar['guild_id'].')';

				if ($config['max_compatibility_mode'])
					$sql		= $sql.' AND (a.act_name = \''.addslashes_mssql($_SESSION['name']).'\')';
				else
					$sql		= $sql.' AND (a.act_id = '.(int)$_SESSION['aid'].')';

			        $guild		= doQuery($sql, DATABASE_GAME);

			        if ($guild !== false)
			        {

					if (mssql_num_rows($guild) == 1)
					{

						// OK, everything is fine so far. Update the status.
						// To prevent modifying online chars, we'll now add mem_addr to the WHERE :o)

						$sql		= 'UPDATE '.TABLE_CHARACTER.' SET guild_stat = 0, guild_id = 0 WHERE ((mem_addr = 0) AND (cha_id = '.(int)$id.'))';

					        $decline	= doQuery($sql, DATABASE_GAME);

					        if ($decline !== false)
					        {

							// Check if we have been successful

							$rcount		= doQuery('SELECT COUNT(*) AS rows FROM '.TABLE_CHARACTER.' WHERE ((cha_id = '.(int)$id.') AND (guild_stat = 0))', DATABASE_GAME);
							$rows		= mssql_result($rcount, 0, 'rows');

							if ($rows == 1)
							{

								$oSmarty->assign('message',			array( 'type'		=> 'ok',
														       'messages'	=> array( 'Character was declined.' ) ) );

								// Fix totals

								$sql		= 'UPDATE '.TABLE_GUILD.' SET try_total = try_total - 1 WHERE guild_id = '.(int)$char['guild_id'];
							        $counter	= doQuery($sql, DATABASE_GAME);

								//

								return true;

							}
							else
							{
								$oSmarty->assign('message',			array( 'type'		=> 'error',
														       'messages'	=> array( 'Character is online. Please try again when he is offline again.' ) ) );
							}

						}
						else
						{
							$oSmarty->assign('message',			array( 'type'		=> 'error',
													       'messages'	=> array( 'Database error while updating char-details.' ) ) );
						}

					}
					else
					{
						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array( 'Error declining char - there was a problem verifying your leader-status.' ) ) );
					}
			        }
			        else
			        {
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Database error while reading leader-details.' ) ) );
			        }

			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array( 'Error declining char - invalid status.' ) ) );
			}

		}
		else
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Database error while reading char-details.' ) ) );

		return false;

	}

	/**
	 * Kick a certain char. Yeah, I know - its looking complicated - but thats only because i'm taking a lot
	 * of security measures to prevent abuse of this function.
	 *
	 * @param string $id
	 * @return bool
	 */

	function account_guild_kick($id)
	{

		global $config, $oSmarty;

		// First we'll load all data we need from that particular character
		// We DO NOT care at this point if the user is online!

		$sql		= 'SELECT cha_name, guild_id, guild_stat, guild_permission FROM '.TABLE_CHARACTER.' WHERE (cha_id = '.(int)$id.')';

	        $char		= doQuery($sql, DATABASE_GAME);

	        if (($char !== false) && (mssql_num_rows($char) == 1))
	        {

			$curchar	= mssql_fetch_assoc($char);

			// Lets see if this is a valid operation for this char

			if ($curchar['guild_permission'] <> -1)
			{

				if ($curchar['guild_stat'] == 1)
				{

					// OK, he is applying, check if the current website-user is guild-leader of that guild

					$sql		= 'SELECT * FROM '.TABLE_ACCOUNT.' AS a LEFT JOIN '.TABLE_CHARACTER.' AS c ON (a.act_id = c.act_id) LEFT JOIN '.TABLE_GUILD.' AS g ON (g.leader_id = c.cha_id) WHERE (c.delflag = 0) AND (ISNULL(g.guild_id,0) = '.(int)$curchar['guild_id'].')';

					if ($config['max_compatibility_mode'])
						$sql		= $sql.' AND (a.act_name = \''.addslashes_mssql($_SESSION['name']).'\')';
					else
						$sql		= $sql.' AND (a.act_id = '.(int)$_SESSION['aid'].')';

				        $guild		= doQuery($sql, DATABASE_GAME);

				        if ($guild !== false)
				        {

						if (mssql_num_rows($guild) == 1)
						{

							// OK, everything is fine so far. Update the status.
							// To prevent modifying online chars, we'll now add mem_addr to the WHERE :o)

							$sql		= 'UPDATE '.TABLE_CHARACTER.' SET guild_stat = 0, guild_id = 0 WHERE ((mem_addr = 0) AND (cha_id = '.(int)$id.'))';

						        $kick		= doQuery($sql, DATABASE_GAME);

						        if ($kick !== false)
						        {

								// Check if we have been successful

								$rcount		= doQuery('SELECT COUNT(*) AS rows FROM '.TABLE_CHARACTER.' WHERE ((cha_id = '.(int)$id.') AND (guild_stat = 0))', DATABASE_GAME);
								$rows		= mssql_result($rcount, 0, 'rows');

								if ($rows == 1)
								{

									$oSmarty->assign('message',			array( 'type'		=> 'ok',
															       'messages'	=> array( 'Character was kicked from guild.' ) ) );

									// Fix totals

									$sql		= 'UPDATE '.TABLE_GUILD.' SET member_total = member_total - 1 WHERE guild_id = '.(int)$char['guild_id'];
								        $counter	= doQuery($sql, DATABASE_GAME);

									//

									return true;

								}
								else
								{
									$oSmarty->assign('message',			array( 'type'		=> 'error',
															       'messages'	=> array( 'Character is online. Please try again when he is offline again.' ) ) );
								}

							}
							else
							{
								$oSmarty->assign('message',			array( 'type'		=> 'error',
														       'messages'	=> array( 'Database error while updating char-details.' ) ) );
							}

						}
						else
						{
							$oSmarty->assign('message',			array( 'type'		=> 'error',
													       'messages'	=> array( 'Error kicking char - there was a problem verifying your leader-status.' ) ) );
						}
				        }
				        else
				        {
						$oSmarty->assign('message',			array( 'type'		=> 'error',
												       'messages'	=> array( 'Database error while reading leader-details.' ) ) );
				        }

				}
				else
				{
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Error kicking char - invalid status.' ) ) );
				}

			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array( 'Error kicking char - the guild-leader can\'t be kicked.' ) ) );
			}

		}
		else
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Database error while reading char-details.' ) ) );

		return false;

	}

?>
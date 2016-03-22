<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.account.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3b      : some minor changes regarding filenames
	// v0.0.3	: added more guild-features, moved functions to seperate files
	// v0.0.2	: added guild-view
	// v0.0.1c      : changed some functions to use act_name instead of act_id
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	if (!function_exists('validAccountname'))
		require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.validate.php');

	if (!function_exists('loadAccountByName'))
		require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.functions.account.php');

	if (!class_exists('Securimage'))
		require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'securimage'.DIRECTORY_SEPARATOR.'securimage.php');

	// ===================================================================================================

	// I moved most of the function to some seperate files as the code is easier to maintain that way.
	// I don't like huge files ;o)

	require_once(BASEDIR.'pages'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'inc.account.guilds.php');
	require_once(BASEDIR.'pages'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'inc.account.chars.php');
	require_once(BASEDIR.'pages'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'inc.account.data.php');

	// ===================================================================================================

	$templatefile		= 'site_account.tpl';

	// Build Breadcrumb-Navigation

	$breadcrumbs	= array();

	$breadcrumbs[]	= array( 'caption'	=> 'Home',
				 'url'		=> $config['script_url']			);

	//

	if (isLoggedIn() && checkReferer() && !isBanned())
	{

		// Get URL-params

		if ((!isset($_GET['sact'])) && (!isset($_POST['sact'])))
			$subaction		= 'display';
		else
			$subaction		= ((isset($_POST['sact'])) ? $_POST['sact'] : $_GET['sact']);

		$subaction		= strtolower(trim(preg_replace('/[^0-9a-z]/i','',$subaction)));

		$id			= 0;

		if ((isset($_GET['id'])) || (isset($_POST['id'])))
			$id		= ((isset($_POST['id'])) ? (int)$_POST['id'] : (int)$_GET['id']);

		// Handle subaction

		switch ($subaction)
		{

			case 'chardetail'	:

				if (account_character_display($id))
				{
					$templatefile		= 'site_chardetail.tpl';
				}

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> site_url('account')		);

				$breadcrumbs[]	= array( 'caption'	=> 'Your Characters',
							 'url'		=> site_url('account', array( 'sact' => 'chars'))	);

				$breadcrumbs[]	= array( 'caption'	=> 'Character-Details',
							 'url'		=> ''				);

				break;

			case 'changepw'		:

				$templatefile		= 'site_changepw.tpl';

				if (isset($_POST['sact']))
				{
					account_password_change();
				}

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> site_url('account')		);

				$breadcrumbs[]	= array( 'caption'	=> 'Change Password',
							 'url'		=> ''				);

				break;

			case 'changemail'	:

				$templatefile		= 'site_changemail.tpl';

				if (isset($_POST['sact']))
				{
					account_mail_change();
				}

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> site_url('account')		);

				$breadcrumbs[]	= array( 'caption'	=> 'Change eMail-Address',
							 'url'		=> ''				);

				break;

			case 'chars'		:

				if (account_characters())
				{
					$templatefile		= 'site_charlist.tpl';
				}
				else
				{
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Database error while reading characters.' ) ) );
				}

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> site_url('account')		);

				$breadcrumbs[]	= array( 'caption'	=> 'Your Characters',
							 'url'		=> ''				);

				break;


			case 'guildkick'	:

				if (!$config['enable_guild_admin'])
					die('Go away!');

				$gid			= 0;

				if ((isset($_GET['gid'])))
					$gid		= (int)$_GET['gid'];

				account_guild_kick($id);

				if (account_guild_view($gid))
					$templatefile		= 'site_guildview.tpl';
				else
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Database error while reading guilds.' ) ) );

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> site_url('account')		);

				$breadcrumbs[]	= array( 'caption'	=> 'Your Guilds',
							 'url'		=> site_url('account', array( 'sact' => 'guilds' ) ) );

				$breadcrumbs[]	= array( 'caption'	=> 'View Guild',
							 'url'		=> ''				);

				break;

			case 'guildapprove'	:

				if (!$config['enable_guild_admin'])
					die('Go away!');

				$gid			= 0;

				if ((isset($_GET['gid'])))
					$gid		= (int)$_GET['gid'];

				account_guild_approve($id);

				if (account_guild_view($gid))
					$templatefile		= 'site_guildview.tpl';
				else
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Database error while reading guilds.' ) ) );

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> site_url('account')		);

				$breadcrumbs[]	= array( 'caption'	=> 'Your Guilds',
							 'url'		=> site_url('account', array( 'sact' => 'guilds' ) ) );

				$breadcrumbs[]	= array( 'caption'	=> 'View Guild',
							 'url'		=> ''				);

				break;

			case 'guilddecline'	:

				if (!$config['enable_guild_admin'])
					die('Go away!');

				$gid			= 0;

				if ((isset($_GET['gid'])))
					$gid		= (int)$_GET['gid'];

				account_guild_decline($id);

				if (account_guild_view($gid))
					$templatefile		= 'site_guildview.tpl';
				else
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Database error while reading guilds.' ) ) );

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> site_url('account')		);

				$breadcrumbs[]	= array( 'caption'	=> 'Your Guilds',
							 'url'		=> site_url('account', array( 'sact' => 'guilds' ) ) );

				$breadcrumbs[]	= array( 'caption'	=> 'View Guild',
							 'url'		=> ''				);

				break;

			case 'guilds'		:

				if (!$config['enable_guild_admin'])
					die('Go away!');

				if (account_guild_list())
					$templatefile		= 'site_guildlist.tpl';
				else
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Database error while reading guilds.' ) ) );

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> site_url('account')		);

				$breadcrumbs[]	= array( 'caption'	=> 'Your Guilds',
							 'url'		=> ''				);

				break;

			case 'guildview'		:

				if (!$config['enable_guild_admin'])
					die('Go away!');

				if (account_guild_view($id))
					$templatefile		= 'site_guildview.tpl';
				else
					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Database error while reading guilds.' ) ) );

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> site_url('account')		);

				$breadcrumbs[]	= array( 'caption'	=> 'Your Guilds',
							 'url'		=> site_url('account', array( 'sact' => 'guilds' ) ) );

				$breadcrumbs[]	= array( 'caption'	=> 'View Guild',
							 'url'		=> ''				);

				break;

			case 'display'		:
			default			:

				$data		= loadAccount($_SESSION['aid']);

				$oSmarty->assign('account',		array( 'last_login'	=> (int)$data['last_login_time'],
									       'last_logout'	=> (int)$data['last_logout_time'],
									       'last_login_ip'	=> $data['last_login_ip'],
									       'email'		=> htmlspecialchars($data['email']),
									       'gm'		=> $_SESSION['level'],
									       'time_online'	=> readable_time(time()-$data['total_live_time'],6),
									       ));

				$breadcrumbs[]	= array( 'caption'	=> 'Your Account',
							 'url'		=> ''				);

				break;

		}

	}
	else
	{

		if (!isLoggedIn())
		{
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'You are not logged in.',
									       				  'Please <a href="'.site_url('login').'">login</a>.' ) ) );
		}
		elseif (!checkReferer())
		{
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Invalid referer.' ) ) );
		}
		elseif (isBanned())
		{
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Your account has been banned.' ) ) );
		}

	}

	// Assign Breadcrumbs

  	$oSmarty->assign('breadcrumbs',		$breadcrumbs);

	// Display Template

  	if (!$error)
		$oSmarty->display('file:pages/account/'.$templatefile);

?>
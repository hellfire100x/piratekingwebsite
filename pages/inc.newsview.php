<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.newsview.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3b      : some minor changes regarding filenames
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================
	// Include needed files

	require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'class.news.php');					// News-class

	// get category from URL

	$cid		= 0;

	if (isset($_GET['cid']))
		$cid	= (int)$_GET['cid'];

	$oSmarty->assign('current_category',	$cid);

	// Lets do some work ;-)

	if (isset($_GET['id']))
	{

		$headline	= '';

		// Get ID from URL

		$id		= (int)$_GET['id'];

		// Load News

		$oNews		= new NewsHandler($config['site_db_type']);

		$entry		= $oNews->getEntry($id);

		unset($oNews);

		// Assign Variables

		if ($entry!==false)
		{
			$oSmarty->assign('news',	array( 'headline'	=> htmlspecialchars($entry['headline']),
							       'author'		=> htmlspecialchars($entry['author']),
							       'category'	=> htmlspecialchars($entry['category']),
							       'body'		=> $entry['body'],
							       'date'		=> (int)$entry['date']	) );

			$headline	= $entry['headline'];
		}
		else
		{
			$error		= true;
			$oSmarty->assign('error',	'News-Entry not found.');
		}

		// Assign Breadcrumb-Navigation

		$breadcrumbs	= array();

		$breadcrumbs[]	= array( 'caption'	=> 'Home',
					 'url'		=> $config['script_url']			);

		$breadcrumbs[]	= array( 'caption'	=> 'News-Archive',
					 'url'		=> $config['script_url'].'?act=news'		);

		$breadcrumbs[]	= array( 'caption'	=> htmlspecialchars($headline),
					 'url'		=> ''						);

		$oSmarty->assign('breadcrumbs',		$breadcrumbs);

	}
	else
	{
		$error		= true;
		$oSmarty->assign('error',	'No News-Entry-ID passed to script.');
	}

	// Display Template

	if (!$error)
		$oSmarty->display('file:pages/site_newsview.tpl');

?>
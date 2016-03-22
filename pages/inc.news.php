<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.news.php
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

	// Lets do some work ;-)

	$cid		= 0;

	if (isset($_GET['cid']))
		$cid	= (int)$_GET['cid'];

	// Load news

	$oNews		= new NewsHandler($config['site_db_type']);

	$entries	= $oNews->getLatestNews(25,$cid);

	if ($entries!==false)
	{

		$news		= array();

		foreach ($entries as $curentry)
		{
			$news[]	= array( 'id'		=> (int)$curentry['news_id'],
					 'category'	=> htmlspecialchars($curentry['category']),
					 'headline'	=> htmlspecialchars($curentry['headline']),
					 'author'	=> htmlspecialchars($curentry['author']),
					 'date'		=> (int)$curentry['date']	);
		}

		$oSmarty->assign('news',		$news);
		$oSmarty->assign('current_category',	$cid);

	}

	// Assign Breadcrumb-Navigation

	$breadcrumbs	= array();

	$breadcrumbs[]	= array( 'caption'	=> 'Home',
				 'url'		=> $config['script_url']			);

	$breadcrumbs[]	= array( 'caption'	=> 'News-Archive',
				 'url'		=> ''						);

	$oSmarty->assign('breadcrumbs',		$breadcrumbs);

	// Display Template

	if (!$error)
		$oSmarty->display('file:pages/site_news.tpl');

?>
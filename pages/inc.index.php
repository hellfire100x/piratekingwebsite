<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.index.php
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

	// Load News

	$oNews		= new NewsHandler($config['site_db_type']);

	$news		= $oNews->getLatestNews($config['news_display'] + 6);

	unset($oNews);

	// Display them :-)

	$recent		= array_splice($news,0,$config['news_display']);

	if (count($news) > 5)
	{

		$oSmarty->assign('show_archive',	true);
	}

	// Assign Template-Variables

	$oSmarty->assign('recent',		$recent);
	$oSmarty->assign('news',		$news);

	// Build breadcrumb-navigation

	$breadcrumbs	= array();

	$breadcrumbs[]	= array( 'caption'	=> 'Home',
				 'url'		=> ''	);

	$oSmarty->assign('breadcrumbs',		$breadcrumbs);

	// Display page

	$oSmarty->display('file:pages/site_index.tpl');

?>
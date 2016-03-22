<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.constants.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// Description  : This file defines various constants used in the NewsHandler and DownloadHandler
	//                classes. The constants are used so you can easily change the table-names if you
	//                want to.
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.2       : added constants for tables in Accountserver and GameDB, etc.
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	$prefix		= '';

	// Table-Names for SQLite/MySQL/...

	define('TABLE_DOWNLOADS',			$prefix.'downloads');
	define('TABLE_DOWNLOAD_CATEGORIES',		$prefix.'dlcategories');
	define('TABLE_NEWS',				$prefix.'news');
	define('TABLE_NEWS_CATEGORIES',			$prefix.'ncategories');
	define('TABLE_AUTHORS',				$prefix.'authors');

	// "Table"-Names for Flatfile-version -> these are actually file-names

	define('TABLE_DOWNLOADS_FF',			'downloads.dat');
	define('TABLE_DOWNLOAD_CATEGORIES_FF',		'dlcategories.dat');
	define('TABLE_NEWS_FF',				'news.dat');
	define('TABLE_NEWS_CATEGORIES_FF',		'ncategories.dat');
	define('TABLE_AUTHORS_FF',			'authors.dat');

	// Table-Names for the GameDB/AccountServer

	define('TABLE_ACCOUNT_INVT',			'account_invt');
	define('TABLE_ACCOUNT_LOGIN',			'account_login');
	define('TABLE_ACCOUNT_PENDING',			'account_pending');
	define('TABLE_ACCOUNT_DETAILS',			'account_details');

	define('TABLE_ACCOUNT',				'account');
	define('TABLE_CHARACTER',			'character');
	define('TABLE_GUILD',				'guild');
	define('TABLE_STAT',				'stat_log');

	// Database-Identifier

	define('DATABASE_ACCOUNT',			'account');
	define('DATABASE_GAME',				'game');

	// Just some usefull constants ;-)

	define('SCRIPT_VERSION',			'1.0.0');
	define('SCRIPT_RELEASE_DATE',			'2009-02-23 12:00:00');
	define('SCRIPT_POWERED_BY',			'powered by pkoSite v'.SCRIPT_VERSION.' ('.SCRIPT_RELEASE_DATE.')');

?>
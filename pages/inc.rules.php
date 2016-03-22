<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.rules.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================
	// Load rules

	if (file_exists(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rules.dat'))
	{
		// This is NOT a normal "dat" file. Its just a plain textfile containing the rules.
		$rules		= @file(BASEDIR.'data'.DIRECTORY_SEPARATOR.'rules.dat');
		$oSmarty->assign('rules',	implode('',$rules));
	}
	else
	{
		$error		= true;
		$oSmarty->assign('error',	'Could not load rules.');
	}

	// Build Breadcrumb-Navigation

	$breadcrumbs	= array();

	$breadcrumbs[]	= array( 'caption'	=> 'Home',
				 'url'		=> $config['script_url']			);

	$breadcrumbs[]	= array( 'caption'	=> 'Rules',
				 'url'		=> ''						);

	$oSmarty->assign('breadcrumbs',		$breadcrumbs);

	// Display Template

	if (!$error)
		$oSmarty->display('file:pages/site_rules.tpl');

?>
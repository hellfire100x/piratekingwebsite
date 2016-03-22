<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.functions.captcha.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// Description  : This file contains the CAPTCHA-functions.
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

	// Load CAPTCHA-class

	require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'securimage'.DIRECTORY_SEPARATOR.'securimage.php');		// CAPTCHA-class

	/**
	 * Outputs a WAV-File containing the CAPTCHA in audible form
	 * @author SnowCrash
	 * @return mixed
	 */

	function captchaAudio()
	{

		$oSI	= new Securimage();

		header('Content-type: audio/x-wav');
		header('Content-Disposition: attachment; name="securimage.wav"');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Expires: Sun, 1 Jan 2000 12:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');

		echo $oSI->getAudibleCode();

		exit;

	}

	/**
	 * Generates the CAPTCHA as an image and outputs it.
	 * @author SnowCrash
	 * @return mixed
	 */

	function captchaShow()
	{

		global $config;

		$oSI	= new Securimage();

		$oSI->ttf_file		= BASEDIR.'classes'.DIRECTORY_SEPARATOR.'securimage'.DIRECTORY_SEPARATOR.'elephant.ttf';
		$oSI->gd_font_file	= BASEDIR.'classes'.DIRECTORY_SEPARATOR.'securimage'.DIRECTORY_SEPARATOR.'gdfonts'.DIRECTORY_SEPARATOR.'bubblebath.gdf';
		$oSI->code_length	= 5;

		echo $oSI->show();

		exit;

	}

?>
<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.downloads.php
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

	// Load the needed class

	require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'class.downloads.php');

	// Lets see if we have a category-id

	if (isset($_GET['cid']))
		$cid	= (int)$_GET['cid'];
	else
		$cid	= 0;

	$current_category	= '';

	// Build Breadcrumb-Navigation (1st part)

	$breadcrumbs	= array();

	$breadcrumbs[]	= array( 'caption'	=> 'Home',
				 'url'		=> site_url()			);

	// get a DownloadHandler-object

	$oDownloads	= new DownloadHandler($config['site_db_type']);

	// If we have a category-id, that category is displayed, otherwise the category-list

	if ($cid > 0)
	{

		// load categories (yeah, even here - we always display them)

		if (downloads_get_categories($cid))
		{

			// Only if the categories could be loaded we try to load the files

			$files		= $oDownloads->getCategoryFiles($cid);

			if ($files!==false)
			{

				foreach ($files as $key=>$value)
				{

					$files[$key]['size']	= format_filesize($value['size']);

					if ($config['anonymize'])
					{
						$files[$key]['url']	= str_ireplace('%URL%', $files[$key]['url'], $config['anonymize_url']);
					}

				}

				$oSmarty->assign('files',		$files);

			}
			else
			{

				$oSmarty->assign('display_message',	true);

				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array( 'There was a database error while reading the categories.',
										       				  'Error: '.htmlspecialchars($oDownloads->getLastError() ) ) ) );

			}

			// Breadcrumb-Navigation (2nd part)

			$breadcrumbs[]	= array( 'caption'	=> 'Downloads',
						 'url'		=> site_url('downloads')	);

			$breadcrumbs[]	= array( 'caption'	=> $current_category,
						 'url'		=> ''				);


		}

	}
	else
	{

		// load categories

		downloads_get_categories();

		// Breadcrumb-Navigation (2nd part)

		$breadcrumbs[]	= array( 'caption'	=> 'Downloads',
					 'url'		=> ''						);

	}

	$oSmarty->assign('breadcrumbs',		$breadcrumbs);

	// Display Template

	if (!$error)
		$oSmarty->display('file:pages/site_downloads.tpl');

	//

	function downloads_get_categories($cid=0)
	{

		global $oDownloads, $oSmarty, $current_category;

		$categories	= $oDownloads->getCategories();

		if ($categories!==false)
		{

			$current_category	= getCategoryCaption($cid,$categories);

			$oSmarty->assign('categories',		$categories);

			return true;

		}
		else
		{

			$oSmarty->assign('display_message',	true);

			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'There was a database error while reading the categories.',
									       				  'Error: '.htmlspecialchars($oDownloads->getLastError() ) ) ) );

			return false;

		}

	}

	function getCategoryCaption($cid,$categories)
	{
		foreach ($categories as $category)
		{
			if ($category['category_id'] == $cid)
				return $category['category'];
		}
		return 'Unknown';
	}

?>
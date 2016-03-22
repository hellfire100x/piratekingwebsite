<?php

	// ===================================================================================================
	// Package      : pkoSite 
	// File         : inc.admin.downloads.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3       : moved all download-related functions from inc.admin.php to this file
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================
	
	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	/**
	 * Fill Download-List
	 *
	 * @return mixed
	 */

	function admin_downloads_list()
	{

		global $oSmarty, $config;

		// Get Downloads from database

		$oDownloads	= new DownloadHandler($config['site_db_type']);

		$entries	= $oDownloads->getCategoryFiles();

		//

		if ($entries === false)
		{
			// Something went wrong.
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Error reading downloads.',
									       				  'Error: '.htmlspecialchars($oDownloads->getLastError()) ) ) );
		}
		else
		{

			$downloads	= array();

			foreach ($entries as $entry)
			{

				$downloads[]	= array( 'id'		=> (int)$entry['download_id'],
							 'category'	=> htmlspecialchars($entry['category']),
							 'name'		=> htmlspecialchars($entry['name']),
							 'url'		=> $entry['url']				);

			}

			$oSmarty->assign('downloads',	$downloads);

		}

		unset($oDownloads);

	}

	/**
	 * Add Download
	 *
	 * @return mixed
	 */

	function admin_downloads_add()
	{

		global $config, $oSmarty;

		// Load data into some vars.

		$name		= (isset($_POST['dl_name']) ? trim($_POST['dl_name']) : '');
		$description	= (isset($_POST['dl_description']) ? trim($_POST['dl_description']) : '');
		$size		= (isset($_POST['dl_size']) ? (int)($_POST['dl_size']) : 0);
		$url		= (isset($_POST['dl_url']) ? trim($_POST['dl_url']) : '');
		$category	= (isset($_POST['dl_category']) ? (int)($_POST['dl_category']) : 0);

		// Save?

		if (isset($_POST['add']) && ((int)$_POST['add'] == 1))
		{

			// OK, looks like this should be saved.

			$errors		= admin_downloads_form_check($name, $description, $size, $url, $category);

			// If we had no errors, save data - otherwise display form again

			if (count($errors) == 0)
			{

				// Create DownloadHandler-object and update download-entry

				$oDownload	= new DownloadHandler($config['site_db_type']);

				$data		= array( 'category'		=> $category,
							 'name'			=> $name,
							 'description'		=> $description,
							 'size'			=> $size,
							 'url'			=> $url			);

				// Add message to output telling the user if everything was OK

				if ($oDownload->createFile($data)!==false)
				{

					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully added.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'downloads' )).'">here</a> to return to the download-list or continue adding downloads.' ) ) );

					$name		= '';
					$description	= '';
					$size		= 0;
					$url		= '';
					$category	= 0;

				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to add entry.',
											       				  'Error: '.htmlspecialchars($oDownloads->getLastError()) ) ) );

				}

				unset($oDownload);

			}
			else
			{
				// Show errors to user
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> $errors ) );
			}

		}
		else
		{

			// Nothing to do...

		}

		// Assign default-values to the form

		$oSmarty->assign('form_name',			htmlspecialchars($name));
		$oSmarty->assign('form_description',		htmlspecialchars($description));
		$oSmarty->assign('form_size',			htmlspecialchars($size));
		$oSmarty->assign('form_url',			htmlspecialchars($url));
		$oSmarty->assign('form_category_id',		$category);

		// Load categories

		admin_downloads_assign_categories();

	}

	/**
	 * Edit Download
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_downloads_edit($id=-1)
	{

		global $config, $oSmarty;

		// Load data into some vars.

		$name		= (isset($_POST['dl_name']) ? trim($_POST['dl_name']) : '');
		$description	= (isset($_POST['dl_description']) ? trim($_POST['dl_description']) : '');
		$size		= (isset($_POST['dl_size']) ? (int)($_POST['dl_size']) : 0);
		$url		= (isset($_POST['dl_url']) ? trim($_POST['dl_url']) : '');
		$category	= (isset($_POST['dl_category']) ? (int)($_POST['dl_category']) : 0);

		// Save?

		if (isset($_POST['edit']) && ((int)$_POST['edit'] == 1))
		{

			// OK, looks like this should be saved.

			$errors		= admin_downloads_form_check($name, $description, $size, $url, $category, $id);

			// If we had no errors, save data - otherwise display form again

			if (count($errors) == 0)
			{

				// Create DownloadHandler-object and update download-entry

				$oDownload	= new DownloadHandler($config['site_db_type']);

				$data		= array( 'category'		=> $category,
							 'name'			=> $name,
							 'description'		=> $description,
							 'size'			=> $size,
							 'url'			=> $url			);

				// Add message to output telling the user if everything was OK

				if ($oDownload->updateFile($id,$data)!==false)
				{
					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully updated.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'downloads' )).'">here</a> to return to the download-list or continue editing.' ) ) );
				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to update entry.',
											       				  'Error: '.htmlspecialchars($oDownloads->getLastError()) ) ) );

				}

				unset($oDownload);

			}
			else
			{
				// Show errors to user
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> $errors ) );
			}

		}
		else
		{

			// First call, no update wanted

			$oDownloads	= new DownloadHandler($config['site_db_type']);

			$entry		= $oDownloads->getFile($id);

			if ($entry!==false)
			{
					$name		= $entry['name'];
					$description	= $entry['description'];
					$category	= $entry['category_id'];
					$size		= $entry['size'];
					$url		= $entry['url'];
			}
			else
			{
				$oSmarty->assign('skip_form',			true);
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array('Invalid Download-ID.',
										       				 'Click <a href="'.site_url('admin',array( 'sact' => 'downloads' )).'">here</a> to return to the download-list.') ) );

			}

		}

		// Assign default-values to the form

		$oSmarty->assign('form_id',			$id);
		$oSmarty->assign('form_name',			htmlspecialchars($name));
		$oSmarty->assign('form_description',		htmlspecialchars($description));
		$oSmarty->assign('form_size',			htmlspecialchars($size));
		$oSmarty->assign('form_url',			htmlspecialchars($url));
		$oSmarty->assign('form_category_id',		$category);

		// Load categories

		admin_downloads_assign_categories();

	}

	/**
	 * Delete Download
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_downloads_delete($id)
	{

		global $oSmarty, $config;

		$oDownloads	= new DownloadHandler($config['site_db_type']);

		$result		= $oDownloads->deleteFile($id);

		if ($result === false)
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( $oDownloads->getLastError() ) ) );
		else
			$oSmarty->assign('message',			array( 'type'		=> 'ok',
									       'messages'	=> array( 'The Download was successfully removed.' ) ) );

		unset($oDownloads);

	}

	/**
	 * Very simple function to check submitted form-data. Returns an array with all errors
	 * Moved to an own function as we need it for "edit" and "new"
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_downloads_form_check($name, $description, $size, $url, $category, $id = -1)
	{

		// Some basic checks...

		$errors		= array();

		if ($name=='')
			$errors[]	= 'You have\'t entered a name.';

		if ($description=='')
			$errors[]	= 'You have\'t entered a description.';

		if ($size<=0)
			$errors[]	= 'You have\'t entered a file-size.';

		if ($url=='')
			$errors[]	= 'You have\'t entered a download-url.';

		if ($category==0)
			$errors[]	= 'You have\'t selected a category.';

		return $errors;

	}

	/**
	 * Assign download-categories to smarty
	 *
	 * @return mixed
	 */

	function admin_downloads_assign_categories()
	{

		global $config, $oSmarty;

		// Load categories

		$oDownloads	= new DownloadHandler($config['site_db_type']);

		$data		= $oDownloads->getCategories();

		unset($oDownloads);

		// Build arrays

		$category_ids	= array();
		$category_names	= array();

		foreach ($data as $value)
		{
			$category_ids[]			= $value['category_id'];
			$category_names[]		= htmlspecialchars($value['category']);
		}

		$oSmarty->assign('form_category_names',			$category_names);
		$oSmarty->assign('form_category_ids',			$category_ids);

	}

	/**
	 * Fill download-categories list
	 *
	 * @return mixed
	 */

	function admin_categories_list()
	{

		global $oSmarty, $config;

		// Get Downloads from database

		$oDownloads	= new DownloadHandler($config['site_db_type']);

		$entries	= $oDownloads->getCategories();

		//

		if ($entries === false)
		{
			// Something went wrong.
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Error reading downloads.',
									       				  'Error: '.htmlspecialchars($oDownloads->getLastError()) ) ) );
		}
		else
		{

			$categories	= array();

			foreach ($entries as $entry)
			{

				$categories[]	= array( 'id'		=> (int)$entry['category_id'],
							 'category'	=> htmlspecialchars($entry['category'])		);

			}

			$oSmarty->assign('categories',	$categories);

		}

		unset($oDownloads);

	}

	/**
	 * Add download-category
	 *
	 * @return mixed
	 */

	function admin_category_add()
	{

		global $config, $oSmarty;

		// Load data into some vars.

		$category	= (isset($_POST['dl_category']) ? trim($_POST['dl_category']) : '');

		// Save?

		if (isset($_POST['add']) && ((int)$_POST['add'] == 1))
		{

			// OK, looks like this should be saved.

			$errors		= array();

			if ($category=='')
				$errors[]	= 'You have\'t entered a category.';

			// If we had no errors, save data - otherwise display form again

			if (count($errors) == 0)
			{

				// Create DownloadHandler-object and update download-entry

				$oDownload	= new DownloadHandler($config['site_db_type']);

				// Add message to output telling the user if everything was OK

				if ($oDownload->createCategory($category)!==false)
				{

					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully added.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'dlcategories' )).'">here</a> to return to the category-list or continue adding categories.' ) ) );

					$category	= '';

				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to add entry.',
											       				  'Error: '.htmlspecialchars($oDownloads->getLastError()) ) ) );

				}

				unset($oDownload);

			}
			else
			{
				// Show errors to user
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> $errors ) );
			}

		}
		else
		{

			// Nothing to do...

		}

		// Assign default-values to the form

		$oSmarty->assign('form_category',			htmlspecialchars($category));

	}

	/**
	 * Delete Download-category
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_category_delete($id)
	{

		global $oSmarty, $config;

		$oDownloads	= new DownloadHandler($config['site_db_type']);

		$result		= $oDownloads->deleteCategory($id);

		if ($result === false)
		{
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( $oDownloads->getLastError() ) ) );
		}
		else
		{
			$oSmarty->assign('message',			array( 'type'		=> 'ok',
									       'messages'	=> array( 'The category was successfully removed.' ) ) );
		}

		unset($oDownloads);

	}

	/**
	 * Edit Download-category
	 *
	 * @param int $id
	 * @return mixed
	 */

	function admin_category_edit($id)
	{

		global $config, $oSmarty;

		// Load data into some vars.

		$category	= (isset($_POST['dl_category']) ? trim($_POST['dl_category']) : '');

		// Save?

		if (isset($_POST['edit']) && ((int)$_POST['edit'] == 1))
		{

			// OK, looks like this should be saved.

			$errors		= array();

			if ($category=='')
				$errors[]	= 'You have\'t entered a category.';

			// If we had no errors, save data - otherwise display form again

			if (count($errors) == 0)
			{

				// Create DownloadHandler-object and update download-entry

				$oDownload	= new DownloadHandler($config['site_db_type']);

				// Add message to output telling the user if everything was OK

				if ($oDownload->updateCategory($id,$category)!==false)
				{

					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully updated.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'dlcategories' )).'">here</a> to return to the category-list or continue editing this category.' ) ) );

				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to add entry.',
											       				  'Error: '.htmlspecialchars($oDownloads->getLastError()) ) ) );

				}

				unset($oDownload);

			}
			else
			{
				// Show errors to user
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> $errors ) );
			}

		}
		else
		{

			//

			$oDownloads	= new DownloadHandler($config['site_db_type']);

			$entry		= $oDownloads->getCategory($id);

			if ($entry!==false)
			{
				$category		= $entry['category'];
			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array('Category not found.') ) );
			}

		}

		// Assign default-values to the form

		$oSmarty->assign('form_category',			htmlspecialchars($category));
		$oSmarty->assign('form_id',				$id);

	}

?>
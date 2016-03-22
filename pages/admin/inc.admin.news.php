<?php

	// ===================================================================================================
	// Package      : pkoSite 
	// File         : inc.admin.news.php
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
	 * Delete News-Entry
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_news_delete_entry($id)
	{

		global $oSmarty, $config;

		$oNews		= new NewsHandler($config['site_db_type']);

		$result		= $oNews->deleteEntry($id);

		if ($result === false)
		{
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( $oNews->getLastError() ) ) );
		}
		else
		{
			$oSmarty->assign('message',			array( 'type'		=> 'ok',
									       'messages'	=> array( 'The News-entry was successfully removed.' ) ) );
		}

		unset($oNews);

	}

	/**
	 * Fill News-List
	 *
	 * @return mixed
	 */

	function admin_news_display_list()
	{

		global $oSmarty, $config;

		$news		= array();

		$oNews		= new NewsHandler($config['site_db_type']);

		$entries	= $oNews->getLatestNews(0);

		unset($oNews);

		if ($entries === false)
		{

			$error	= true;
			$oSmarty->assign('error',	'Error reading from database.');

		}
		else
		{

			foreach ($entries as $entry)
			{

				$news[]	= array( 'id'		=> (int)$entry['news_id'],
						 'headline'	=> htmlspecialchars($entry['headline']),
						 'author'	=> htmlspecialchars($entry['author']),
						 'category'	=> htmlspecialchars($entry['category']),
						 'date'		=> (int)$entry['date']	);

			}

			$oSmarty->assign('news',	$news);

		}

	}

	/**
	 * Edit News-Entry
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_news_edit($id)
	{

		global $config, $error, $oSmarty;

		// Save?

		if (isset($_POST['sact']))
		{

			// OK, looks like this should be saved...

			$headline	= trim($_POST['news_headline']);
			$body		= trim($_POST['news_body']);
			$date		= trim($_POST['news_date']);
			$author		= (int)($_POST['news_author']);
			$category	= (int)($_POST['news_category']);
			$id		= (int)($_POST['id']);

			// Some basic checks...

			$errors		= array();

			if ($headline=='')
				$errors[]	= 'You have\'t entered a headline.';

			if ($body=='')
				$errors[]	= 'You have\'t entered a news-body.';

			if ($date=='')
				$errors[]	= 'You have\'t entered a date.';
			else
			{
				$datetime	= strtotime($date);
			}

			if ($author<=0)
				$errors[]	= 'You have\'t selected an author.';

			if ($category<=0)
				$errors[]	= 'You have\'t selected a category.';

			if (count($errors) == 0)
			{

				$oNews		= new NewsHandler($config['site_db_type']);

				$data		= array( 'headline'	=> $headline,
							 'body'		=> $body,
							 'author'	=> $author,
							 'category'	=> $category,
							 'date'		=> $datetime	);

				if ($oNews->updateEntry($id,$data)!==false)
				{
					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully updated.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'news' )).'">here</a> to return to the news-list or continue editing.' ) ) );
				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to update entry.' ) ) );

				}

				unset($oNews);

			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> $errors ) );
			}

			$oSmarty->assign('form_id',			$id);
			$oSmarty->assign('form_headline',		htmlspecialchars($headline));
			$oSmarty->assign('form_body',			htmlspecialchars($body));
			$oSmarty->assign('form_date',			strtotime($date));
			$oSmarty->assign('author_id',			$author);
			$oSmarty->assign('form_category_id',		$category);

		}
		else
		{

			//

			$oNews		= new NewsHandler($config['site_db_type']);

			$entry		= $oNews->getEntry($id);

			if ($entry!==1)
			{
				$oSmarty->assign('form_id',			$id);
				$oSmarty->assign('form_headline',		htmlspecialchars($entry['headline']));
				$oSmarty->assign('form_author',			htmlspecialchars($entry['author']));
				$oSmarty->assign('form_body',			htmlspecialchars($entry['body']));
				$oSmarty->assign('form_date',			date('Y-m-d H:i:s',$entry['date']));
				$oSmarty->assign('author_id',			$entry['author_id']);
				$oSmarty->assign('form_category_id',		$entry['category_id']);
			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array('News-Entry not found.') ) );
			}

		}

		// Load authors

		$oNews		= new NewsHandler($config['site_db_type']);

		$data		= $oNews->getAuthors();

		unset($oNews);

		$author_ids	= array();
		$author_names	= array();

		foreach ($data as $value)
		{
			$author_ids[]		= $value['author_id'];
			$author_names[]		= htmlspecialchars($value['author']);
		}

		$oSmarty->assign('author_names',		$author_names);
		$oSmarty->assign('author_ids',			$author_ids);

		//

		admin_news_assign_categories();

	}

	/**
	 * Add News-Entry
	 *
	 * @return mixed
	 */

	function admin_news_add()
	{
		global $config, $error, $oSmarty;

		// Save?

		if (isset($_POST['sact']))
		{

			// OK, looks like this should be saved...

			$headline	= trim($_POST['news_headline']);
			$body		= trim($_POST['news_body']);
			$date		= trim($_POST['news_date']);
			$author		= (int)($_POST['news_author']);
			$category	= (int)($_POST['news_category']);

			// Some basic checks...

			$errors		= array();

			if ($headline=='')
				$errors[]	= 'You have\'t entered a headline.';

			if ($body=='')
				$errors[]	= 'You have\'t entered a news-body.';

			if ($date=='')
				$errors[]	= 'You have\'t entered a date.';
			else
			{
				$datetime	= strtotime($date);
			}

			if ($author<=0)
				$errors[]	= 'You have\'t selected an author.';

			if ($category<=0)
				$errors[]	= 'You have\'t selected a category.';

			if (count($errors) == 0)
			{

				$oNews		= new NewsHandler($config['site_db_type']);

				$data		= array( 'headline'	=> $headline,
							 'body'		=> $body,
							 'author'	=> $author,
							 'category'	=> $category,
							 'date'		=> $datetime	);

				if ($oNews->createEntry($data)!==false)
				{
					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully added.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'news' )).'">here</a> to return to the news-list or continue adding more news.' ) ) );

					$oSmarty->assign('form_date',			date('Y-m-d H:i:s',time()));

				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to add entry.' ) ) );

				}

				unset($oNews);

			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> $errors ) );
			}

		}
		else
		{

			$oSmarty->assign('form_headline',		'');
			$oSmarty->assign('form_body',			'');
			$oSmarty->assign('form_date',			date('Y-m-d H:i:s',time()));
			$oSmarty->assign('author_id',			0);
			$oSmarty->assign('form_category_id',		0);

		}

		// Load authors

		$oNews		= new NewsHandler($config['site_db_type']);

		$data		= $oNews->getAuthors();

		unset($oNews);

		$author_ids	= array();
		$author_names	= array();

		foreach ($data as $value)
		{
			$author_ids[]		= $value['author_id'];
			$author_names[]		= htmlspecialchars($value['author']);
		}

		$oSmarty->assign('author_names',		$author_names);
		$oSmarty->assign('author_ids',			$author_ids);

		//

		admin_news_assign_categories();

	}

	/**
	 * Return list of news-categories
	 *
	 * @return mixed
	 */

	function admin_ncategories_list()
	{

		global $oSmarty, $config;

		// Get News from database

		$oNews		= new NewsHandler($config['site_db_type']);

		$entries	= $oNews->getCategories();

		//

		if ($entries === false)
		{
			// Something went wrong.
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Error reading news-categories.',
									       				  'Error: '.htmlspecialchars($oNews->getLastError()) ) ) );
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

		unset($oNews);

	}

	/**
	 * Add news-category
	 *
	 * @return mixed
	 */

	function admin_ncategory_add()
	{

		global $config, $oSmarty;

		// Load data into some vars.

		$category	= (isset($_POST['n_category']) ? trim($_POST['n_category']) : '');

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

				// Create NewsHandler-object and update news-entry

				$oNews	= new NewsHandler($config['site_db_type']);

				// Add message to output telling the user if everything was OK

				if ($oNews->createCategory($category)!==false)
				{

					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully added.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'ncategories' )).'">here</a> to return to the category-list or continue adding categories.' ) ) );

					$category	= '';

				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to add entry.',
											       				  'Error: '.htmlspecialchars($oNews->getLastError()) ) ) );

				}

				unset($oNews);

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
	 * Delete news-category
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_ncategory_delete($id)
	{

		global $oSmarty, $config;

		$oNews	= new NewsHandler($config['site_db_type']);

		$result		= $oNews->deleteCategory($id);

		if ($result === false)
		{
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( $oNews->getLastError() ) ) );
		}
		else
		{
			$oSmarty->assign('message',			array( 'type'		=> 'ok',
									       'messages'	=> array( 'The category was successfully removed.' ) ) );
		}

		unset($oNews);

	}

	/**
	 * Edit news-category
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_ncategory_edit($id)
	{

		global $config, $oSmarty;

		// Load data into some vars.

		$category	= (isset($_POST['n_category']) ? trim($_POST['n_category']) : '');

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

				// Create NewsHandler-object and update news-entry

				$oNews	= new NewsHandler($config['site_db_type']);

				// Add message to output telling the user if everything was OK

				if ($oNews->updateCategory($id,$category)!==false)
				{

					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully updated.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'ncategories' )).'">here</a> to return to the category-list or continue editing this category.' ) ) );

				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to add entry.',
											       				  'Error: '.htmlspecialchars($oNews->getLastError()) ) ) );

				}

				unset($oNews);

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

			$oNews		= new NewsHandler($config['site_db_type']);

			$entry		= $oNews->getCategory($id);

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

	/**
	 * Add news-author
	 *
	 * @return mixed
	 */

	function admin_author_add()
	{

		global $config, $oSmarty;

		// Load data into some vars.

		$author		= (isset($_POST['na_author']) ? trim($_POST['na_author']) : '');

		// Save?

		if (isset($_POST['add']) && ((int)$_POST['add'] == 1))
		{

			// OK, looks like this should be saved.

			$errors		= array();

			if ($author=='')
				$errors[]	= 'You have\'t entered an author.';

			// If we had no errors, save data - otherwise display form again

			if (count($errors) == 0)
			{

				// Create NewsHandler-object and update news-entry

				$oNews		= new NewsHandler($config['site_db_type']);

				// Add message to output telling the user if everything was OK

				if ($oNews->createAuthor($author)!==false)
				{

					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully added.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'authors' )).'">here</a> to return to the authors-list or continue adding authors.' ) ) );

					$author		= '';

				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to add entry.',
											       				  'Error: '.htmlspecialchars($oNews->getLastError()) ) ) );

				}

				unset($oNews);

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

		$oSmarty->assign('form_author',			htmlspecialchars($author));

	}

	/**
	 * Delete news-author
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_author_delete($id)
	{

		global $oSmarty, $config;

		$oNews	= new NewsHandler($config['site_db_type']);

		$result		= $oNews->deleteAuthor($id);

		if ($result === false)
		{
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( $oNews->getLastError() ) ) );
		}
		else
		{
			$oSmarty->assign('message',			array( 'type'		=> 'ok',
									       'messages'	=> array( 'The author was successfully removed.' ) ) );
		}

		unset($oNews);

	}

	/**
	 * Edit news-author
	 *
	 * @param string $id
	 * @return mixed
	 */

	function admin_author_edit($id)
	{

		global $config, $oSmarty;

		// Load data into some vars.

		$author		= (isset($_POST['na_author']) ? trim($_POST['na_author']) : '');

		// Save?

		if (isset($_POST['edit']) && ((int)$_POST['edit'] == 1))
		{

			// OK, looks like this should be saved.

			$errors		= array();

			if ($author=='')
				$errors[]	= 'You have\'t entered an author.';

			// If we had no errors, save data - otherwise display form again

			if (count($errors) == 0)
			{

				// Create NewsHandler-object and update author-entry

				$oNews	= new NewsHandler($config['site_db_type']);

				// Add message to output telling the user if everything was OK

				if ($oNews->updateAuthor($id,$author)!==false)
				{

					$oSmarty->assign('message',			array( 'type'		=> 'ok',
											       'messages'	=> array( 'The Entry was successfully updated.',
											       				  'Click <a href="'.site_url('admin',array( 'sact' => 'authors' )).'">here</a> to return to the author-list or continue editing this author.' ) ) );

				}
				else
				{

					$oSmarty->assign('message',			array( 'type'		=> 'error',
											       'messages'	=> array( 'Failed to add entry.',
											       				  'Error: '.htmlspecialchars($oNews->getLastError()) ) ) );

				}

				unset($oNews);

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

			$oNews		= new NewsHandler($config['site_db_type']);

			$entry		= $oNews->getAuthor($id);

			if ($entry!==false)
			{
				$author		= $entry['author'];
			}
			else
			{
				$oSmarty->assign('message',			array( 'type'		=> 'error',
										       'messages'	=> array('Author not found.') ) );
			}

		}

		// Assign default-values to the form

		$oSmarty->assign('form_author',			htmlspecialchars($author));
		$oSmarty->assign('form_id',			$id);

	}

	/**
	 * Assign news-category-variables
	 *
	 * @return mixed
	 */

	function admin_news_assign_categories()
	{

		global $config, $oSmarty;

		// Load categories

		$oNews		= new NewsHandler($config['site_db_type']);

		$data		= $oNews->getCategories();

		unset($oNews);

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
	 * Fill news-authors-list
	 *
	 * @return mixed
	 */

	function admin_authors_list()
	{

		global $oSmarty, $config;

		// Get News-Authors from database

		$oNews		= new NewsHandler($config['site_db_type']);

		$entries	= $oNews->getAuthors();

		//

		if ($entries === false)
		{
			// Something went wrong.
			$oSmarty->assign('message',			array( 'type'		=> 'error',
									       'messages'	=> array( 'Error reading authors.',
									       				  'Error: '.htmlspecialchars($oNews->getLastError()) ) ) );
		}
		else
		{

			$authors		= array();

			foreach ($entries as $entry)
			{

				$authors[]	= array( 'id'		=> (int)$entry['author_id'],
							 'author'	=> htmlspecialchars($entry['author'])		);

			}

			$oSmarty->assign('authors',	$authors);

		}

		unset($oNews);

	}

?>
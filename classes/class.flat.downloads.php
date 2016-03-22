<?php

	// ===================================================================================================
	// Package      : pkoSite 
	// File         : class.flat.downloads.php
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
	// = Define constants
	// ===================================================================================================

	define('FAKE_LINE_BREAK',		'°^°');
	define('FAKE_TAB',			'°|°');
	
	define('COLUMN_DOWNLOADS_DOWNLOAD_ID',	0);
	define('COLUMN_DOWNLOADS_CATEGORY_ID',	1);
	define('COLUMN_DOWNLOADS_NAME',		2);
	define('COLUMN_DOWNLOADS_URL',		3);
	define('COLUMN_DOWNLOADS_DESCRIPTION',	4);
	define('COLUMN_DOWNLOADS_SIZE',		5);

	define('COLUMN_CATEGORY_CATEGORY_ID',	0);
	define('COLUMN_CATEGORY_CATEGORY',	1);

	// ===================================================================================================

	class FlatFileDownloadHandler
	{
	
		public function __construct($path)
		{
			$this->db		= new Flatfile();
			$this->db->datadir	= $path;
		}		

		// ===========================================================================================
		// = All functions related to categories
		// ===========================================================================================

		public function getCategories($mode='ext')
		{

			$categories	= array();

			$rows		= $this->db->selectWhere(TABLE_DOWNLOAD_CATEGORIES_FF, NULL, -1, new OrderBy(COLUMN_CATEGORY_CATEGORY, ASCENDING, STRING_COMPARISON));			

			foreach ($rows as $category)
			{
				if ($mode=='ext')
				{
					$categories[]						= array( 'category_id'	=> $category[COLUMN_CATEGORY_CATEGORY_ID],
													 'category'	=> $category[COLUMN_CATEGORY_CATEGORY]	);
				}
				else
				{
					$categories[$category[COLUMN_CATEGORY_CATEGORY_ID]]	= $category[COLUMN_CATEGORY_CATEGORY];
				}
			}

			return $categories;

		}

		public function getCategory($id)
		{
		
			$entry		= $this->db->selectUnique(TABLE_DOWNLOAD_CATEGORIES_FF, COLUMN_CATEGORY_CATEGORY_ID, (int)$id);

			if (count($entry)>0)
			{
	        		$category		= array( 'category_id'		=> $entry[COLUMN_CATEGORY_CATEGORY_ID],
	        						 'category'		=> $entry[COLUMN_CATEGORY_CATEGORY]	);
	                
				return $category;
			}
			else
			{
				$this->error	= 'Record not found.';
				return false;
			}

		}

		public function createCategory($category)
		{

			$data	= array( COLUMN_CATEGORY_CATEGORY	=> $category	);
					 
			$newId	= $this->db->insertWithAutoId(TABLE_DOWNLOAD_CATEGORIES_FF, COLUMN_CATEGORY_CATEGORY_ID, $data);

			return ($newId > 0);

		}

		public function updateCategory($id,$category)
		{

			$data	= array( COLUMN_CATEGORY_CATEGORY	=> $category	);
			
			$this->db->updateSetWhere(TABLE_DOWNLOAD_CATEGORIES_FF, $data, new SimpleWhereClause(COLUMN_CATEGORY_CATEGORY_ID, '=', $id, INTEGER_COMPARISON));

			return true;

		}
		
		public function deleteCategory($id)
		{
			$this->db->deleteWhere(TABLE_DOWNLOAD_CATEGORIES_FF, new SimpleWhereClause(COLUMN_CATEGORY_CATEGORY_ID, '=', $id, INTEGER_COMPARISON));

			return true;

		}

		// ===========================================================================================
		// = All functions related to files
		// ===========================================================================================

		public function getCategoryFiles($cid=-1)
		{

			$downloads	= array();

			// First we must read all categories as Flatfile can't JOIN :-(

			$categories	= $this->getCategories('internal');
			
			// Load Downloads

			if ($cid >= 0)
				$rows	= $this->db->selectWhere(TABLE_DOWNLOADS_FF, 
							   new SimpleWhereClause(COLUMN_DOWNLOADS_CATEGORY_ID, '=', (int)$cid), 
							   -1, 
							   new OrderBy(COLUMN_DOWNLOADS_NAME, ASCENDING, STRING_COMPARISON));
			else
				$rows	= $this->db->selectWhere(TABLE_DOWNLOADS_FF, 
							   NULL, 
							   -1, 
							   new OrderBy(COLUMN_DOWNLOADS_NAME, ASCENDING, STRING_COMPARISON));

			// Create Result-Array
			                
                	foreach ($rows as $entry)
                	{

                		$downloads[]	= array( 'download_id'	=> $entry[COLUMN_DOWNLOADS_DOWNLOAD_ID],
                					 'category_id'	=> $entry[COLUMN_DOWNLOADS_CATEGORY_ID],
                					 'name'		=> $entry[COLUMN_DOWNLOADS_NAME],
                					 'url'		=> $entry[COLUMN_DOWNLOADS_URL],
                					 'description'	=> $this->removeFakeFormats($entry[COLUMN_DOWNLOADS_DESCRIPTION]),
                					 'size'		=> $entry[COLUMN_DOWNLOADS_SIZE],
	                				 'category'	=> (isset($categories[$entry[COLUMN_DOWNLOADS_CATEGORY_ID]]) ? $categories[$entry[COLUMN_DOWNLOADS_CATEGORY_ID]] : 'Unknown')	);
                		
                	}
                
			return $downloads;

		}
		
		public function getFile($id)
		{

			// First we must read all categories as Flatfile can't JOIN :-(

			$categories	= $this->getCategories('internal');

			// Load Download-Entry
		
			$entry		= $this->db->selectUnique(TABLE_DOWNLOADS_FF, COLUMN_DOWNLOADS_DOWNLOAD_ID, (int)$id);

			if (count($entry)>0)
			{

                		$download	= array( 'download_id'	=> $entry[COLUMN_DOWNLOADS_DOWNLOAD_ID],
                					 'category_id'	=> $entry[COLUMN_DOWNLOADS_CATEGORY_ID],
                					 'name'		=> $entry[COLUMN_DOWNLOADS_NAME],
                					 'url'		=> $entry[COLUMN_DOWNLOADS_URL],
                					 'description'	=> $this->removeFakeFormats($entry[COLUMN_DOWNLOADS_DESCRIPTION]),
                					 'size'		=> $entry[COLUMN_DOWNLOADS_SIZE],
	                				 'category'	=> (isset($categories[COLUMN_DOWNLOADS_CATEGORY_ID]) ? $categories[COLUMN_DOWNLOADS_CATEGORY_ID] : 'Unknown')	);
	                
				return $download;

			}
			else
			{
				$this->error	= 'Record not found.';
				return false;
			}
			                
		}
		
		public function createFile($data)
		{

			$data	= array( COLUMN_DOWNLOADS_CATEGORY_ID	=> (int)$data['category'],
					 COLUMN_DOWNLOADS_NAME		=> $data['name'],
					 COLUMN_DOWNLOADS_URL		=> $data['url'],
					 COLUMN_DOWNLOADS_DESCRIPTION	=> $this->addFakeFormats($data['description']),
					 COLUMN_DOWNLOADS_SIZE		=> $data['size']		);
					 
			$newId	= $this->db->insertWithAutoId(TABLE_DOWNLOADS_FF, COLUMN_DOWNLOADS_DOWNLOAD_ID, $data);
			
			return ($newId != 0);

		}

		// Update an entry in the downloads-table
		
		public function updateFile($id,$data)
		{
			
			$data	= array( COLUMN_DOWNLOADS_DOWNLOAD_ID	=> $id,
					 COLUMN_DOWNLOADS_CATEGORY_ID	=> (int)$data['category'],
					 COLUMN_DOWNLOADS_NAME		=> $data['name'],
					 COLUMN_DOWNLOADS_URL		=> $data['url'],
					 COLUMN_DOWNLOADS_DESCRIPTION	=> $this->addFakeFormats($data['description']),
					 COLUMN_DOWNLOADS_SIZE		=> $data['size']		);
			
			$this->db->updateSetWhere(TABLE_DOWNLOADS_FF, $data, new SimpleWhereClause(COLUMN_DOWNLOADS_DOWNLOAD_ID, '=', $id, INTEGER_COMPARISON));

			return true;

		}

		// Delete an entry from the downloads-table
		
		public function deleteFile($id)
		{

			$this->db->deleteWhere(TABLE_DOWNLOADS_FF, new SimpleWhereClause(COLUMN_DOWNLOADS_DOWNLOAD_ID, '=', $id, INTEGER_COMPARISON));

			return true;

		}

		// ===========================================================================================
		// = Other functions
		// ===========================================================================================

		public function getLastError()
		{
			return $this->error;
		}

		// The following 2 functions are needed as the Flatfile-class removes linebreaks and tabs
		// from the data you add to it. 

		private function addFakeFormats($text)
		{
			$text		= str_replace("\n",FAKE_LINE_BREAK,$text);
			$text		= str_replace("\t",FAKE_TAB,$text);
			return $text;
		}

		private function removeFakeFormats($text)
		{
			$text		= str_replace(FAKE_LINE_BREAK,"\n",$text);
			$text		= str_replace(FAKE_TAB,"\t",$text);
			return $text;
		}

	}

?>
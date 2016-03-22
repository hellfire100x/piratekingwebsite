<?php

	// ===================================================================================================
	// Package      : pkoSite 
	// File         : class.flat.news.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.2       : added news-categories
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
	
	define('COLUMN_NEWS_NEWS_ID',		0);
	define('COLUMN_NEWS_AUTHOR_ID',		1);
	define('COLUMN_NEWS_HEADLINE',		2);
	define('COLUMN_NEWS_BODY',		3);
	define('COLUMN_NEWS_DATE',		4);
	define('COLUMN_NEWS_CATEGORY_ID',	5);

	define('COLUMN_AUTHOR_AUTHOR_ID',	0);
	define('COLUMN_AUTHOR_NAME',		1);

	define('COLUMN_CATEGORY_CATEGORY_ID',	0);
	define('COLUMN_CATEGORY_CATEGORY',	1);

	// ===================================================================================================

	class FlatFileNewsHandler
	{
	
		public function __construct($path)
		{
			$this->db		= new Flatfile();
			$this->db->datadir	= $path;
		}		

		// ===========================================================================================
		// = Functions related to news-entries
		// ===========================================================================================

		public function getLatestNews($cnt,$cid=0)
		{

			$news		= array();

			// First we must read all authors and categories as Flatfile can't JOIN :-(

			$authors	= $this->getAuthors('internal');
			$categories	= $this->getCategories('internal');
			
			// Load News-Entries

			if ($cid==0)
			{
				if ($cnt > 0)		
					$rows		= $this->db->selectWhere(TABLE_NEWS_FF, NULL, $cnt, new OrderBy(COLUMN_NEWS_DATE, DESCENDING, INTEGER_COMPARISON));
				else
					$rows		= $this->db->selectAll(TABLE_NEWS_FF, NULL, -1, new OrderBy(COLUMN_NEWS_DATE, DESCENDING, INTEGER_COMPARISON));
			}
			else
			{
				if ($cnt > 0)		
					$rows		= $this->db->selectWhere(TABLE_NEWS_FF, new SimpleWhereClause(COLUMN_NEWS_CATEGORY_ID, '=', $cid, INTEGER_COMPARISON), $cnt, new OrderBy(COLUMN_NEWS_DATE, DESCENDING, INTEGER_COMPARISON));
				else
					$rows		= $this->db->selectAll(TABLE_NEWS_FF, new SimpleWhereClause(COLUMN_NEWS_CATEGORY_ID, '=', $cid, INTEGER_COMPARISON), -1, new OrderBy(COLUMN_NEWS_DATE, DESCENDING, INTEGER_COMPARISON));
			}
                
                	foreach ($rows as $entry)
                	{

                		$news[]	= array( 'news_id'	=> $entry[COLUMN_NEWS_NEWS_ID],
                				 'author_id'	=> $entry[COLUMN_NEWS_AUTHOR_ID],
                				 'headline'	=> $entry[COLUMN_NEWS_HEADLINE],
                				 'body'		=> $this->removeFakeFormats($entry[COLUMN_NEWS_BODY]),
                				 'date'		=> $entry[COLUMN_NEWS_DATE],
                				 'author'	=> (isset($authors[$entry[COLUMN_NEWS_AUTHOR_ID]]) ? $authors[$entry[COLUMN_NEWS_AUTHOR_ID]] : 'Unknown'),
                				 'category_id'	=> $entry[COLUMN_NEWS_CATEGORY_ID],
                				 'category'	=> (isset($categories[$entry[COLUMN_NEWS_CATEGORY_ID]]) ? $categories[$entry[COLUMN_NEWS_CATEGORY_ID]] : 'Unknown')	);
                		
                	}
                
			return $news;

		}
		
		public function getEntry($id)
		{

			// First we must read all authors and categories as Flatfile can't JOIN :-(

			$authors	= $this->getAuthors('internal');
			$categories	= $this->getCategories('internal');

			// Load News-Entry
		
			$entry		= $this->db->selectUnique(TABLE_NEWS_FF, COLUMN_NEWS_NEWS_ID, (int)$id);

			if (count($entry)>0)
			{
	        		$news		= array( 'news_id'	=> $entry[COLUMN_NEWS_NEWS_ID],
	        					 'author_id'	=> $entry[COLUMN_NEWS_AUTHOR_ID],
		        				 'headline'	=> $entry[COLUMN_NEWS_HEADLINE],
	        					 'body'		=> $this->removeFakeFormats($entry[COLUMN_NEWS_BODY]),
	        					 'date'		=> $entry[COLUMN_NEWS_DATE],
	                				 'author'	=> (isset($authors[$entry[COLUMN_NEWS_AUTHOR_ID]]) ? $authors[$entry[COLUMN_NEWS_AUTHOR_ID]] : 'Unknown'),
	                				 'category_id'	=> $entry[COLUMN_NEWS_CATEGORY_ID],
	                				 'category'	=> (isset($categories[$entry[COLUMN_NEWS_CATEGORY_ID]]) ? $categories[$entry[COLUMN_NEWS_CATEGORY_ID]] : 'Unknown')	);
	                
				return $news;
			}
			else
			{
				$this->error	= 'Record not found.';
				return false;
			}
			                
		}
		
		// Update an entry in the news-table
		
		public function updateEntry($id,$data)
		{
			
			$entry		= array( COLUMN_NEWS_NEWS_ID		=> $id,
						 COLUMN_NEWS_AUTHOR_ID		=> $data['author'],
						 COLUMN_NEWS_HEADLINE		=> $data['headline'],
						 COLUMN_NEWS_BODY		=> $this->addFakeFormats($data['body']),
						 COLUMN_NEWS_DATE		=> $data['date'],
						 COLUMN_NEWS_CATEGORY_ID	=> $data['category']		);
			
			$this->db->updateSetWhere(TABLE_NEWS_FF, $entry, new SimpleWhereClause(COLUMN_NEWS_NEWS_ID, '=', $id, INTEGER_COMPARISON));

			return true;

		}

		// Delete an entry from the news-table
		
		public function deleteEntry($id)
		{

			$this->db->deleteWhere(TABLE_NEWS_FF, new SimpleWhereClause(COLUMN_NEWS_NEWS_ID, '=', $id, INTEGER_COMPARISON));

			return true;

		}

		// Create a new entry 

		public function createEntry($data)
		{

			$data	= array( COLUMN_NEWS_AUTHOR_ID		=> (int)$data['author'],
					 COLUMN_NEWS_HEADLINE		=> $data['headline'],
					 COLUMN_NEWS_BODY		=> $this->addFakeFormats($data['body']),
					 COLUMN_NEWS_DATE		=> $data['date'],
					 COLUMN_NEWS_CATEGORY_ID	=> $data['category']		);
					 
			$newId	= $this->db->insertWithAutoId(TABLE_NEWS_FF, COLUMN_NEWS_NEWS_ID, $data);

			return ($newId > 0);

		}

		// ===========================================================================================
		// = Functions related to authors
		// ===========================================================================================

		public function getAuthors($mode='ext')
		{

			$authors	= array();

			$rows		= $this->db->selectAll(TABLE_AUTHORS_FF);
			
			foreach ($rows as $author)
			{
				if ($mode=='ext')
				{
					$authors[]					= array( 'author_id'	=> $author[COLUMN_AUTHOR_AUTHOR_ID],
												 'author'	=> $author[COLUMN_AUTHOR_NAME]	);
				}
				else
				{
					$authors[$author[COLUMN_AUTHOR_AUTHOR_ID]]	= $author[COLUMN_AUTHOR_NAME];
				}
			}

			return $authors;

		}

		public function getAuthor($id)
		{
		
			$entry		= $this->db->selectUnique(TABLE_AUTHORS_FF, COLUMN_AUTHOR_AUTHOR_ID, (int)$id);

			if (count($entry)>0)
			{
	        		$author		= array( 'author_id'		=> $entry[COLUMN_AUTHOR_AUTHOR_ID],
	        					 'author'		=> $entry[COLUMN_AUTHOR_NAME]	);
	                
				return $author;
			}
			else
			{
				$this->error	= 'Record not found.';
				return false;
			}

		}

		public function createAuthor($author)
		{

			$data	= array( COLUMN_AUTHOR_NAME	=> $author	);
					 
			$newId	= $this->db->insertWithAutoId(TABLE_AUTHORS_FF, COLUMN_AUTHOR_AUTHOR_ID, $data);

			return ($newId > 0);

		}

		public function updateAuthor($id,$author)
		{

			$data	= array( COLUMN_AUTHOR_NAME	=> $author	);
			
			$this->db->updateSetWhere(TABLE_AUTHORS_FF, $data, new SimpleWhereClause(COLUMN_AUTHOR_AUTHOR_ID, '=', $id, INTEGER_COMPARISON));

			return true;

		}
		
		public function deleteAuthor($id)
		{

			$this->db->deleteWhere(TABLE_AUTHORS_FF, new SimpleWhereClause(COLUMN_AUTHOR_AUTHOR_ID, '=', $id, INTEGER_COMPARISON));

			return true;

		}

		// ===========================================================================================
		// = All functions related to categories
		// ===========================================================================================

		public function getCategories($mode='ext')
		{

			$categories	= array();

			$rows		= $this->db->selectWhere(TABLE_NEWS_CATEGORIES_FF, NULL, -1, new OrderBy(COLUMN_CATEGORY_CATEGORY, ASCENDING, STRING_COMPARISON));

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
		
			$entry		= $this->db->selectUnique(TABLE_NEWS_CATEGORIES_FF, COLUMN_CATEGORY_CATEGORY_ID, (int)$id);

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
					 
			$newId	= $this->db->insertWithAutoId(TABLE_NEWS_CATEGORIES_FF, COLUMN_CATEGORY_CATEGORY_ID, $data);

			return ($newId > 0);

		}

		public function updateCategory($id,$category)
		{

			$data	= array( COLUMN_CATEGORY_CATEGORY	=> $category	);
			
			$this->db->updateSetWhere(TABLE_NEWS_CATEGORIES_FF, $data, new SimpleWhereClause(COLUMN_CATEGORY_CATEGORY_ID, '=', $id, INTEGER_COMPARISON));

			return true;

		}
		
		public function deleteCategory($id)
		{
			$this->db->deleteWhere(TABLE_NEWS_CATEGORIES_FF, new SimpleWhereClause(COLUMN_CATEGORY_CATEGORY_ID, '=', $id, INTEGER_COMPARISON));

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
<?php

	// ===================================================================================================
	// Package      : pkoSite 
	// File         : class.mysql.news.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3       : fixed wrong function names (last_error, etc.)
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

	class MySQLNewsHandler
	{
	
		public function __construct($connection)
		{

			$this->db	= mysql_connect($connection['host'], $connection['user'], $connection['password']);

			if (!$this->db)
			{
				die('Could not connect to database: ' . mysql_error($this->db));
			}

			$db_selected	= mysql_select_db($connection['database'], $this->db);
			if (!$db_selected)
			{
				die ('Can\'t use database : ' . mysql_error($this->db));
			}			

		}		

		// ===========================================================================================
		// = Functions related to news-entries
		// ===========================================================================================

		public function getLatestNews($cnt,$cid)
		{

			// If a category was passed, take it into account

			if ((int)$cid <> 0)
				$where		= 'WHERE n.CATEGORY_ID = '.(int)$cid;
			else
				$where		= '';

			// Query database

			if ($cnt>0)
				$query		= @mysql_query('SELECT n.NEWS_ID, n.date, n.headline, n.body, a.author, nc.category_id, nc.category FROM '.TABLE_NEWS.' AS n LEFT JOIN '.TABLE_AUTHORS.' AS a ON (n.author_id = a.author_id) LEFT JOIN '.TABLE_NEWS_CATEGORIES.' AS nc ON (n.category_id = nc.category_id) '.$where.' ORDER BY n.date DESC LIMIT '.$cnt, $this->db);
			else
				$query		= @mysql_query('SELECT n.NEWS_ID, n.date, n.headline, n.body, a.author, nc.category_id, nc.category FROM '.TABLE_NEWS.' AS n LEFT JOIN '.TABLE_AUTHORS.' AS a ON (n.author_id = a.author_id) LEFT JOIN '.TABLE_NEWS_CATEGORIES.' AS nc ON (n.category_id = nc.category_id) '.$where.' ORDER BY n.date DESC', $this->db);

			//
	
			if ($query === false)
			{
				$this->error	= mysql_error($this->db);
				return false;
			}
			else
			{
				$result		= array();
				
				if (mysql_num_rows($query) > 0)
				{

					while ($row = mysql_fetch_assoc($query))
					{
						$result[]		= array_change_key_case($row,CASE_LOWER);
					}

				}

				return $result;

			}

		}
		
		public function getEntry($id)
		{

			$query		= @mysql_query('SELECT n.NEWS_ID, n.date, n.headline, n.body, a.author_id, a.author, nc.category_id, nc.category FROM '.TABLE_NEWS.' AS n LEFT JOIN '.TABLE_AUTHORS.' AS a ON (n.author_id = a.author_id) LEFT JOIN '.TABLE_NEWS_CATEGORIES.' AS nc ON (n.category_id = nc.category_id) WHERE n.NEWS_ID = '.$id, $this->db);
	
			if ($query === false)
			{
				$this->error	= mysql_error($this->db);
				return false;
			}
			else
			{

				if (mysql_num_rows($query) == 1)
				{

					$row		= mysql_fetch_assoc($query);
					$result		= array_change_key_case($row,CASE_LOWER);
		
					return $result;

				}
				else
				{
					$this->error	= 'Record not found.';
					return false;
				}

			}
	
		}
		
		// Update an entry in the news-table
		
		public function updateEntry($id,$data)
		{

			$query		= @mysql_query('UPDATE '.TABLE_NEWS.' SET AUTHOR_ID = '.(int)$data['author'].', CATEGORY_ID = '.(int)$data['category'].', headline = \''.sqlite_escape_string($data['headline']).'\', body = \''.sqlite_escape_string($data['body']).'\', date = \''.(int)$data['date'].'\' WHERE NEWS_ID = '.(int)$id, $this->db);
			
			if ($query === false)
			{
				$this->error	= mysql_error($this->db);
			}
	
			return $query;

		}

		// Delete an entry from the news-table
		
		public function deleteEntry($id)
		{

			$query		= @mysql_query('DELETE FROM '.TABLE_NEWS.' WHERE NEWS_ID = '.(int)$id, $this->db);
			
			if ($query === false)
				$this->error	= mysql_error($this->db);
	
			return $query;

		}

		// Create a new entry 

		public function createEntry($data)
		{

			$query		= @mysql_query('INSERT INTO '.TABLE_NEWS.' (AUTHOR_ID, CATEGORY_ID, headline, body, date) VALUES ('.(int)$data['author'].','.(int)$data['category'].',\''.sqlite_escape_string($data['headline']).'\',\''.sqlite_escape_string($data['body']).'\','.(int)$data['date'].')', $this->db);
			
			if ($query === false)
				$this->error	= mysql_error($this->db);
	
			return $query;
			
		}

		// ===========================================================================================
		// = Functions related to authors
		// ===========================================================================================

		public function getAuthors()
		{

			$query		= @mysql_query('SELECT * FROM '.TABLE_AUTHORS.' ORDER BY author', $this->db);
	
			if ($query === false)
			{
				$this->error	= mysql_error($this->db);
				return false;
			}
			else
			{

				$result		= array();
				
				if (mysql_num_rows($query) > 0)
				{
					while ($row = mysql_fetch_assoc($query))
					{
						$result[]		= array_change_key_case($row,CASE_LOWER);
					}
				}

				return $result;

			}

		}

		public function getAuthor($id)
		{

			$query		= @mysql_query('SELECT * FROM '.TABLE_AUTHORS.' WHERE AUTHOR_ID = '.(int)$id, $this->db);
	
			if ($query === false)
			{
				$this->error	= mysql_error($this->db);
				return false;
			}
			else
			{

				$result			= array();
				
				if (mysql_num_rows($query) == 1)
				{
					$row		= mysql_fetch_assoc($query);
					$result		= array_change_key_case($row,CASE_LOWER);
				}
				else
				{
					$this->error	= 'Record not found.';
					return false;
				}

				return $result;

			}

		}

		public function createAuthor($author)
		{

			$query		= @mysql_query('INSERT INTO '.TABLE_AUTHORS.' (author) VALUES (\''.mysql_real_escape_string($author).'\')', $this->db);
			
			if ($query === false)
				$this->error	= mysql_error($this->db);
	
			return $query;

		}

		public function updateAuthor($id,$author)
		{

			$query		= @mysql_query('UPDATE '.TABLE_AUTHORS.' SET author = \''.mysql_real_escape_string($author).'\' WHERE AUTHOR_ID = '.(int)$id, $this->db);
			
			if ($query === false)
				$this->error	= mysql_error($this->db);
	
			return $query;

		}
		
		public function deleteAuthor($id)
		{

			$query		= @mysql_query('DELETE FROM '.TABLE_AUTHORS.' WHERE AUTHOR_ID = '.(int)$id, $this->db);
			
			if ($query === false)
				$this->error	= mysql_error($this->db);
	
			return $query;

		}

		// ===========================================================================================
		// = All functions related to categories
		// ===========================================================================================

		public function getCategories()
		{

			$query		= @mysql_query('SELECT CATEGORY_ID, category FROM '.TABLE_NEWS_CATEGORIES.' ORDER BY category', $this->db);
	
			if ($query === false)
			{
				$this->error	= mysql_error($this->db);
				return false;
			}
			else
			{
				$result		= array();
				
				while ($row = mysql_fetch_assoc($query))
				{
					$result[]			= array_change_key_case($row,CASE_LOWER);
				}

				return $result;

			}

		}
		
		public function getCategory($id)
		{

			$query		= @mysql_query('SELECT * FROM '.TABLE_NEWS_CATEGORIES.' WHERE CATEGORY_ID = '.(int)$id, $this->db);

			if ($query === false)
			{
				$this->error	= mysql_error($this->db);
				return false;
			}
			else
			{
				$result		= array();
				
				if (mysql_num_rows($query) == 1)
				{
					$row		= mysql_fetch_assoc($query);
					$result		= array_change_key_case($row,CASE_LOWER);
				}
				else
				{
					$this->error	= 'Record not found.';
					return false;
				}

				return $result;

			}

		}

		public function createCategory($category)
		{

			$query		= @mysql_query('INSERT INTO '.TABLE_NEWS_CATEGORIES.' (category) VALUES (\''.mysql_real_escape_string($category).'\')', $this->db);
			
			if ($query === false)
				$this->error	= mysql_error($this->db);
	
			return $query;

		}

		public function updateCategory($id,$category)
		{

			$query		= @mysql_query('UPDATE '.TABLE_NEWS_CATEGORIES.' SET category = \''.mysql_real_escape_string($category).'\' WHERE CATEGORY_ID = '.(int)$id, $this->db);
			
			if ($query === false)
				$this->error	= mysql_error($this->db);
	
			return $query;

		}
		
		public function deleteCategory($id)
		{

			$query		= @mysql_query('DELETE FROM '.TABLE_NEWS_CATEGORIES.' WHERE CATEGORY_ID = '.(int)$id, $this->db);
			
			if ($query === false)
				$this->error	= mysql_error($this->db);
	
			return $query;

		}

		// ===========================================================================================
		// = Other functions
		// ===========================================================================================

		public function getLastError()
		{
			return $this->error;
		}

	}

?>
<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : class.sqlite.news.php
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

	class SQLiteNewsHandler
	{

		public function __construct($path)
		{
			$this->db	= sqlite_open($path, 0666, $sqliteerror);
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
				$query		= @sqlite_query('SELECT n.NEWS_ID, n.date, n.headline, n.body, a.author, nc.category FROM '.TABLE_NEWS.' AS n LEFT JOIN '.TABLE_AUTHORS.' AS a ON (n.author_id = a.author_id) LEFT JOIN '.TABLE_NEWS_CATEGORIES.' AS nc ON (n.category_id = nc.category_id) '.$where.' ORDER BY n.date DESC LIMIT '.$cnt, $this->db);
			else
				$query		= @sqlite_query('SELECT n.NEWS_ID, n.date, n.headline, n.body, a.author, nc.category FROM '.TABLE_NEWS.' AS n LEFT JOIN '.TABLE_AUTHORS.' AS a ON (n.author_id = a.author_id) LEFT JOIN '.TABLE_NEWS_CATEGORIES.' AS nc ON (n.category_id = nc.category_id) '.$where.' ORDER BY n.date DESC', $this->db);

			// Lets see what we have...

			if ($query === false)
			{

				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{
				$result		= array();

				if (sqlite_num_rows($query) > 0)
				{

					while ($row = sqlite_fetch_array($query, SQLITE_ASSOC))
					{

						$row			= array_change_key_case($row,CASE_LOWER);

						// The following code is needed as SQLite returns "n.news_id" instead
						// of "news_id" when using JOINs

						$result[]		= array( 'news_id'	=> $row['n.news_id'],
										 'author'	=> $row['a.author'],
										 'category'	=> $row['nc.category'],
										 'body'		=> $row['n.body'],
										 'date'		=> $row['n.date'],
										 'headline'	=> $row['n.headline']	);
					}

				}

				return $result;

			}

		}

		public function getEntry($id)
		{

			$query		= @sqlite_query('SELECT n.NEWS_ID, n.date, n.headline, n.body, a.author_id, a.author, nc.category_id, nc.category FROM '.TABLE_NEWS.' AS n LEFT JOIN '.TABLE_AUTHORS.' AS a ON (n.author_id = a.author_id) LEFT JOIN '.TABLE_NEWS_CATEGORIES.' AS nc ON (n.category_id = nc.category_id) WHERE n.NEWS_ID = '.$id, $this->db);

			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{

				if (sqlite_num_rows($query) == 1)
				{

					$row		= sqlite_fetch_array($query, SQLITE_ASSOC);

					$row		= array_change_key_case($row,CASE_LOWER);

					$result		= array( 'news_id'	=> $row['n.news_id'],
								 'author'	=> $row['a.author'],
								 'category'	=> $row['nc.category'],
								 'body'		=> $row['n.body'],
								 'date'		=> (int)$row['n.date'],
								 'headline'	=> $row['n.headline'],
								 'author_id'	=> (int)$row['a.author_id'],
								 'category_id'	=> (int)$row['nc.category_id'] );

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

			$query		= @sqlite_query('UPDATE '.TABLE_NEWS.' SET AUTHOR_ID = '.(int)$data['author'].', CATEGORY_ID = '.(int)$data['category'].', headline = \''.sqlite_escape_string($data['headline']).'\', body = \''.sqlite_escape_string($data['body']).'\', date = \''.(int)$data['date'].'\' WHERE NEWS_ID = '.(int)$id, $this->db);

			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
			}

			return $query;

		}

		// Delete an entry from the news-table

		public function deleteEntry($id)
		{

			$query		= @sqlite_query('DELETE FROM '.TABLE_NEWS.' WHERE NEWS_ID = '.(int)$id, $this->db);

			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));

			return $query;

		}

		// Create a new entry

		public function createEntry($data)
		{

			$query		= @sqlite_query('INSERT INTO '.TABLE_NEWS.' (AUTHOR_ID, CATEGORY_ID, headline, body, date) VALUES ('.(int)$data['author'].','.(int)$data['category'].',\''.sqlite_escape_string($data['headline']).'\',\''.sqlite_escape_string($data['body']).'\','.(int)$data['date'].')', $this->db);

			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));

			return $query;

		}

		// ===========================================================================================
		// = Functions related to authors
		// ===========================================================================================

		public function getAuthors()
		{

			$query		= @sqlite_query('SELECT * FROM '.TABLE_AUTHORS.' ORDER BY author', $this->db);

			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{

				$result		= array();

				if (sqlite_num_rows($query) > 0)
				{
					while ($row = sqlite_fetch_array($query, SQLITE_ASSOC))
					{
						$result[]		= array_change_key_case($row,CASE_LOWER);
					}
				}

				return $result;

			}

		}

		public function getAuthor($id)
		{

			$query		= @sqlite_query('SELECT * FROM '.TABLE_AUTHORS.' WHERE AUTHOR_ID = '.(int)$id, $this->db);

			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{

				$result			= array();

				if (sqlite_num_rows($query) == 1)
				{
					$row		= sqlite_fetch_array($query, SQLITE_ASSOC);
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

			$query		= @sqlite_query('INSERT INTO '.TABLE_AUTHORS.' (author) VALUES (\''.sqlite_escape_string($author).'\')', $this->db);

			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));

			return $query;

		}

		public function updateAuthor($id,$author)
		{

			$query		= @sqlite_query('UPDATE '.TABLE_AUTHORS.' SET author = \''.sqlite_escape_string($author).'\' WHERE AUTHOR_ID = '.(int)$id, $this->db);

			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));

			return $query;

		}

		public function deleteAuthor($id)
		{

			$query		= @sqlite_query('DELETE FROM '.TABLE_AUTHORS.' WHERE AUTHOR_ID = '.(int)$id, $this->db);

			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));

			return $query;

		}

		// ===========================================================================================
		// = Functions related to categories
		// ===========================================================================================

		public function getCategories()
		{

			$query		= @sqlite_query('SELECT * FROM '.TABLE_NEWS_CATEGORIES.' ORDER BY category', $this->db);

			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{

				$result		= array();

				if (sqlite_num_rows($query) > 0)
				{
					while ($row = sqlite_fetch_array($query, SQLITE_ASSOC))
					{
						$result[]		= array_change_key_case($row,CASE_LOWER);
					}
				}

				return $result;

			}

		}

		public function getCategory($id)
		{

			$query		= @sqlite_query('SELECT * FROM '.TABLE_NEWS_CATEGORIES.' WHERE CATEGORY_ID = '.(int)$id, $this->db);

			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{

				$result			= array();

				if (sqlite_num_rows($query) == 1)
				{
					$row		= sqlite_fetch_array($query, SQLITE_ASSOC);
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

			$query		= @sqlite_query('INSERT INTO '.TABLE_NEWS_CATEGORIES.' (category) VALUES (\''.sqlite_escape_string($category).'\')', $this->db);

			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));

			return $query;

		}

		public function updateCategory($id,$category)
		{

			$query		= @sqlite_query('UPDATE '.TABLE_NEWS_CATEGORIES.' SET category = \''.sqlite_escape_string($category).'\' WHERE CATEGORY_ID = '.(int)$id, $this->db);

			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));

			return $query;

		}

		public function deleteCategory($id)
		{

			$query		= @sqlite_query('DELETE FROM '.TABLE_NEWS_CATEGORIES.' WHERE CATEGORY_ID = '.(int)$id, $this->db);

			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));

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
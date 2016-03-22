<?php

	// ===================================================================================================
	// Package      : pkoSite 
	// File         : class.sqlite.downloads.php
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

	class SQLiteDownloadHandler
	{

		private $error	= '';
	
		// ===========================================================================================
		// = Constructor, etc.
		// ===========================================================================================

		public function __construct($path)
		{
			$this->db	= sqlite_open($path, 0666, $sqliteerror);
		}		

		// ===========================================================================================
		// = All functions related to categories
		// ===========================================================================================

		public function getCategories()
		{

			$query		= @sqlite_query('SELECT CATEGORY_ID, category FROM '.TABLE_DOWNLOAD_CATEGORIES.' ORDER BY category', $this->db);
	
			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{
				$result		= array();
				
				while ($row = sqlite_fetch_array($query, SQLITE_ASSOC))
				{
					$result[]			= array_change_key_case($row,CASE_LOWER);
				}

				return $result;

			}

		}
		
		public function getCategory($id)
		{

			$query		= @sqlite_query('SELECT * FROM '.TABLE_DOWNLOAD_CATEGORIES.' WHERE CATEGORY_ID = '.(int)$id, $this->db);

			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{
				$result		= array();
				
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

			$query		= @sqlite_query('INSERT INTO '.TABLE_DOWNLOAD_CATEGORIES.' (category) VALUES (\''.sqlite_escape_string($category).'\')', $this->db);
			
			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
	
			return $query;

		}

		public function updateCategory($id,$category)
		{

			$query		= @sqlite_query('UPDATE '.TABLE_DOWNLOAD_CATEGORIES.' SET category = \''.sqlite_escape_string($category).'\' WHERE CATEGORY_ID = '.(int)$id, $this->db);
			
			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
	
			return $query;

		}
		
		public function deleteCategory($id)
		{

			$query		= @sqlite_query('DELETE FROM '.TABLE_DOWNLOAD_CATEGORIES.' WHERE CATEGORY_ID = '.(int)$id, $this->db);
			
			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
	
			return $query;

		}

		// ===========================================================================================
		// = All functions related to files
		// ===========================================================================================

		public function getCategoryFiles($cid=-1)
		{

			if ($cid >= 0)
				$query		= @sqlite_query('SELECT * FROM '.TABLE_DOWNLOADS.' AS d LEFT JOIN '.TABLE_DOWNLOAD_CATEGORIES.' AS c ON (c.CATEGORY_ID = d.CATEGORY_ID) WHERE d.CATEGORY_ID = '.(int)$cid, $this->db);
			else
				$query		= @sqlite_query('SELECT * FROM '.TABLE_DOWNLOADS.' AS d LEFT JOIN '.TABLE_DOWNLOAD_CATEGORIES.' AS c ON (c.CATEGORY_ID = d.CATEGORY_ID)', $this->db);
	
			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{
				$result		= array();
				
				while ($row = sqlite_fetch_array($query, SQLITE_ASSOC))
				{

					$row			= array_change_key_case($row,CASE_LOWER);

					// The following code is needed as SQLite returns "n.news_id" instead
					// of "news_id" when using JOINs

					$result[]		= array( 'category_id'		=> $row['d.category_id'],
									 'category'		=> $row['c.category'],
									 'download_id'		=> $row['d.download_id'],
									 'name'			=> $row['d.name'],
									 'description'		=> $row['d.description'],
									 'url'			=> $row['d.url'],
									 'size'			=> $row['d.size']		);

				}

				return $result;

			}

		}

		public function getFile($id)
		{

			$query		= @sqlite_query('SELECT * FROM '.TABLE_DOWNLOADS.' AS d LEFT JOIN '.TABLE_DOWNLOAD_CATEGORIES.' AS c ON (c.CATEGORY_ID = d.CATEGORY_ID) WHERE d.DOWNLOAD_ID = '.(int)$id, $this->db);
	
			if ($query === false)
			{
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
				return false;
			}
			else
			{
				
				if (sqlite_num_rows($query) == 1)
				{
				
					$result		= array();
					
					while ($row = sqlite_fetch_array($query, SQLITE_ASSOC))
					{
	
						$row			= array_change_key_case($row,CASE_LOWER);
	
						// The following code is needed as SQLite returns "n.news_id" instead
						// of "news_id" when using JOINs
	
						$result		= array( 'category_id'		=> $row['d.category_id'],
									 'category'		=> $row['c.category'],
									 'download_id'		=> $row['d.download_id'],
									 'name'			=> $row['d.name'],
									 'description'		=> $row['d.description'],
									 'url'			=> $row['d.url'],
									 'size'			=> $row['d.size']		);
	
					}
	
					return $result;

				}
				else
				{
					$this->error	= 'Record not found.';
					return false;
				}

			}

		}

		public function createFile($data)
		{

			$query		= @sqlite_query('INSERT INTO '.TABLE_DOWNLOADS.' (CATEGORY_ID, name, url, description, size) VALUES ('.(int)$data['category'].', \''.sqlite_escape_string($data['name']).'\', \''.sqlite_escape_string($data['url']).'\', \''.sqlite_escape_string($data['description']).'\', '.(int)$data['size'].')', $this->db);
			
			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
	
			return $query;

		}

		public function updateFile($id,$data)
		{

			$query		= @sqlite_query('UPDATE '.TABLE_DOWNLOADS.' SET CATEGORY_ID = '.(int)$data['category'].', name = \''.sqlite_escape_string($data['name']).'\', url = \''.sqlite_escape_string($data['url']).'\', description = \''.sqlite_escape_string($data['description']).'\', size = '.(int)$data['size'].' WHERE DOWNLOAD_ID = '.$id, $this->db);
			
			if ($query === false)
				$this->error	= sqlite_error_string(sqlite_last_error($this->db));
	
			return $query;

		}

		public function deleteFile($id)
		{

			$query		= @sqlite_query('DELETE FROM '.TABLE_DOWNLOADS.' WHERE DOWNLOAD_ID = '.(int)$id, $this->db);
			
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
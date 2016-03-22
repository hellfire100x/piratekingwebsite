<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : class.news.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// Description  : This is just a wrapper for the database-specific classes
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	//                modified constructor to use BASEDIR
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

	class NewsHandler
	{

		public function __construct($mode)
		{

			$mode		= strtolower(trim($mode));

			switch ($mode)
			{

				case 'mysql'	:

					global $config;

					require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'class.mysql.news.php');

					$this->nh	= new MySQLNewsHandler($config['mysql']);

					break;

				case 'sqlite'	:

					require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'class.sqlite.news.php');

					$this->nh	= new SQLiteNewsHandler(BASEDIR.'data'.DIRECTORY_SEPARATOR.'sqlite'.DIRECTORY_SEPARATOR.'pkosite.db2');

					break;

				case 'flat'	:

					require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'class.flat.news.php');

					$this->nh	= new FlatFileNewsHandler('data'.DIRECTORY_SEPARATOR.'flatfile'.DIRECTORY_SEPARATOR);

					break;

			}

		}

		// ===========================================================================================
		// = All functions related to news-articles
		// ===========================================================================================

		public function getLatestNews($cnt,$cid=0)
		{
			return $this->nh->getLatestNews($cnt,$cid);
		}

		public function getEntry($id)
		{
			return $this->nh->getEntry($id);
		}

		public function updateEntry($id,$data)
		{
			return $this->nh->updateEntry($id,$data);
		}

		public function deleteEntry($id)
		{
			return $this->nh->deleteEntry($id);
		}

		public function createEntry($id)
		{
			return $this->nh->createEntry($id);
		}

		// ===========================================================================================
		// = All functions related to news-authors
		// ===========================================================================================

		public function getAuthors()
		{
			return $this->nh->getAuthors();
		}

		public function getAuthor($id)
		{
			return $this->nh->getAuthor($id);
		}

		public function createAuthor($author)
		{
			return $this->nh->createAuthor($author);
		}

		public function updateAuthor($id,$author)
		{
			return $this->nh->updateAuthor($id,$author);
		}

		public function deleteAuthor($id)
		{
			return $this->nh->deleteAuthor($id);
		}

		// ===========================================================================================
		// = All functions related to categories
		// ===========================================================================================

		public function getCategories()
		{
			return $this->nh->getCategories();
		}

		public function getCategory($id)
		{
			return $this->nh->getCategory($id);
		}

		public function createCategory($category)
		{
			return $this->nh->createCategory($category);
		}

		public function updateCategory($id,$category)
		{
			return $this->nh->updateCategory($id,$category);
		}

		public function deleteCategory($id)
		{
			return $this->nh->deleteCategory($id);
		}

		// ===========================================================================================
		// = All other functions
		// ===========================================================================================

		public function getLastError()
		{
			return $this->nh->getLastError();
		}

	}

?>
<?php

	// ===================================================================================================
	// Package      : pkoSite 
	// File         : class.downloads.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	//                modified constructor to use BASEDIR
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================
	
	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	class DownloadHandler
	{
	
		public function __construct($mode)
		{

			$mode		= strtolower(trim($mode));
			
			switch ($mode)
			{

				case 'mysql'	:

					global $config;

					require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'class.mysql.downloads.php');
		
					$this->dh	= new MySQLDownloadHandler($config['mysql']);
					
					break;				

				case 'sqlite'	:

					require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'class.sqlite.downloads.php');
		
					$this->dh	= new SQLiteDownloadHandler(BASEDIR.'data'.DIRECTORY_SEPARATOR.'sqlite'.DIRECTORY_SEPARATOR.'pkosite.db2');
					
					break;				
				
				case 'flat'	:

					require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'class.flat.downloads.php');
		
					$this->dh	= new FlatFileDownloadHandler('data'.DIRECTORY_SEPARATOR.'flatfile'.DIRECTORY_SEPARATOR);
					
					break;				

			}			
			
		}		

		// ===========================================================================================
		// = All functions related to categories
		// ===========================================================================================

		public function getCategories()
		{
			return $this->dh->getCategories();
		}

		public function getCategory($id)
		{
			return $this->dh->getCategory($id);
		}
		
		public function createCategory($category)
		{
			return $this->dh->createCategory($category);
		}

		public function updateCategory($id,$category)
		{
			return $this->dh->updateCategory($id,$category);
		}
		
		public function deleteCategory($id)
		{
			return $this->dh->deleteCategory($id);
		}

		// ===========================================================================================
		// = All functions related to files
		// ===========================================================================================

		public function getCategoryFiles($cid=-1)
		{
			return $this->dh->getCategoryFiles($cid);
		}

		public function getFile($id)
		{
			return $this->dh->getFile($id);
		}

		public function createFile($data)
		{
			return $this->dh->createFile($data);
		}

		public function updateFile($id,$data)
		{
			return $this->dh->updateFile($id,$data);
		}

		public function deleteFile($id)
		{
			return $this->dh->deleteFile($id);
		}

		// ===========================================================================================
		// = All other functions
		// ===========================================================================================

		public function getLastError()
		{
			return $this->dh->getLastError();
		}

	}

?>
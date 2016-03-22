<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.graph.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3b      : some minor changes regarding filenames
	// v0.0.2       : added basic function-headers to all functions, cleaned up some code, added
	//		  new graphs
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================

	require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'pchart'.DIRECTORY_SEPARATOR.'pData.class');
	require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'pchart'.DIRECTORY_SEPARATOR.'pChart.class');

	// ===================================================================================================

	if (checkReferer())
	{

		//

		if ((!isset($_GET['sact'])) && (!isset($_POST['sact'])))
			$subaction		= '';
		else
			$subaction		= ((isset($_POST['sact'])) ? $_POST['sact'] : $_GET['sact']);

		$subaction		= strtolower(trim(preg_replace('/[^0-9a-z]/i','',$subaction)));

		// Handle subaction

		switch ($subaction)
		{

			case 'firstjobs'	:

				// Job Distribution as Pie
				stats_jobs_first();
				break;

			case 'secondjobs'	:

				// Job Distribution as Pie
				stats_jobs_second();
				break;

			case 'onlinetoday'	:

				if (isLoggedIn() && isAdmin() && !isBanned())
					stats_online(time());
				else
					errorImage('Permission denied.',300,20);

				break;

			case 'onlineyesterday'	:

				if (isLoggedIn() && isAdmin() && !isBanned())
					stats_online(time()-86400);
				else
					errorImage('Permission denied.',300,20);

				break;

			default:

				errorImage('Unknown statistics.',300,20);

		}

	}
	else
		errorImage('Invalid referer.',300,20);

	/**
	 * Outputs a pie-chart with the job-distribution of the first level jobs
	 *
	 * @return mixed
	 */

	function stats_jobs_first()
	{

		global $config;

  		// Configure chart

  		$image_width	= 480;
  		$image_height	= 310;

		$font_file	= 'classes'.DIRECTORY_SEPARATOR.'pchart'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'tahoma.ttf';

		// Query DB

		if ($config['is_gm_server'])
			$query		= 'SELECT c.job, COUNT(*) AS rcount FROM '.TABLE_CHARACTER.' AS c WHERE (c.delflag = 0) GROUP BY c.job';
		else
			$query		= 'SELECT c.job, COUNT(*) AS rcount FROM '.TABLE_CHARACTER.' AS c LEFT JOIN '.TABLE_ACCOUNT.' AS a ON (a.act_id = c.act_id) WHERE ((c.delflag = 0) AND (a.gm = 0)) GROUP BY c.job';

	        $result		= array( 'explorer'	=> 0,
	        			 'herbalist'	=> 0,
	        			 'hunter'	=> 0,
	        			 'newbie'	=> 0,
	        			 'swordsman'	=> 0	);

	        $qresult	= doQuery($query, DATABASE_GAME);

	        if ($qresult !== false)
	        {

	        	while ($row = mssql_fetch_assoc($qresult))
	        	{
	        		$result[trim(strtolower(str_replace(' ','',getJobName($row['job']))))]	= (int)$row['rcount'];
	        	}

		}
		else
			errorImage('Database Error',$image_width,$image_height);

		// Dataset definition
		$oDataSet	= new pData;
		$oDataSet->AddPoint(array( $result['explorer'], $result['herbalist'], $result['hunter'], $result['newbie'], $result['swordsman'] ),"Serie1");
		$oDataSet->AddPoint(array( 'Explorer', 'Herbalist', 'Hunter', 'Newbie', 'Swordsman' ), "Serie2");
		$oDataSet->AddAllSeries();
		$oDataSet->SetAbsciseLabelSerie("Serie2");

		// Initialise the graph
		$oGraph		= new pChart($image_width,$image_height);
		$oGraph->drawFilledRoundedRectangle(7,7,$image_width-7,$image_height-7,5,240,240,240);
		$oGraph->drawRoundedRectangle(5,5,$image_width-5,$image_height-5,5,230,230,230);

		// This will draw a shadow under the pie chart
		$oGraph->drawFilledCircle(20+(int)($image_height / 2)+8,(int)($image_height / 2)+8,(int)($image_height / 2)-30,200,200,200);

		// Draw the pie chart
		$oGraph->setFontProperties($font_file,8);
		$oGraph->drawBasicPieGraph($oDataSet->GetData(),$oDataSet->GetDataDescription(),20+(int)($image_height / 2),(int)($image_height / 2),(int)($image_height / 2)-30,PIE_PERCENTAGE,255,255,218);
		$oGraph->drawPieLegend($image_width-120,15,$oDataSet->GetData(),$oDataSet->GetDataDescription(),250,250,250);
		$oGraph->Stroke();

		exit();

 	}

	/**
	 * Outputs a pie-chart with the job-distribution of the second level jobs
	 *
	 * @return mixed
	 */

	function stats_jobs_second()
	{

		global $config;

  		// Configure chart

  		$image_width	= 480;
  		$image_height	= 310;

		$font_file	= 'classes'.DIRECTORY_SEPARATOR.'pchart'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'tahoma.ttf';

		// Query DB

		if ($config['is_gm_server'])
			$query		= 'SELECT c.job, COUNT(*) AS rcount FROM '.TABLE_CHARACTER.' AS c WHERE (c.delflag = 0) GROUP BY c.job';
		else
			$query		= 'SELECT c.job, COUNT(*) AS rcount FROM '.TABLE_CHARACTER.' AS c LEFT JOIN '.TABLE_ACCOUNT.' AS a ON (a.act_id = c.act_id) WHERE ((c.delflag = 0) AND (a.gm = 0)) GROUP BY c.job';

	        $result		= array( 'champion'	=> 0,
	        			 'cleric'	=> 0,
	        			 'crusader'	=> 0,
	        			 'sealmaster'	=> 0,
	        			 'sharpshooter'	=> 0,
	        			 'voyager'	=> 0	);

	        $qresult	= doQuery($query, DATABASE_GAME);

	        if ($qresult !== false)
	        {

	        	while ($row = mssql_fetch_assoc($qresult))
	        	{
	        		$result[trim(strtolower(str_replace(' ','',getJobName($row['job']))))]	= (int)$row['rcount'];
	        	}

		}
		else
			errorImage('Database Error',$image_width,$image_height);

		// Dataset definition
		$oDataSet	= new pData;
		$oDataSet->AddPoint(array( $result['champion'], $result['cleric'], $result['crusader'], $result['sealmaster'], $result['sharpshooter'], $result['voyager'] ),"Serie1");
		$oDataSet->AddPoint(array( 'Champion', 'Cleric', 'Crusader', 'Seal Master', 'Sharpshooter', 'Voyager' ), "Serie2");
		$oDataSet->AddAllSeries();
		$oDataSet->SetAbsciseLabelSerie("Serie2");

		// Initialise the graph
		$oGraph		= new pChart($image_width,$image_height);
		$oGraph->drawFilledRoundedRectangle(7,7,$image_width-7,$image_height-7,5,240,240,240);
		$oGraph->drawRoundedRectangle(5,5,$image_width-5,$image_height-5,5,230,230,230);

		// This will draw a shadow under the pie chart
		$oGraph->drawFilledCircle(20+(int)($image_height / 2)+8,(int)($image_height / 2)+8,(int)($image_height / 2)-30,200,200,200);

		// Draw the pie chart
		$oGraph->setFontProperties($font_file,8);
		$oGraph->drawBasicPieGraph($oDataSet->GetData(),$oDataSet->GetDataDescription(),20+(int)($image_height / 2),(int)($image_height / 2),(int)($image_height / 2)-30,PIE_PERCENTAGE,255,255,218);
		$oGraph->drawPieLegend($image_width-120,15,$oDataSet->GetData(),$oDataSet->GetDataDescription(),250,250,250);
		$oGraph->Stroke();

		exit();

	}

	/**
	 * Outputs a cubic-line-chart with the users online for a given date.
	 *
	 * @param $date
	 * @return mixed
	 */

	function stats_online($date)
	{

  		// Configure chart

  		$image_width	= 480;
  		$image_height	= 310;

		$font_file	= 'classes'.DIRECTORY_SEPARATOR.'pchart'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'tahoma.ttf';

		// Create Datasets

		$day	= date('j',$date);
		$month	= date('n',$date);
		$year	= date('Y',$date);

		// load the number of users online

		$sqlquery	= '	SELECT
					        DAY(track_date) AS log_day,
					        MONTH(track_date) AS log_month,
					        YEAR(track_date) AS log_year,
					        DATEPART(hh,track_date) AS log_hour,
					        AVG(login_num) AS log_avg_login,
					        AVG(play_num) AS log_avg_play,
					        AVG(wgplay_num) AS log_avg_wgplay
					FROM
					        '.TABLE_STAT.'
					WHERE
					        (DAY(track_date) = '.$day.') AND (MONTH(track_date) = '.$month.') AND (YEAR(track_date) = '.$year.')
					GROUP BY
					        DAY(track_date), MONTH(track_date), YEAR(track_date), DATEPART(hh,track_date)
					ORDER BY
					        YEAR(track_date), MONTH(track_date), DAY(track_date)';

		$online		= doQuery($sqlquery, DATABASE_GAME);

		if ($online !== false)
		{

			$data		= array();

			// Initialize array with 0

			for ($i=0;$i<=23;$i++)
			{
				$data['login'][$i]	= 0;
				$data['playing'][$i]	= 0;
			}

			// Assign real data to array

			if ($online !== false)
			{
				while ($row = mssql_fetch_assoc($online))
				{
					// For some strange reason the SQL-query could return negative values - those will be ignored!
					$data['login'][(int)$row['log_hour']]		= (((int)$row['log_avg_login'] < 0) ? 0 : (int)$row['log_avg_login']);
					$data['playing'][(int)$row['log_hour']]		= (((int)$row['log_avg_play'] < 0) ? 0 : (int)$row['log_avg_play']);
				}
			}

			//  Assign data to Dataset

			$oDataSet	= new pData;

			$oDataSet->AddPoint($data['login'],"S1");
			$oDataSet->AddPoint($data['playing'],"S2");
			$oDataSet->AddAllSeries();
			$oDataSet->SetAbsciseLabelSerie();

			// Initialise the graph

			$oChart		= new pChart($image_width,$image_height);

			$oChart->setFontProperties($font_file,8);
			$oChart->setGraphArea(50,17,$image_width-15,$image_height-30);
			$oChart->drawFilledRoundedRectangle(7,7,$image_width-7,$image_height-7,5,240,240,240);
			$oChart->drawRoundedRectangle(5,5,$image_width-5,$image_height-5,5,230,230,230);
			$oChart->drawGraphArea(255,255,255,TRUE);
			$oChart->drawScale($oDataSet->GetData(),$oDataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,0,TRUE);
			$oChart->drawGrid(4,TRUE,230,230,230,50);

			// Draw the cubic curve graph
			$oChart->drawCubicCurve($oDataSet->GetData(),$oDataSet->GetDataDescription());

			// Output
			$oChart->Stroke();

		}
		else
			errorImage('Database Error',$image_width,$image_height);

		exit();

	}

?>
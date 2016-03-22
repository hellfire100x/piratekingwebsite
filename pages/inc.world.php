<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.world.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3b      : some minor changes regarding filenames
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================
	// Load Map-Definitions and Zones

	require_once(BASEDIR.'includes'.DIRECTORY_SEPARATOR.'inc.config.maps.php');

	//

	if (isset($_GET['map']))
	{

		$map	= strtolower(trim(preg_replace('/[^0-9a-z]/i','',$_GET['map'])));

		$xp	= ((isset($_GET['x'])) ? (int)$_GET['x'] : 0);
		$yp	= ((isset($_GET['y'])) ? (int)$_GET['y'] : 0);

		$xp	= (($xp<0) ? 0 : (($xp>4) ? 4 : $xp));
		$yp	= (($yp<0) ? 0 : (($yp>4) ? 4 : $yp));

		if (isset($maps[$map]))
		{

			// Define Zones for Minimap

			for ($y=0;$y<=4;$y++)
			{
				for ($x=0;$x<=4;$x++)
				{
					$minizones[]		= array( 'type'		=> 'rect',
									 'coords'	=> ($x*47).','.($y*47).','.(($x+1)*47).','.(($y+1)*47),
									 'caption'	=> htmlspecialchars($maps[$map]['caption']),
									 'url'		=> $config['script_url'].'?act=world&map='.$map.'&x='.$x.'&y='.$y	 );
				}
			}

			$oSmarty->assign('minizones',		$minizones);

			//

			$oSmarty->assign('map',			array( 'name'	=> htmlspecialchars($maps[$map]['caption']),
								       'file'	=> $maps[$map]['mini'].'_'.$yp.'_'.$xp.'.jpg',
								       'mini'	=> $maps[$map]['file'],
								       'id'	=> $map 	));

			//

			if (isset($zones[$map][$yp][$xp]))
				$oSmarty->assign('zones',	$zones[$map][$yp][$xp]);

			// Build Breadcrumb-Navigation

			$breadcrumbs	= array();

			$breadcrumbs[]	= array( 'caption'	=> 'Home',
						 'url'		=> $config['script_url']			);

			$breadcrumbs[]	= array( 'caption'	=> 'World-Map',
						 'url'		=> $config['script_url'].'?act=world'		);

			$breadcrumbs[]	= array( 'caption'	=> htmlspecialchars($maps[$map]['caption']),
						 'url'		=> ''						);

			$oSmarty->assign('breadcrumbs',		$breadcrumbs);

			// Display Template

			if (!$error)
				$oSmarty->display('file:pages/site_world.tpl');

		}
		else
		{
			$error		= true;
			$oSmarty->assign('error',	'Invalid Map');
		}

	}
	else
	{

		$oSmarty->assign('maps',	$maps);

		// Build Breadcrumb-Navigation

		$breadcrumbs	= array();

		$breadcrumbs[]	= array( 'caption'	=> 'Home',
					 'url'		=> $config['script_url']			);

		$breadcrumbs[]	= array( 'caption'	=> 'World-Map',
					 'url'		=> ''						);

		$oSmarty->assign('breadcrumbs',		$breadcrumbs);

		// Display Template

		if (!$error)
			$oSmarty->display('file:pages/site_worldlist.tpl');

	}

?>
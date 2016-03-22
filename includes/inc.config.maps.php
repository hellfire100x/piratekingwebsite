<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.config.maps.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// Description  : This file contains all definitions needed for the pop-ups for the World-Map.
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	// v0.0.3       : fixed a small bug
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================

	// Prevent people from calling this file directly! Normally it doesn't matter but its always better
	// being save than being sorry.

	if (!defined('PKOSITE'))
		die('Go away!');

	// ===================================================================================================
	// General Map-Definitions
	// ===================================================================================================

	$maps			= array();

	$maps['ascaron']	= array( 'caption'	=> 'Ascaron',
					 'file'		=> 'ascaron.jpg',
					 'mini'		=> 'asc'	);

	$maps['deepblue']	= array( 'caption'	=> 'Deep Blue',
					 'file'		=> 'deepblue.jpg',
					 'mini'		=> 'db'		);

	$maps['magicocean']	= array( 'caption'	=> 'Magic Ocean',
					 'file'		=> 'magicalocean.jpg',
					 'mini'		=> 'mo'		);

	// ===================================================================================================
	// Definitions of pop-ups
	// ===================================================================================================

	$zones				= array();

	// ===================================================================================================
	// ASCARON
	// ===================================================================================================

	$zones['ascaron'][0][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '375,365,410,390',
						 'caption'	=> 'Reagan Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][0][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '90,400,190,470',
						 'caption'	=> 'Outlaw Island',
						 'url'		=> '#'			 );

	$zones['ascaron'][1][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '350,290,470,470',
						 'caption'	=> 'Thundoria Castle',
						 'url'		=> '#'			 );

	$zones['ascaron'][1][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '70,240,150,290',
						 'caption'	=> 'Thundoria Harbor',
						 'url'		=> '#'			 );

	$zones['ascaron'][1][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,330,15,420',
						 'caption'	=> 'Thundoria Castle',
						 'url'		=> '#'			 );

	$zones['ascaron'][1][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '280,140,370,205',
						 'caption'	=> 'Glacier Isle',
						 'url'		=> '#'			 );

	$zones['ascaron'][1][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '210,285,245,315',
						 'caption'	=> 'Hafta Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][1][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '90,0,190,30',
						 'caption'	=> 'Outlaw Island',
						 'url'		=> '#'			 );

	$zones['ascaron'][2][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '140,68,162,90',
						 'caption'	=> 'Lone Tower',
						 'url'		=> '#'			 );

	$zones['ascaron'][2][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '350,250,370,265',
						 'caption'	=> 'Chaldea Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][2][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '295,445,305,455',
						 'caption'	=> 'Solace Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][2][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '370,15,400,40',
						 'caption'	=> 'Albania Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][3][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '160,150,185,175',
						 'caption'	=> 'Valhalla Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][3][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '110,275,140,295',
						 'caption'	=> 'Andes Forest Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][3][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '110,330,135,355',
						 'caption'	=> 'Bandit Camp',
						 'url'		=> '#'			 );

	$zones['ascaron'][3][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '380,340,405,375',
						 'caption'	=> 'Rockery Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][3][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '130,175,160,200',
						 'caption'	=> 'Abandon Mine Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][3][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '295,120,395,250',
						 'caption'	=> 'Argent City',
						 'url'		=> '#'			 );

	$zones['ascaron'][3][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '430,360,470,470',
						 'caption'	=> 'Zephyr Island',
						 'url'		=> '#'			 );

	$zones['ascaron'][3][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,355,90,470',
						 'caption'	=> 'Zephyr Island',
						 'url'		=> '#'			 );

	$zones['ascaron'][4][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '340,110,375,140',
						 'caption'	=> 'Sara Haven',
						 'url'		=> '#'			 );

	$zones['ascaron'][4][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '440,0,470,30',
						 'caption'	=> 'Zephyr Island',
						 'url'		=> '#'			 );

	$zones['ascaron'][4][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,0,105,30',
						 'caption'	=> 'Zephyr Island',
						 'url'		=> '#'			 );

	// ===================================================================================================
	// DEEP BLUE
	// ===================================================================================================

	$zones['deepblue'][0][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '440,195,470,220',
						 'caption'	=> 'Icicle Haven',
						 'url'		=> '#'			 );

	$zones['deepblue'][0][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '250,250,320,345',
						 'caption'	=> 'Icicle City',
						 'url'		=> '#'			 );

	$zones['deepblue'][0][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '200,340,255,390',
						 'caption'	=> 'Icicle City',
						 'url'		=> '#'			 );

	$zones['deepblue'][0][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '110,360,140,390',
						 'caption'	=> 'Atlantis Haven',
						 'url'		=> '#'			 );

	$zones['deepblue'][0][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '265,300,295,325',
						 'caption'	=> 'Skeleton Haven',
						 'url'		=> '#'			 );

	$zones['deepblue'][0][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,330,40,370',
						 'caption'	=> 'Icespire Haven',
						 'url'		=> '#'			 );

	$zones['deepblue'][2][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '100,20,130,45',
						 'caption'	=> 'Salva Haven',
						 'url'		=> '#'			 );

	$zones['deepblue'][2][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '360,25,470,210',
						 'caption'	=> 'Isle of Fortune',
						 'url'		=> '#'			 );

	$zones['deepblue'][2][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,25,60,210',
						 'caption'	=> 'Isle of Fortune',
						 'url'		=> '#'			 );

	$zones['deepblue'][2][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '360,425,470,470',
						 'caption'	=> 'Spring Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][2][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,425,70,470',
						 'caption'	=> 'Spring Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '315,290,470,470',
						 'caption'	=> 'Autumn Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,260,280,470',
						 'caption'	=> 'Autumn Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '150,120,365,260',
						 'caption'	=> 'Autumn Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '280,260,370,305',
						 'caption'	=> 'Autumn Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '280,365,370,400',
						 'caption'	=> 'Autumn Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '415,415,470,470',
						 'caption'	=> 'Summer Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '340,0,470,75',
						 'caption'	=> 'Spring Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,0,70,70',
						 'caption'	=> 'Spring Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '155,70,325,255',
						 'caption'	=> 'Summer Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][3][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,255,325,470',
						 'caption'	=> 'Summer Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][4][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '215,45,285,110',
						 'caption'	=> 'Arena Isle',
						 'url'		=> '#'			 );

	$zones['deepblue'][4][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '435,35,470,120',
						 'caption'	=> 'Isle of Ruins',
						 'url'		=> '#'			 );

	$zones['deepblue'][4][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,35,70,120',
						 'caption'	=> 'Isle of Ruins',
						 'url'		=> '#'			 );

	$zones['deepblue'][4][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '275,0,470,90',
						 'caption'	=> 'Autumn Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][4][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '45,0,280,85',
						 'caption'	=> 'Autumn Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][4][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '370,0,470,145',
						 'caption'	=> 'Summer Island',
						 'url'		=> '#'			 );

	$zones['deepblue'][4][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,0,260,180',
						 'caption'	=> 'Summer Island',
						 'url'		=> '#'			 );

	// ===================================================================================================
	// MAGICAL OCEAN
	// ===================================================================================================

	$zones['magicocean'][0][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '255,245,280,275',
						 'caption'	=> 'Norite Harbor Island',
						 'url'		=> '#'			 );

	$zones['magicocean'][0][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '210,340,245,370',
						 'caption'	=> 'Aerase Haven',
						 'url'		=> '#'			 );

	$zones['magicocean'][0][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '385,300,470,435',
						 'caption'	=> 'Isle of Chill',
						 'url'		=> '#'			 );

	$zones['magicocean'][0][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,300,45,435',
						 'caption'	=> 'Isle of Chill',
						 'url'		=> '#'			 );

	$zones['magicocean'][1][4][]	= array( 'type'		=> 'rect',
						 'coords'	=> '100,40,135,65',
						 'caption'	=> 'Lahu Haven',
						 'url'		=> '#'			 );

	$zones['magicocean'][2][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '420,400,470,470',
						 'caption'	=> 'Cupid Isle',
						 'url'		=> '#'			 );

	$zones['magicocean'][2][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,390,105,470',
						 'caption'	=> 'Cupid Isle',
						 'url'		=> '#'			 );

	$zones['magicocean'][2][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '335,255,365,280',
						 'caption'	=> 'Pyrex Haven',
						 'url'		=> '#'			 );

	$zones['magicocean'][3][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '440,360,470,375',
						 'caption'	=> 'Oasis Haven',
						 'url'		=> '#'			 );

	$zones['magicocean'][3][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '213,400,234,422',
						 'caption'	=> 'Babul Haven',
						 'url'		=> '#'			 );

	$zones['magicocean'][3][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '200,140,230,160',
						 'caption'	=> 'Woody Haven',
						 'url'		=> '#'			 );

	$zones['magicocean'][3][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '425,0,470,45',
						 'caption'	=> 'Cupid Isle',
						 'url'		=> '#'			 );

	$zones['magicocean'][3][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,0,95,75',
						 'caption'	=> 'Cupid Isle',
						 'url'		=> '#'			 );

	$zones['magicocean'][4][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '70,55,95,83',
						 'caption'	=> 'Hubble Haven',
						 'url'		=> '#'			 );

	$zones['magicocean'][4][0][]	= array( 'type'		=> 'rect',
						 'coords'	=> '440,90,470,240',
						 'caption'	=> 'Shaitan City',
						 'url'		=> '#'			 );

	$zones['magicocean'][4][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,90,55,240',
						 'caption'	=> 'Shaitan City',
						 'url'		=> '#'			 );

	$zones['magicocean'][4][1][]	= array( 'type'		=> 'rect',
						 'coords'	=> '445,230,470,330',
						 'caption'	=> 'Canary Isle',
						 'url'		=> '#'			 );

	$zones['magicocean'][4][2][]	= array( 'type'		=> 'rect',
						 'coords'	=> '0,230,70,330',
						 'caption'	=> 'Canary Isle',
						 'url'		=> '#'			 );

	$zones['magicocean'][4][3][]	= array( 'type'		=> 'rect',
						 'coords'	=> '400,100,430,125',
						 'caption'	=> 'Ethio Haven',
						 'url'		=> '#'			 );

?>
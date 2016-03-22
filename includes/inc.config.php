<?php

	// ===================================================================================================
	// Package      : pkoSite
	// File         : inc.config.php
	// Version      : 1.0.0
	// Author       : SnowCrash
	// ===================================================================================================
	// History:
	//
	// v1.0.0       : release of the FINAL version ;o)
	//                added new variables 'cache_rankings_*'
	// v0.0.3b      : added new variable 'cache_stats'
	// v0.0.3       : added new variable 'enable_guild_admin'
	// v0.0.2       : added new variables 'disable_registration', 'cron_autoban' and 'delete_pending'
	//                added some informations for non-codes to this file.
	// v0.0.1c      : added new variable $config['new_gm_level'] for GM-servers and $config['new_gm_level']
	// v0.0.1b      : added basename() to $config['scriptname']
	// v0.0.1       : first release
	// ===================================================================================================
	// Disclaimer   : To make it short - if you steal code you're a pathetic, little loser :-)
	// ===================================================================================================
	//
	// ATTENTION! This is a small info for all non-coders reading this file!!!
	//
	// Everything after "//" are comments!                     \ This means you don't have to change
	// Everything between "/*" and "*/" are comments, too!     / anything thats commented!
	//
	// If you change the content of a value keep in mind that ' must be escaped. This means: lets say
	// your server is called "Big Al's Private Server" and you want to change $config['servername']
	// to contain your name, then DO NOT write:
	//
	// $config['servername']			= 'Big Al's Private Server';
	//
	// because this will cause an error. Instead you must write:
	//
	// $config['servername']			= 'Big Al\'s Private Server';
	//
	// The "\" before the ' will not be displayed but is necessary for PHP to know that the ' in Al's
	// is not the end of the string.
	//
	// Some variables have values like "true". the opposite of this is "false" (and vice versa). So
	// for example if your server is a GM-server your config should look like this:
	//
	// $config['is_gm_server']			= true;
	//
	// and otherwise:
	//
	// $config['is_gm_server']			= false;
	//
	// I hope this prevents people from messing up this file ;-)
	//
	// ===================================================================================================

	$config					= array();

	// ===================================================================================================
	// = General Site-Configuration
	// ===================================================================================================

	$config['servername']			= 'Your Private Server';				// Name of your private Server
	$config['slogan']			= 'Pirates Inside! Yarrrrr!';				// Slogan

	$config['is_gm_server']			= false;						// Set to true if you run a GM-server because we'll
													// use some different SQL-queries for those servers.

	$config['gm_chars']			= array();						// This is some kind of "second line of defense".
													// If you are running a GM-Server or if you want
													// additional security, add the ACCOUNT-IDs of
													// those GM-accounts that should be able to access
													// the Admin-area to this array.
													//
													// For example: array(12, 33, 1283)

	$config['max_compatibility_mode']	= true;							// There seem to be some issues with servers where
													// the "account_login" and "account" tables contain
													// inconsistent data. This leads to the situation that
													// the user has different account-ids in both tables.
													// Leave this set to "true" for now. This forces pkoSite
													// to use the account-name for all operations instead
													// of the IDs.

	$config['enable_guild_admin']		= true;							// If set to true guild-leaders can admin their guild
													// on the website (at least some of their stuff)

	// ===================================================================================================
	// = Data-Storage-Engine
	// ===================================================================================================
	// = pkoSite can use different storage-engines to save news and downloads.
	// ===================================================================================================
	// = Possible Storage-Engines:
	// =
	// = $config['site_db_type']		= 'sqlite';
	// = $config['site_db_type']		= 'flat';
	// = $config['site_db_type']		= 'mysql';
	// ===================================================================================================
	// = A little explanation:
	// =
	// = To make pkoSite as easy to use as possible, I added two very simple engines: 'sqlite' and 'flat'
	// =
	// = 'sqlite' uses a SQLite database which is stored in the "data\sqlite\" folder. SQLite is normally
	// = installed with PHP.
	// =
	// = 'flat' is a very untested (and probably unstable) FlatFile-database. This means that all data is
	// = stored in simple text-files in the "data\flat\" folder.
	// =
	// = 'mysql' is the best choice - but hardest to setup. If you're running a mysql-server you can use
	// = the "pkosite.sql" file located in "data\mysql\" to create a new database with all needed tables.
	// = If you created the tables, you must uncomment the "config['mysql']" array further down and enter
	// = your data.
	// ===================================================================================================

	if (extension_loaded('sqlite'))
		$config['site_db_type']		= 'sqlite';		// Use SQLite automatically if PHP allows it
	else
		$config['site_db_type']		= 'flat';

/*

	MySQL-Example:
	==============

	$config['site_db_type']		= 'mysql';

	$config['mysql']		= array( 'host'		=> 'localhost',
						 'user'		=> 'root',
						 'password'	=> '',
						 'database'	=> 'pkosite'	);

*/

	// ===================================================================================================
	// = Frontpage
	// ===================================================================================================

	$config['news_display']			= 3;							// Number of News entries to display on the front-page with *full* text

	// ===================================================================================================
	// = Server-Rates
	// ===================================================================================================
	// = This is only important if the template you use supports this (like the default-template).
	// = these variables are used to display the server-rates.
	// ===================================================================================================

	$config['rates']			= array( 'exp'		=> 1,				// Solo
							 'pexp'		=> 1,				// Party
							 'fairy'	=> 1,				// Fairy Growth
							 'drop'		=> 1,				// Drop-Rate
							 'ship'		=> 1		);		// Ship-Experience

	// ===================================================================================================
	// = Forum
	// ===================================================================================================
	// = If you have a forum, just put its link here. If the template supports this feature, a link
	// = to the forum is added to the site.
	// ===================================================================================================

	$config['forum']			= '';

	// ===================================================================================================
	// = Anonymize D/L Links
	// ===================================================================================================
	// = If you enable D/L anonymization, all download-links will be anonymized.
	// ===================================================================================================

	$config['anonymize']			= true;
	$config['anonymize_url']		= 'http://anonym.to/?%URL%';				// %URL% will be replaced with the D/L url

	// Alternatives to anonym.to
	//
	// $config['anonymize_url']		= 'http://link.yagbu.net/?%URL%';			// %URL% will be replaced with the D/L url
	// $config['anonymize_url']		= 'http://hidemyass.com/?%URL%';			// %URL% will be replaced with the D/L url
	// $config['anonymize_url']		= 'http://promanele.net/?%URL%';			// %URL% will be replaced with the D/L url
	// $config['anonymize_url']		= 'http://extramina.com/?%URL%';			// %URL% will be replaced with the D/L url

	// ===================================================================================================
	// = Time-Offset
	// ===================================================================================================
	// = Another purely cosmetical thing ;-) If your template supports it, this changed the time of the
	// = displayed real-time-clock.
	// ===================================================================================================
	// = You can either leave the following code intact or enter the right value on your own.
	// = time_offset is the difference between your local time and GMT! For example: if you live in a
	// = place which time is GMT+5, you should set time_offset to '5'. Or if you're living somewhere with
	// = a time like GMT+2.5, add '2.5' - Normally, the code below *should* detect that difference on
	// = its own so you don't have to do anything.
	// ===================================================================================================

	$dummy					= date('O',time());
	$config['time_offset']			= ((substr($dummy,0,1) != '+') ? '-' : '').(int)substr($dummy,1,2).'.'.substr($dummy,3,2);

	// ===================================================================================================
	// = Rankings-Page
	// ===================================================================================================
	// = This is pretty obvious. Just set whatever values you want.
	// = ATTENTION! $config['top_richest'] is a little bit different, if its set to 0, the links to that
	// = ranking-page will disappear. Generally, I recommend to set this to 0 to prevent scammers from
	// = knowing which people make good targets.
	// ===================================================================================================

	$config['top_richest']			= 50;							// Number of wealthiest players to display
	$config['top_guilds']			= 15;							// Number of Top-Guilds to display
	$config['top_players']			= 50;							// Number of Top-Players to display

	// ===================================================================================================
	// = Cache
	// ===================================================================================================
	// = "cache_stats" defines how long statistics get cached. This is done to decrease unnecessary
	// = database-queries. For example if you set it to 300, the stats are only calculated every 5
	// = minutes.
	// ===================================================================================================

	$config['cache_stats']			= 300;							// Number of seconds to cache stats
	$config['cache_rankings_exp']		= 3600;							// Number of seconds to cache exp-rankings
	$config['cache_rankings_gold']		= 3600;							// Number of seconds to cache gold-rankings
	$config['cache_rankings_guild']		= 3600;							// Number of seconds to cache guild-rankings

	// ===================================================================================================
	// = WebIM
	// ===================================================================================================
	// = If you have OpenWebIM installed and its located in the "webim" folder beneath pkosite, the
	// = following code will auto-detect it and display a button showing if somebody is online there (if
	// = the template supports this!)
	// =
	// = You can set this manually to true/false if you want. The HTML used to display the button is
	// = located in "templates\sidebar\right.tpl".
	// ===================================================================================================

	$config['use_webim']			= file_exists(BASEDIR.'webim'.DIRECTORY_SEPARATOR.'index.php');			// This checks if there is a "webim" directory containing "index.php"
																// If your WebIM is installed somewhere else, just set this var to
																// true (and don't forget to edit sidebar/right.tpl !)

	// ===================================================================================================
	// = Registration-Settings
	// ===================================================================================================
	// = Just read the comments on the right...
	// =
	// = If I were you, I would always leave "use_captcha" set to true to prevent bots from registering
	// = hundreds or thousands of accounts.
	// ===================================================================================================

	$config['disable_registration']		= false;						// true -> registration will be disabled

	$config['use_captcha']			= true;							// true -> use captcha on reg-page, false -> don't use captcha

	$config['allow_dupe_email']		= false;						// true -> allow users to use the same email for multiple accounts

	$config['use_activation']		= false;						// if this is set to "true" , upon registration an email is sent to
													// the player and he has to click on an activation-link before his
													// account is created.

	$config['new_gm_level']			= 0;							// ATTENTION: if $config['is_gm_server'] is "true", EVERY new account
													// will get the GM-level you put here. So if you want everybody to be
													// a full GM you must put 99 here.

	// ===================================================================================================
	// = Registration-Settings
	// ===================================================================================================
	// = Define links to toplists - currently only http://rank.serverdev.net is supported!
	// = (and of course your template must support this feature)
	// ===================================================================================================

	$config['toplists']			= array( 'privatia'	=> '' );			// Enter your Accountname here if you have an account on rank.serverdev.net!

	// ===================================================================================================
	// = Mail-Settings
	// ===================================================================================================
	// = mode		-> either smtp or php (smtp uses SwiftMailer to send mails using SMTP while
	// =			   php requires your server to be configured properly - most XAMPP users
	// =                       will have to use smtp as php won't work!)
	// = use_ssl		-> set this to true if the SMTP-server requires encrypted connections
	// =                       (this needs some libs on the server and will cause lots of trouble! so
	// =                        have fun getting that to work properly *lol*)
	// = port		-> port to use on the server (25 = default for normal SMTP)
	// = server		-> hostname/IP of the SMTP-server
	// = email		-> YOUR email-address used as From/Reply-To address
	// = use_auth 		-> if "true", the SMTP-server requires you to login
	// = username		-> username to use for login on SMTP-server
	// = password		-> password to use for login on SMTP-server
	// ===================================================================================================

	$config['mail']				= array( 'mode'		=> 'smtp',
							 'use_ssl'	=> false,
							 'server'	=> 'smtp.somewhere.com',
							 'port'		=> 25,
							 'email'	=> 'your@domain.com',
							 'username'	=> 'u123456',
							 'password'	=> 'pw123456',
							 'use_auth'	=> 'yes'		);

/*

	This is an example for GMail (which i could'nt get to work) :

	$config['mail']				= array( 'mode'		=> 'smtp',
							 'use_ssl'	=> true,
							 'email'	=> 'you@gmail.com',
							 'server'	=> 'smtp.gmail.com',
							 'port'		=> 465,				// 465 or 587 for GMail
							 'account'	=> 'you',			// without "@gmail.com" !!!
							 'password'	=> 'your_password',
							 'use_auth'	=> 'yes'		);	// openssl must be installed!

	And this is an example that uses the build-in mail() function of PHP - as you can see, not much configuration is needed *lol*

	$config['mail']				= array( 'mode'		=> 'php',
							 'email'	=> 'your@domain.com'	);

*/

	// ===================================================================================================
	// = Database-Configuration
	// ===================================================================================================
	// = The following two arrays define the logins which should be used to access the database.
	// =
	// = If you use accounts with passwords, 'pass' MUST contain the UNENCRYPTED passwords! (NOT those
	// = from the config-files of the gameserver, etc. !!!!!!!)
	// ===================================================================================================

	$config['db']['game']			= array( 'host'		=> '(local)',
							 'db'		=> 'GameDB',
							 'user'		=> 'sa',
							 'pass'		=> ''			);

	$config['db']['account']		= array( 'host'		=> '(local)',
							 'db'		=> 'AccountServer',
							 'user'		=> 'sa',
							 'pass'		=> ''			);

	// ===================================================================================================
	// = Automatic Tasks
	// ===================================================================================================

	$config['delete_pending']		= 7;							// Number of days afer which a pending account should be deleted

	$config['cron_autoban']			= false;						// ATTENTION! If you set this to true, all accounts that share the same
													// MAC-address as an already banned account will be banned, too!

	// =========================================================================================================================================
	// = Now comes a lot of rather tricky code.
	// =========================================================================================================================================
	// = The following code does a lot of fancy stuff to detect some things. Basically it auto-detects some paths, etc. to fill a few variables.
	// =
	// = Lets make some assumptions:
	// =
	// = 	Lets say you've installed the files to C:\Web\htdocs
	// = 	Lets say your server has the domain "www.myprivateserver.com"
	// =
	// = These vars are set:
	// =
	// = $config['base_url']	-> will contain "http://www.myprivateserver.com"
	// = $config['base_path']	-> will contain "c:\web\htdocs"
	// = $config['scriptname']	-> will contain "index.php"
	// = $config['script_url']	-> will contain "http://www.myprivateserver.com/index.php"
	// =
	// = I hope the auto-detection works. If not, feel free to set the vars on your own.
	// =========================================================================================================================================

	if(array_key_exists('HTTPS', $_SERVER) && ($_SERVER['HTTPS'] == 'on'))
		$url	= 'https://';
	else
		$url	= 'http://';

	$url		.= $_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];

	$parts		= parse_url($url);

	if (substr($parts['path'],-1,1)=='/')
		$parts['dirpath']	= $parts['path'];
	else
		$parts['dirpath']	= substr($parts['path'],0,strrpos($parts['path'],'/')+1);

	// Special Port?

	if ((int)$_SERVER['SERVER_PORT']<>80)
		$config['base_url']		= $parts['scheme'].'://'.$parts['host'].':'.$_SERVER['SERVER_PORT'].$parts['dirpath'];
	else
		$config['base_url']		= $parts['scheme'].'://'.$parts['host'].$parts['dirpath'];

	unset($url);
	unset($parts);

	$config['base_path']		= BASEDIR;
	$config['scriptname']		= basename(ltrim($_SERVER['SCRIPT_NAME'],'/'));
	$config['script_url']		= $config['base_url'].$config['scriptname'];

	// ===================================================================================================
	// = Translations
	// ===================================================================================================
	// = OK, the following two arrays contain "translations".
	// =
	// = Some tables store data either in numeric or string-form that must be "translated" before displaying.
	// =
	// = For example: if your server stores the "job" field as "Crsdr" in the database, add
	// =
	// = $jobs['Crsdr']			= 'Crusader';
	// =
	// = to the jobs-array.
	// ===================================================================================================

	$config['chars']		= array();

	$config['chars'][1]		= 'Lance';
	$config['chars'][2]		= 'Carsise';
	$config['chars'][3]		= 'Phyllis';
	$config['chars'][4]		= 'Ami';

	$config['jobs']			= array();

	$config['jobs']['Swordy']	= 'Swordsman';			// These abbreviations should used by most servers (taken from joeblow's files).
	$config['jobs']['Xplorer']	= 'Explorer';			// Thx to Octie and opexone78 for pointing me in the right direction.
	$config['jobs']['Herby']	= 'Herbalist';
	$config['jobs']['Mrchnt']	= 'Merchant';
	$config['jobs']['Champ']	= 'Champion';
	$config['jobs']['Crusdr']	= 'Crusader';
	$config['jobs']['Knight']	= 'White Knight';
	$config['jobs']['Tamer']	= 'Animal Tamer';
	$config['jobs']['SShoot']	= 'Sharpshooter';
	$config['jobs']['Captin']	= 'Captain';
	$config['jobs']['Voyger']	= 'Voyager';
	$config['jobs']['Upstrt']	= 'Upstart';
	$config['jobs']['Engine']	= 'Engineer';
	$config['jobs']['SMastr']	= 'Seal Master';

	$config['jobs']['新手']		= 'Newbie';			// Chinese ?!?
	$config['jobs']['剑士']		= 'Swordsman';
	$config['jobs']['猎人']		= 'Hunter';
	$config['jobs']['水手']		= 'Sailor';
	$config['jobs']['冒险者']	= 'Explorer';
	$config['jobs']['祈愿使']	= 'Wish Angel';
	$config['jobs']['技师']		= 'Artisan';
	$config['jobs']['商人']		= 'Merchant';
	$config['jobs']['巨剑士']	= 'Champion';
	$config['jobs']['双剑士']	= 'Crusader';
	$config['jobs']['剑盾士']	= 'White Knight';
	$config['jobs']['驯兽师']	= 'Animal Tamer';
	$config['jobs']['狙击手']	= 'Sharpshooter';
	$config['jobs']['圣职者']	= 'Cleric';
	$config['jobs']['封印师']	= 'Seal Master';
	$config['jobs']['船长']		= 'Captain';
	$config['jobs']['航海士']	= 'Voyager';
	$config['jobs']['暴发户']	= 'Upstart';
	$config['jobs']['工程师']	= 'Engineer';

	$config['jobs']['穝も']		= 'Newbie';			// chinese job-names taken from ToPCP by Sjikke
	$config['jobs']['糃']		= 'Swordsman';
	$config['jobs']['聐']		= 'Hunter';
	$config['jobs']['玙繧']	= 'Explorer';
	$config['jobs']['篅ㄏ']	= 'Herbalist';
	$config['jobs']['エ糃']	= 'Champion';
	$config['jobs']['蛮糃']	= 'Crusader';
	$config['jobs']['阑も']	= 'Sharpshooter';
	$config['jobs']['蒾戮']	= 'Cleric';
	$config['jobs']['畍']	= 'Seal Master';
	$config['jobs']['']	= 'Voyager';

	// =========================================================================================================================================
	// PLEASE KEEP YOUR FINGERS FROM ANYTHING BELOW THIS LINE! THANKS!
	// =========================================================================================================================================

	$connections			= array( 'game'		=> false,
						 'account'	=> false		);

	$stats				= array( 'queries'	=> array(),
						 'querycount'	=> 0,
						 'start'	=> microtime(true)	);

	// Load FlatFile-class if needed

	if ($config['site_db_type'] == 'flat')
		require_once(BASEDIR.'classes'.DIRECTORY_SEPARATOR.'flatfile'.DIRECTORY_SEPARATOR.'flatfile.php');	// FlatFile-Database

	$error				= false;

	// And yes, I know that global variables aren't good practice. But sometimes it makes things easier for newbies.

	define('PKOSITE',		1);

	define('DEBUG',			false);

?>
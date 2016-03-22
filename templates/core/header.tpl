<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="3600" />
		<meta name="revisit-after" content="2 days" />
		<meta name="robots" content="index,follow" />
		<meta name="publisher" content="Your publisher infos here ..." />
		<meta name="copyright" content="Your copyright infos here ..." />
		<meta name="author" content="Design: 1234.info / Modified: {$servername}" />
		<meta name="distribution" content="global" />
		<meta name="description" content="Your page description here ..." />
		<meta name="keywords" content="Your keywords, keywords, keywords, here ..." />
		<link rel="stylesheet" type="text/css" media="screen,projection,print" href="{$base_url}/css/mf42_layout4_setup.css" />
		<link rel="stylesheet" type="text/css" media="screen,projection,print" href="{$base_url}/css/mf42_layout4_text.css" />
		<link rel="stylesheet" type="text/css" media="screen,projection,print" href="{$base_url}/css/jquery.tooltip.css" />
		<link rel="icon" type="image/x-icon" href="{$base_url}/img/favicon.ico" />
		<title>{$servername}{$pagename}</title>
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<script>
{literal}
			<!--

			// Load jQuery from google's servers
			google.load("jquery", "1.3.0");

			google.setOnLoadCallback(function() {
			
				$("map *").tooltip({ 
					positionLeft: true,
					showURL: false,
					track: true });
				
				$('#jclock1').jclock({
					utc: true,
{/literal}
					utc_offset: {$time_offset} });
{literal}

				$('a.confirm').click(function(event) {

					// Dirty little hack to support IE

					var fakeLink = document.createElement ("a");

					if (typeof(fakeLink.click) == 'undefined')
						location.href = $(this).attr('href');		// sends referrer in FF, not in IE
					else
					{
						fakeLink.href = $(this).attr('href');
						document.body.appendChild(fakeLink);
						fakeLink.click();				// click() method defined in IE only
					}

    				}).confirm();

			});

			-->
{/literal}
		</script>
		<script type="text/javascript" src="{$base_url}/js/jquery.tooltip.js"></script>
		<script type="text/javascript" src="{$base_url}/js/jquery.jclock.js"></script>
		<script type="text/javascript" src="{$base_url}/js/jquery.confirm.js"></script>
	</head>
<body>

	<div class="clock">
		{$servername} | Server-Date: {$smarty.now|date_format:"%Y-%m-%d"} | Server Time: <b><span id="jclock1">{$smarty.now|date_format:"%H:%M"}</span></b>
	</div>

	<!-- Main Page Container -->

	<div class="page-container">

		<!-- A. HEADER -->      

		<div class="header">
      
			<!-- A.1 HEADER TOP -->
			<div class="header-top">

				<div class="round-border-topleft"></div><div class="round-border-topright"></div>      
        
				<!-- Sitelogo and sitename -->
				<a class="sitelogo" href="{$script_url}" title="Home"></a>
				<div class="sitename">
					<h1><a href="{$script_url}" title="Go to Start page">{$servername}</a></h1> 
					<h2>{$slogan}</h2>
				</div>

			</div>
            
			<!-- A.3 HEADER BOTTOM -->
			<div class="header-bottom">
	      
				<!-- Navigation Level 2 (Drop-down menus) -->
				<div class="nav2">
				
					<!-- Navigation item -->
					<ul>
						<li><a href="{$script_url}">Home</a></li>
					</ul>

{if $forum_url neq ""}
					<!-- Navigation item -->
					<ul>
						<li><a href="{$forum_url}">Community</a></li>
					</ul>
{/if}
{if $user.is_logged_in}
					<!-- Navigation item -->
					<ul>
						<li><a href="{$script_url}?act=account">Your Account<!--[if IE 7]><!--></a><!--<![endif]-->
						<!--[if lte IE 6]><table><tr><td><![endif]-->
							<ul>
								<li><a href="{$script_url}?act=account&sact=chars">Your Characters</a></li>
{if $enable_guild_admin}
								<li><a href="{$script_url}?act=account&sact=guilds">Your Guilds</a></li>
{/if}
								<li><a href="{$script_url}?act=account&sact=changepw">Change Password</a></li>
								<li><a href="{$script_url}?act=account&sact=changemail">Change eMail</a></li>
							</ul>
						<!--[if lte IE 6]></td></tr></table></a><![endif]-->
						</li>
					</ul>                    
{else}
{if $allow_registration eq true}
					<!-- Navigation item -->
					<ul>
						<li><a href="{$script_url}?act=register">Register</a></li>
					</ul>
{/if}
{/if}
{if $user.is_admin}
					<!-- Navigation item -->
					<ul>
						<li><a href="{$script_url}?act=admin">Administration<!--[if IE 7]><!--></a><!--<![endif]-->
						<!--[if lte IE 6]><table><tr><td><![endif]-->
							<ul>
								<li><a href="{$script_url}?act=admin&sact=rules">Edit Rules</a></li>
								<li><a href="{$script_url}?act=admin&sact=news">Edit News</a></li>
								<li><a href="{$script_url}?act=admin&sact=authors">Edit News-Authors</a></li>
								<li><a href="{$script_url}?act=admin&sact=ncategories">Edit News-Categories</a></li>
								<li><a href="{$script_url}?act=admin&sact=downloads">Edit Downloads</a></li>
								<li><a href="{$script_url}?act=admin&sact=dlcategories">Edit D/L-Categories</a></li>
							</ul>
						<!--[if lte IE 6]></td></tr></table></a><![endif]-->
						</li>
					</ul>                    
{/if}

				</div>
			</div>

			<!-- A.4 HEADER BREADCRUMBS -->

			<!-- Breadcrumbs -->
			<div class="header-breadcrumbs">
				<ul>
{foreach item=breadcrumb from=$breadcrumbs}
	{if $breadcrumb.url eq ""}
					<li>{$breadcrumb.caption}</li>
	{else}
					<li><a href="{$breadcrumb.url}">{$breadcrumb.caption}</a></li>
	{/if}
{/foreach}
				</ul>

			</div>
		</div>

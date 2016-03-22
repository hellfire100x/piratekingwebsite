
		<!-- B.3 SUBCONTENT -->
		<div class="main-subcontent">
{if $user.is_logged_in}
			<!-- Subcontent unit -->
			<div class="subcontent-unit-border">
			<div class="round-border-topleft"></div><div class="round-border-topright"></div>
				<h1>Account</h1>
				<p>You are currently logged in as <b>{$user.name}</b>.</p>
				<p><a href="{$script_url}?act=account">Your Account</a></p>
				<p><a href="{$script_url}?act=account&sact=chars">Your Characters</a><br />
				<a href="{$script_url}?act=account&sact=changepw">Change Password</a><br />
				<a href="{$script_url}?act=account&sact=changemail">Change eMail</a></p>
				<p><a href="{$script_url}?act=logout">Logout</a></p>
			</div>
{else}
			<div class="subcontent-unit-border">
				<div class="round-border-topleft"></div><div class="round-border-topright"></div>
				<h1>Login</h1>
				<div class="loginform">
					<form method="post" action="{$script_url}"> 
						<input type="hidden" name="act" value="login" />
						<fieldset>
							<p><label for="username" class="top">User:</label><br />
								<input type="text" name="username" id="username" tabindex="101" class="field" value="" /></p>
							<p><label for="password" class="top">Password:</label><br />
								<input type="password" name="password" id="password" tabindex="102" class="field" value="" autocomplete="off" /></p>
							<p><input type="submit" name="login" class="button" value="LOGIN" tabindex="103" /></p>
							<p><a href="{$script_url}?act=forgot">Forgot your password?</a></p>
						</fieldset>
					</form>
				</div>
			</div>
{/if}

			<!-- Subcontent unit -->
			<div class="subcontent-unit-border-orange">
			<div class="round-border-topleft"></div><div class="round-border-topright"></div>
				<h1 class="orange">Statistics</h1>
				<p><span style="float:left;font-weight:bold;">Accounts</span><span style="float:right">{$stats.accounts}</span></p>
				<p><span style="float:left;font-weight:bold;">Characters</span><span style="float:right">{$stats.chars}</span></p>
				<p><span style="float:left;font-weight:bold;">Guilds</span><span style="float:right">{$stats.guilds}</span></p>
				<p><span style="float:left;font-weight:bold;">Online</span><span style="float:right">{$stats.online}</span></p>
			</div>

			<!-- Subcontent unit -->
			<div class="subcontent-unit-border-green">
			<div class="round-border-topleft"></div><div class="round-border-topright"></div>
				<h1 class="green">Online Record</h1>
				<p>The most players ({$stats.record.online}) were online on {$stats.record.timestamp|date_format:"%Y-%m-%d"} at {$stats.record.timestamp|date_format:"%H:%M:%S"}.</p>
			</div>

			<!-- Subcontent unit -->
			<div class="subcontent-unit-border-blue">
			<div class="round-border-topleft"></div><div class="round-border-topright"></div>
				<h1 class="blue">Job Distribution</h1>
				<p><span style="float:left;font-weight:bold;">Champion</span><span style="float:right">{$stats.jobs.champion}</span></p>
				<p><span style="float:left;font-weight:bold;">Cleric</span><span style="float:right">{$stats.jobs.cleric}</span></p>
				<p><span style="float:left;font-weight:bold;">Crusader</span><span style="float:right">{$stats.jobs.crusader}</span></p>
				<p><span style="float:left;font-weight:bold;">Explorer</span><span style="float:right">{$stats.jobs.explorer}</span></p>
				<p><span style="float:left;font-weight:bold;">Herbalist</span><span style="float:right">{$stats.jobs.herbalist}</span></p>
				<p><span style="float:left;font-weight:bold;">Hunter</span><span style="float:right">{$stats.jobs.hunter}</span></p>
				<p><span style="float:left;font-weight:bold;">Newbie</span><span style="float:right">{$stats.jobs.newbie}</span></p>
				<p><span style="float:left;font-weight:bold;">Seal Master</span><span style="float:right">{$stats.jobs.sealmaster}</span></p>
				<p><span style="float:left;font-weight:bold;">Sharpshooter</span><span style="float:right">{$stats.jobs.sharpshooter}</span></p>
				<p><span style="float:left;font-weight:bold;">Swordsman</span><span style="float:right">{$stats.jobs.swordsman}</span></p>
				<p><span style="float:left;font-weight:bold;">Voyager</span><span style="float:right">{$stats.jobs.voyager}</span></p>
			</div>

{if $toplist.privatia neq ""}
			<!-- Subcontent unit -->
			<div class="subcontent-unit-border-green">
			<div class="round-border-topleft"></div><div class="round-border-topright"></div>
				<h1 class="green">Vote!</h1>
				<p>Please support us by voting for us:<br /><br />
					<a href="http://rank.serverdev.net/index.php?a=rate&u={$toplist.privatia}"><img src="./img/vote/privatia.gif" title="Vote!" border="0" style="border:0;" /></a>
				</p>
			</div>
{/if}

{if $webim_found}
			<!-- Subcontent unit -->
			<div class="subcontent-unit-border-green">
			<div class="round-border-topleft"></div><div class="round-border-topright"></div>
				<h1 class="green">Live Support!</h1>
				<p>
					<a href="/webim/client.php?locale=en" target="_blank" onclick="if(navigator.userAgent.toLowerCase().indexOf('opera') != -1 && window.event.preventDefault) window.event.preventDefault();this.newWindow = window.open('/webim/client.php?locale=en', 'webim', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=640,height=480,resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;"><img src="/webim/button.php?image=webim&lang=en" border="0" width="163" height="61" style="border:0;" /></a>
				</p>
			</div>

{/if}
		</div>

{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Change Password</h1>

			<p>Please fill out the form below to change your password!</p>

			<div class="column1-unit">

{if count($message.messages) gt 0}
				<div class="{$message.type}">
					<ul>
				{foreach item=text from=$message.messages}
						<li>{$text}</li>
				{/foreach}
					</ul>
				</div>
{/if}

				<div class="simpleform">
					<form method="post" action="{$script_url}">
						<input type="hidden" name="act" value="account" />
						<input type="hidden" name="sact" value="changepw" />
						<p><label for="pw_current" class="left"><b>Current</b> PW:</label>
							<input type="password" name="pw_current" id="pw_current" class="shortfield" value="" tabindex="1" /></p>
						<p><label for="pw_password" class="left">Password:</label>
							<input type="password" name="pw_password" id="pw_password" class="shortfield" value="" tabindex="2" /></p>
						<p><label for="pw_password2" class="left">Repeat PW:</label>
							<input type="password" name="pw_password2" id="pw_password2" class="shortfield" value="" tabindex="3" /></p>
						<p><img src="{$script_url}?act=getcaptcha" alt="Captcha" style="margin-left:114px;" /></p>
						<div style="clear:both;"></div>
						<p><label for="pw_captcha" class="left">Code:</label>
							<input type="text" name="pw_captcha" id="pw_captcha" class="shortfield" value="" tabindex="5" /></p>
						<p><input type="submit" name="submit" id="submit" class="button" value="OK" tabindex="6" /></p>
						<p>&nbsp;</p>
					</form>
				</div>              
			</div>

			<p>
				<ul>
					<li> Passwords can be 5-20 chars long!</li>
					<li> Passwords can contain the following chars: a-z A-Z 0-9 ~!@#$%^&*()_+|-=\[]:";&lt;&gt;?,./</li>
				</ul>
			</p>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
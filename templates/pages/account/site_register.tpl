{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Register</h1>

			<p>Before registering a new account please make sure you read our <a href="{$script_url}?act=rules">rules</a>!</p>

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

{if $allow_registration eq true}
				<div class="simpleform">
					<form method="post" action="{$script_url}?act=register">
						<input type="hidden" name="register" value="doit" />
						<p><label for="reg_username" class="left">Username:</label>
							<input type="text" name="reg_username" id="reg_username" class="shortfield" value="{$form_username}" tabindex="1" /></p>
						<p><label for="reg_password" class="left">Password:</label>
							<input type="password" name="reg_password" id="reg_password" class="shortfield" value="" tabindex="2" autocomplete="off" /></p>
						<p><label for="reg_password2" class="left">Repeat PW:</label>
							<input type="password" name="reg_password2" id="reg_password2" class="shortfield" value="" tabindex="3" autocomplete="off" /></p>
						<p><label for="reg_email" class="left">eMail:</label>
							<input type="text" name="reg_email" id="reg_email" class="field" value="{$form_email}" tabindex="4" /></p>
{if $show_captcha}
						<p><img src="{$script_url}?act=getcaptcha" alt="Captcha" style="margin-left:114px;" /></p>
						<div style="clear:both;"></div>
						<p><label for="reg_captcha" class="left">Code:</label>
							<input type="text" name="reg_captcha" id="reg_captcha" class="shortfield" value="" tabindex="5" /></p>
{/if}
						<p><input type="submit" name="submit" id="submit" class="button" value="Register" tabindex="6" /></p>
						<p>&nbsp;</p>
					</form>
				</div>              
{/if}
			</div>

{if $allow_registration eq true}
			<p>
				<ul>
							
					<li> Usernames can be 5-20 chars long!</li>
					<li> Usernames can contain the following chars: a-z A-Z 0-9 ~!@#$%^&*()_+|-=\[]:";&lt;%gt;?,./</li>
					<li> Passwords can be 5-20 chars long!</li>
					<li> Passwords can contain the following chars: a-z A-Z 0-9 ~!@#$%^&*()_+|-=\[]:";&lt;%gt;?,./</li>
{if $allow_dupe_email}
					<li> registering multiple accounts using the same eMail-address is <b>allowed</b>.</li>
{else}
					<li> registering multiple accounts using the same eMail-address is <b>forbidden</b>.</li>
{/if}
				</ul>
			</p>
{/if}

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
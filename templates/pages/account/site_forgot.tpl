{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Password Reminder</h1>

			<div class="column1-unit">

{if $message.type neq "ok"}
				<p>If you've forgotten your login details, don't worry, they can be retrieved. To start this process please enter your username below.</p>
{/if}
{if count($message.messages) > 0}
				<div class="{$message.type}">
					<ul>
				{foreach item=text from=$message.messages}
						<li>{$text}</li>
				{/foreach}
					</ul>
				</div>
{/if}
{if $message.type neq "ok"}
				<div class="simpleform">
					<form method="post" action="{$script_url}?act=forgot">
						<input type="hidden" name="forgot" value="doit" />
						<p><label for="username" class="left">Username:</label>
							<input type="text" name="username" id="username" class="shortfield" value="{$form_username}" tabindex="1" /></p>
						<p><img src="{$script_url}?act=getcaptcha" alt="Captcha" style="margin-left:114px;" /></p>
						<div style="clear:both;"></div>
						<p><label for="captcha" class="left">Code:</label>
							<input type="text" name="captcha" id="captcha" class="shortfield" value="" tabindex="2" /></p>
						<p><input type="submit" name="submit" id="submit" class="button" value="OK" tabindex="3" /></p>
						<p>&nbsp;</p>
					</form>
				</div>              
{/if}
			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
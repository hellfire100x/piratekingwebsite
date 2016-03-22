{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Login</h1>

{if count($message.messages) > 0}
			<div class="{$message.type}">
				<ul>
			{foreach item=text from=$message.messages}
					<li>{$text}</li>
			{/foreach}
				</ul>
			</div>
{/if}

			<div class="simpleform">
				<form method="post" action="{$script_url}?act=login">
					<input type="hidden" name="login" value="doit" />
					<p><label for="username" class="left">Username:</label>
						<input type="text" name="username" id="username" class="shortfield" value="{$form_username}" tabindex="1" /></p>
					<p><label for="password" class="left">Password:</label>
						<input type="password" name="password" id="password" class="shortfield" value="" tabindex="2" autocomplete="off" /></p>
					<p><input type="submit" name="submit" id="submit" class="button" value="Login" tabindex="3" /></p>
					<p>&nbsp;</p>
				</form>
			</div>              

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
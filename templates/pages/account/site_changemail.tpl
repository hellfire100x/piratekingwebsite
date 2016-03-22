{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Change eMail-Address</h1>

			<p>Please fill out the form below to change your email-addressd!</p>

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
						<input type="hidden" name="sact" value="changemail" />
						<p><label for="email" class="left">New eMail:</label>
							<input type="text" name="email" id="email" class="shortfield" value="{$form_email}" tabindex="1" /></p>
						<p><img src="{$script_url}?act=getcaptcha" alt="Captcha" style="margin-left:114px;" /></p>
						<div style="clear:both;"></div>
						<p><label for="captcha" class="left">Code:</label>
							<input type="text" name="captcha" id="captcha" class="shortfield" value="" tabindex="2" /></p>
						<p><input type="submit" name="submit" id="submit" class="button" value="OK" tabindex="3" /></p>
						<p>&nbsp;</p>
					</form>
				</div>              
			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
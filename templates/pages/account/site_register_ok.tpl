{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Registration OK</h1>

			<div class="column1-unit">
			<p>Your account has successfully been created.</p>
{if $show_mail_info}
			<p style="color:#b00;"><b>An eMail with an activation-link was sent to your email-address. Please check your emails for further informations.</b></p>
{else}
			<p>You can now <a href="{$script_url}?act=login">login</a> using your username and password.</p>
{/if}
			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
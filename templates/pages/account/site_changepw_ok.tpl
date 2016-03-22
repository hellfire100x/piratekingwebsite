{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Password Changed</h1>

			<div class="column1-unit">
			<p>Your account-password was successfully changed.</p>
			<p>You've been logged out for security reasons! Feel free to <a href="{$script_url}?act=login">login</a> again now using your <b>new</b> password.</p>
			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
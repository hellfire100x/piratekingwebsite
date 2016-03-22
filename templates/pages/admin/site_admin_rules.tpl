{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Manage Rules</h1>

			<p>You can edit your server-rules in the form below. Be <b>very</b> careful!</p>

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
						<input type="hidden" name="act" value="admin" />
						<input type="hidden" name="sact" value="rules" />
						<p><textarea name="rules" cols="50" rows="10" style="width:460px;height:300px;" tabindex="1">{$rules}</textarea></p>
						<p><input type="submit" name="submit" id="submit" class="button" value="OK" tabindex="2" /></p>
						<p>&nbsp;</p>
					</form>
				</div>              
			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
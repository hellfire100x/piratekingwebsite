{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Add News-Category</h1>

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
{if $skip_form neq true}
				<div class="simpleform">
					<form method="post" action="{$script_url}">
						<input type="hidden" name="act" value="admin" />
						<input type="hidden" name="sact" value="ncategoryadd" />
						<input type="hidden" name="add" value="1" />
						<p><label for="n_category" class="left">Category:</label>
							<input type="text" name="n_category" id="n_category" class="field" value="{$form_category}" tabindex="1" style="width:340px;" /></p>
						<p><input type="submit" name="submit" id="submit" class="button" value="Add Category" tabindex="2" /></p>
						<p>&nbsp;</p>
					</form>
				</div>
{/if}
			</div>
		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
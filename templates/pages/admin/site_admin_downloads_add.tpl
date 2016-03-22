{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Add Download</h1>

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
						<input type="hidden" name="sact" value="downloadadd" />
						<input type="hidden" name="add" value="1" />
						<p><label for="dl_category" class="left">Category:</label>
							<select name="dl_category" id="dl_category" tabindex="1">
								{html_options values=$form_category_ids output=$form_category_names selected=$form_category_id}
							</select></p>
						<p><label for="dl_name" class="left">Name:</label>
							<input type="text" name="dl_name" id="dl_name" class="field" value="{$form_name}" tabindex="2" style="width:340px;" /></p>
						<p><label for="dl_description" class="left">Description:</label>
							<textarea name="dl_description" cols="50" rows="10" style="width:340px;height:300px;" tabindex="3">{$form_description}</textarea></p>
						<p><label for="dl_size" class="left">Size:</label>
							<input type="text" name="dl_size" id="dl_size" class="shortfield" value="{$form_size}" tabindex="4" style="width:150px" /> Bytes</p>
						<p><label for="dl_url" class="left">URL:</label>
							<input type="text" name="dl_url" id="dl_url" class="field" value="{$form_url}" tabindex="5" style="width:340px;" /></p>
						<p><input type="submit" name="submit" id="submit" class="button" value="Add" tabindex="5" /></p>
						<p>&nbsp;</p>
					</form>
				</div>
{/if}
			</div>
		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
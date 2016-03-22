{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Edit News-Entry</h1>

			<div class="column1-unit">

				<p>Keep in mind: You can add HTML to your news-entries but be very careful
				   when doing so as the text is outputted <b>without</b> any filtering! So if you mess
				   up the HTML there, your site will be messed up, too.</p>

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
					<form method="post" action="{$script_url}?act=admnewsedit">
						<input type="hidden" name="act" value="admin" />
						<input type="hidden" name="sact" value="newsedit" />
						<input type="hidden" name="id" value="{$form_id}" />
						<p><label for="news_category" class="left">Category:</label>
							<select name="news_category" id="news_category" tabindex="1">
								{html_options values=$form_category_ids output=$form_category_names selected=$form_category_id}
							</select></p>
						<p><label for="news_headline" class="left">Headline:</label>
							<input type="text" name="news_headline" id="news_headline" class="field" value="{$form_headline}" tabindex="1" style="width:340px;" /></p>
						<p><label for="news_body" class="left">News-Text:</label>
							<textarea name="news_body" cols="50" rows="10" style="width:340px;height:300px;" tabindex="2">{$form_body}</textarea></p>
						<p><label for="news_date" class="left">Date:</label>
							<input type="text" name="news_date" id="news_date" class="shortfield" value="{$form_date|date_format:"%Y-%m-%d %H:%M:%S"}" tabindex="3" style="width:150px" /></p>
						<p><label for="news_author" class="left">Author:</label>
							<select name="news_author" id="news_author" tabindex="4">
								{html_options values=$author_ids output=$author_names selected=$author_id}
							</select></p>
						<p><input type="submit" name="submit" id="submit" class="button" value="Edit" tabindex="5" /></p>
						<p>&nbsp;</p>
					</form>
				</div>              

			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
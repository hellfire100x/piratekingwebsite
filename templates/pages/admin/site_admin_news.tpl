{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Manage News</h1>

			<div class="column1-unit">

				<p>Here you can manage your news. You can add HTML to your news-entries but be very careful
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

				<table>
					<tr>
						<th scope="col" class="top">Headline</th>
						<th scope="col" class="top" style="width:90px;">Category</th>
						<th scope="col" class="top" style="width:70px;">Date</th>
						<th scope="col" class="top" style="width:50px;">Action</th>
					</tr>
{if count($news) gt 0}
			{foreach item=entry from=$news}
					<tr>
						<td><a href="{$script_url}?act=admin&sact=newsedit&id={$entry.id}">{$entry.headline}</a></td>
						<td style="text-align:center">{$entry.category}</td>
						<td style="text-align:center">{$entry.date|date_format:"%Y-%m-%d<br />%H:%M:%S"}</td>
						<td style="text-align:center"><a href="{$script_url}?act=admin&sact=newsdelete&id={$entry.id}">delete</a></td>
					</tr>
			{/foreach}
{else}
					<tr>
						<td colspan="4">No news found.</td>
					</tr>
{/if}
					<tr>
						<td colspan="4"><a href="{$script_url}?act=admin&sact=newsadd">Add News-Entry</a></td>
					</tr>
				</table>

			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
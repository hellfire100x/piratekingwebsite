{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Downloads</h1>

{if count($message.messages) > 0}
			<div class="{$message.type}">
				<ul>
			{foreach item=text from=$message.messages}
					<li>{$text}</li>
			{/foreach}
				</ul>
			</div>
{/if}

			<h1>Category</h1>
			<ul>
			{foreach item=category from=$categories}
				<li><a href="{$script_url}?act=downloads&cid={$category.category_id}">{$category.category}</a></li>
			{/foreach}
			</ul>

{if count($files) neq 0}
			<h1>Files</h1>
			{foreach item=file from=$files}
			<table>
				<tr>
					<th scope="col" colspan="2" class="top"><b>{$file.name}</b></th>
				</tr>
				<tr>
					<td colspan="2">{$file.description}</td>
				</tr>
				<tr>
					<td style="text-align:center">{$file.size}</td>
					<td style="text-align:center"><a href="{$file.url}">Download</a></td>
				</tr>
			</table>
			{/foreach}
{/if}


		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
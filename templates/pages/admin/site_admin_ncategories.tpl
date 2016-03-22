{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Manage News-Categories</h1>

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

				<table>
					<tr>
						<th scope="col" class="top">Name</th>
						<th scope="col" class="top" style="width:70px;">Action</th>
					</tr>
{if count($categories) gt 0}
			{foreach item=entry from=$categories}
					<tr>
						<td><a href="{$script_url}?act=admin&sact=ncategoryedit&id={$entry.id}">{$entry.category}</a></td>
						<td style="text-align:center"><a href="{$script_url}?act=admin&sact=ncategorydelete&id={$entry.id}">delete</a></td>
					</tr>
			{/foreach}
{else}
					<tr>
						<td colspan="2">No categories found.</td>
					</tr>
{/if}
					<tr>
						<td colspan="2"><a href="{$script_url}?act=admin&sact=ncategoryadd">Add Category</a></td>
					</tr>
				</table>

			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
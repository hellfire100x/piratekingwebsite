{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Manage Downloads</h1>

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
						<th scope="col" class="top" style="width:150px;">Category</th>
						<th scope="col" class="top" style="width:70px;">Action</th>
					</tr>
{if count($downloads) gt 0}
			{foreach item=entry from=$downloads}
					<tr>
						<td><a href="{$script_url}?act=admin&sact=downloadedit&id={$entry.id}">{$entry.name}</a></td>
						<td>{$entry.category}</td>
						<td style="text-align:center"><a href="{$script_url}?act=admin&sact=downloaddelete&id={$entry.id}">delete</a></td>
					</tr>
			{/foreach}
{else}
					<tr>
						<td colspan="3">No downloads found.</td>
					</tr>
{/if}
					<tr>
						<td colspan="3"><a href="{$script_url}?act=admin&sact=downloadadd">Add Download</a></td>
					</tr>
				</table>

			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
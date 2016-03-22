{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Your Guilds</h1>

{if count($guilds) gt 0}
			<table>
				<tr>
					<th scope="col" class="top">Name</th>
					<th scope="col" class="top">Motto</th>
					<th scope="col" class="top" style="width:70px;">Members</th>
					<th scope="col" class="top" style="width:70px;">Applicants</th>
				</tr>
			{foreach item=guild from=$guilds}
				<tr>
					<td><a href="{$script_url}?act=account&sact=guildview&id={$guild.id}">{$guild.name}</a></td>
					<td>{$guild.motto}</td>
					<td>{$guild.members}</td>
					<td>{$guild.applicants}</td>
				</tr>
			{/foreach}
			</table>
			<p></p>
			<p>Click on the guild-name to edit that guild.</p>
{else}
			<p>You're not leader of any guild!</p>
{/if}

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
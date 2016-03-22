{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Rankings</h1>
			<a href="{$script_url}?act=ranking&rank=exp">Top Pirates</a> {if $show_richest}:: <a href="{$script_url}?act=ranking&rank=gold">Richest Pirates</a> {/if}:: Top Guilds
			<h1>Top Guilds</h1>

			<table>
			<tr>
				<th scope="col" class="top" width="30">#</th>
				<th scope="col" class="top">Guild</th>
				<th scope="col" class="top">Leader</th>
			</tr>
{if count($guilds) gt 0}
			{foreach item=guild from=$guilds key=pos}
				<tr>
					<td>{$pos}</td>
					<td><b>{$guild.name}</b>
					{if $guild.motto neq ""}
					<br /><small>( {$guild.motto} )</small>
					{/if}
					</td>
					<td>{$guild.leader}</td>
				</tr>
			{/foreach}
{else}
				<tr>
					<td colspan="3">No guilds found.</td>
				</tr>
{/if}
			</table>

			<p></p>
			<p><b>Attention</b>: The Top-Guilds are based on the number of members! The experience, wealth, etc. don't play any role in this list!</p>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
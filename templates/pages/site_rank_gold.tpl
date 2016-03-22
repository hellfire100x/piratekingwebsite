{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Rankings</h1>
			<a href="{$script_url}?act=ranking&rank=exp">Top Pirates</a> :: Richest Pirates :: <a href="{$script_url}?act=ranking&rank=guild">Top Guilds</a>
			<h1>Richest Pirates</h1>

			<table>
			<tr>
				<th scope="col" class="top" width="30">#</th>
				<th scope="col" class="top">Player</th>
				<th scope="col" class="top" width="70">Gold</th>
				<th scope="col" class="top">Guild</th>
				<th scope="col" class="top">Char/Job</th>
			</tr>
{if count($players) gt 0}
			{foreach item=player from=$players key=pos}
				<tr>
					<td>{$pos}</td>
					<td><b>{$player.name}</b></td>
					<td>{$player.gold}</td>
					<td>
						{if $player.guild neq ""}
							{$player.guild}
						{else}
							-none-
						{/if}
					</td>
					<td>{$player.char}<br />{$player.job}</td>
				</tr>
			{/foreach}
{else}
				<tr>
					<td colspan="5">No players found!?</td>
				</tr>
{/if}
			</table>

			<p></p>
			<p><b>Attention</b>: The Top-Pirates are <b>solely</b> based on the player's experience. Wealth, PvP-wins, etc. are not taken into account.</p>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Your Characters</h1>

{if count($characters) gt 0}
			{foreach item=character from=$characters}
			<table>
				<tr>
					<td rowspan="3" width="76"><a href="{$script_url}?act=account&sact=chardetail&id={$character.id}"><img src="{$base_url}img/chars/{$character.picture}" style="border:0" /></a></td>
					<th scope="col" colspan="2"><a href="{$script_url}?act=account&sact=chardetail&id={$character.id}">{$character.name}</a></th>
				</tr>
				<tr>
					<td>Lv {$character.level}</td>
					<td>{$character.job}</td>
				</tr>
				<tr>
					<td>{$character.gold} Gold</td>
					<td>AP/TP left: {$character.ap} - {$character.tp}</td>
				</tr>
			</table>
			{/foreach}
			<p></p>
			<p>Click on the picture or name to see detailed informations on that char.</p>
{else}
			<p>No characters found!?</p>
{/if}

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
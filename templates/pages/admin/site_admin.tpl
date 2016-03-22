{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Admin Dashboard</h1>

			<h1>Statistics</h1>
			<table>
				<tr>
					<th class="top" style="width:200px">Currently Logged In</td>
					<td>{$admstats.login}</td>
				</tr>
				<tr>
					<th class="top">Currently Playing</td>
					<td>{$admstats.play}</td>
				</tr>
				<tr>
					<th class="top">Unique MACs</td>
					<td>{$admstats.unique_mac}</td>
				</tr>
				<tr>
					<th class="top">New Chars in the last 24h</td>
					<td>{$admstats.new_players}</td>
				</tr>
{if 0 eq 1}
This doesn't work at the moment!
				<tr>
					<th class="top">New Accounts in the last 24h</td>
					<td>{$admstats.new_accounts}</td>
				</tr>
{/if}
				<tr>
					<th class="top">Total Accounts</td>
					<td>{$admstats.acc_count}</td>
				</tr>
				<tr>
					<th class="top">Total Characters</td>
					<td>{$admstats.players}</td>
				</tr>
			</table>

			<h1>Users online today</h1>
			<p><img src="{$script_url}?act=graph&sact=onlinetoday" style="bordeR:0px;" alt="Users online today" /></p>
			<p style="text-align:center"><span style="color:#c8e664;"><b>Logged In</b></span> - <span style="color:#c86400;"><b>Playing</b></span></p>

			<h1>Users online yesterday</h1>
			<p><img src="{$script_url}?act=graph&sact=onlineyesterday" style="bordeR:0px;" alt="Users online yesterday" /></p>
			<p style="text-align:center"><span style="color:#c8e664;"><b>Logged In</b></span> - <span style="color:#c86400;"><b>Playing</b></span></p>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
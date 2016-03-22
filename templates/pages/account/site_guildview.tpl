{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">View Guild {$guild.guild_name}</h1>


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

				<h2>Guild-Members</h2>
				<table>
					<tr>
						<th scope="col" class="top">Name</th>
						<th scope="col" class="top" style="width:40px;">Level</th>
						<th scope="col" class="top" style="width:85px;">Char/Job</th>
						<th scope="col" class="top" style="width:45px;">Status</th>
						<th scope="col" class="top" style="width:90px;">Action</th>
					</tr>
{if count($members) gt 0}
	{foreach item=member from=$members}
					<tr>
						<td>{$member.name}</a></td>
						<td>{$member.level}</td>
						<td>{$member.char}<br />{$member.job}</td>
						<td>
						{if $member.online}
							<span style="color:#090;">online</span>
						{else}
							<span style="color:#900;">offline</span>
						{/if}
						</td>
						<td>
						{if $member.online}
							&nbsp;
						{else}
							<a href="{$script_url}?act=account&sact=guildkick&id={$member.id}&gid={$guild.guild_id}" class="confirm">kick</a>
						{/if}
						</td>
					</tr>
	{/foreach}
{else}
					<tr>
						<td colspan="5">This guild has no members!?</a></td>
					</tr>
{/if}
				</table>
				
				<h2>Guild-Applicants</h2>
				<table>
					<tr>
						<th scope="col" class="top">Name</th>
						<th scope="col" class="top" style="width:40px;">Level</th>
						<th scope="col" class="top" style="width:85px;">Char/Job</th>
						<th scope="col" class="top" style="width:45px;">Status</th>
						<th scope="col" class="top" style="width:90px;">Action</th>
					</tr>
{if count($applicants) gt 0}
	{foreach item=member from=$applicants}
					<tr>
						<td>{$member.name}</a></td>
						<td>{$member.level}</td>
						<td>{$member.char}<br />{$member.job}</td>
						<td>
						{if $member.online}
							<span style="color:#090;">online</span>
						{else}
							<span style="color:#900;">offline</span>
						{/if}
						</td>
						<td>
						{if $member.online}
							&nbsp;
						{else}
							<a href="{$script_url}?act=account&sact=guildapprove&id={$member.id}&gid={$guild.guild_id}" class="confirm">approve</a><br />
							<a href="{$script_url}?act=account&sact=guilddecline&id={$member.id}&gid={$guild.guild_id}" class="confirm">decline</a>
						{/if}
						</td>
					</tr>
	{/foreach}
{else}
					<tr>
						<td colspan="5">This guild has no applicants.</a></td>
					</tr>
{/if}
				</table>

			</div>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
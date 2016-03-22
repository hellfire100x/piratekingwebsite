{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Your Account</h1>

{if count($message.messages) gt 0}
			<div class="{$message.type}">
				<ul>
			{foreach item=text from=$message.messages}
					<li>{$text}</li>
			{/foreach}
				</ul>
			</div>
{/if}

			<p>Welcome to your {$servername}-account.</p>

			<table>
				<tr>
					<th class="top" style="width:90px">Last Login</td>
{if $account.last_login neq ""}
					<td><b>{$account.last_login|date_format:"%Y-%m-%d %H:%M:%S"}</b> from <b>{$account.last_login_ip}</b></td>
{else}
					<td><b>never</b></td>
{/if}
				</tr>
				<tr>
					<th class="top" style="width:140px">Last Logout</td>
{if $account.last_logout neq ""}
					<td><b>{$account.last_logout|date_format:"%Y-%m-%d %H:%M:%S"}</b></td>
{else}
					<td><b>never</b></td>
{/if}
				</tr>
				<tr>
					<th class="top" style="width:140px">eMail</td>
					<td><b>{$account.email}</b></td>
				</tr>
				<tr>
					<th class="top" style="width:140px">GM-Level</td>
					<td><b>{$account.gm}</b></td>
				</tr>
				<tr>
					<th class="top" style="width:140px">Time online</td>
{if trim($account.time_online) neq ""}
					<td><b>{$account.time_online}</b></td>
{else}
					<td><b>never</b></td>
{/if}
				</tr>
			</table>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
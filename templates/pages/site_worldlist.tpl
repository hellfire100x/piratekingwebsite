{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Map-List</h1>

			<p>Please choose the map you want to view from the following list:</p>
			<p>
				<ul>
				{foreach item=map from=$maps key=id}
					<li> <a href="{$script_url}?act=world&map={$id}">{$map.caption}</a></li>
				{/foreach}
				</ul>
			</p>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
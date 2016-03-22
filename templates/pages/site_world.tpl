{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Map of {$map.name}</h1>

			<p>
			<img id="map" src="{$base_url}img/map/{$map.file}" width="470" height="470" border="0" usemap="#map">
			<map name="map">
				{foreach item=zone from=$zones}
				  <area shape="{$zone.type}" coords="{$zone.coords}" href="#" alt="{$zone.caption}" title="{$zone.caption}">
				{/foreach}
			</map>

			<img id="mapdefm" src="{$base_url}img/map/{$map.mini}" width="235" height="235" border="0" usemap="#mapdefm" style="margin-left:117px">
			<map name="mapdefm">
				{foreach item=zone from=$minizones}
					<area shape="{$zone.type}" coords="{$zone.coords}" href="{$zone.url}" alt="{$zone.caption}" title="{$zone.caption}">
				{/foreach}
			</map>
			</p>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
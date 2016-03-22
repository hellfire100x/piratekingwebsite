{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">News</h1>

{if count($recent) gt 0}

			<!-- Content unit - One column -->
			<div class="column1-unit">
		{foreach item=curnews from=$recent}
				<h1>[{$curnews.category}] {$curnews.headline}</h1>                            
				<h3>by {$curnews.author} on {$curnews.date|date_format:"%A, %B %e, %Y"}</h3>
				<p>{$curnews.body|nl2br}</p>
		{/foreach}
			</div>
			<hr class="clear-contentunit" />          

	{if count($news) gt 0}

			<h1>Older news:</h1>

			<!-- Content unit - One column -->
			<div class="column1-unit">
				<ul>
		{foreach item=curnews from=$news}
					<li>[{$curnews.date|date_format:"%Y-%m-%d / %H:%M"}] <a href="{$script_url}?act=newsview&id={$curnews.news_id}">{$curnews.headline}</a></li>
		{/foreach}
				</ul>
		{if $show_archive}
				<p><a href="{$script_url}?act=news">Show all news.</a></p>
		{/if}
			</div>                                    

	{/if}

{else}

			<p>No news available.</p>

{/if}
		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
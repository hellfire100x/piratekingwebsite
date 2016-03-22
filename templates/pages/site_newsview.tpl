{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">News</h1>

			<!-- Content unit - One column -->
			<div class="column1-unit">
				<h1>[{$news.category}] {$news.headline}</h1>                            
				<h3>by {$news.author} on {$news.date|date_format:"%A, %B %e, %Y"}</h3>
				<p>{$news.body|nl2br}</p>
			</div>
			<hr class="clear-contentunit" />          

			<p><a href="{$script_url}?act=news&cid={$current_category}">Back to News-Archive</a></p>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
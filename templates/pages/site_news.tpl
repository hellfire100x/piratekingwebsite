{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">News Archive</h1>

			<!-- Content unit - One column -->
			<div class="column1-unit">
{if $news neq false}
				<ul>
		{foreach item=curnews from=$news}
					<li><a href="index.php?act=newsview&id={$curnews.id}&cid={$current_category}">[{$curnews.category}] {$curnews.headline}</a><br /><small>({$curnews.date|date_format:"%Y-%m-%d / %H:%M"} by {$curnews.author})</small></li>
		{/foreach}
				</ul>
{else}
				<p>No News in archive.</p>
{/if}
			</div>                                    


		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
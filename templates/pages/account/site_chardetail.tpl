{include file='file:core/header.tpl'}

	<!-- B. MAIN -->
	<div class="main">

		{include file='file:sidebars/left.tpl'}
 
		<!-- B.2 MAIN CONTENT -->
		<div class="main-content">
        
			<!-- Pagetitle -->
			<h1 class="pagetitle">Character Detail</h1>

			<h1>{$character.name}</h1>
{literal}
		<style>

			/* DEFINITION LIST PROGRESS BAR */
			
			dl, dt, dd{margin:0;padding:0;}
			
			dd{
				width:216px;
				height:41px;
				background:url({/literal}{$base_url}{literal}img/bg_bar.gif) no-repeat 0 0;
				position:relative;
			}
			dd span{
				position:absolute;
				display:block;
				width:200px;
				height:25px;
				background:url({/literal}{$base_url}{literal}img/bar.gif) no-repeat 0 0;
				top:8px;
				left:8px;
				overflow:hidden;
				text-indent:-8000px;
			}
			dd em{
				position:absolute;
				display:block;
				width:200px;
				height:25px;
				background:url({/literal}{$base_url}{literal}img/bg_cover.gif) repeat-x;
				top:0;
			}


		/* SINGLE PROGRESS BAR */
		
		.progressBar{
			width:216px;
			height:41px;
			background:url({/literal}{$base_url}{literal}img/bg_bar.gif) no-repeat 0 0;
			position:relative;
		}

		.progressBar span{
			position:absolute;
			display:block;
			width:200px;
			height:25px;
			background:url({/literal}{$base_url}{literal}img/bar.gif) no-repeat 0 0;
			top:8px;
			left:8px;
			overflow:hidden;
			text-indent:-8000px;
		}

		.progressBar em{
			position:absolute;
			display:block;
			width:200px;
			height:25px;
			background:url({/literal}{$base_url}{literal}img/bg_cover.gif) repeat-x 0 0;
			top:0;
		}

	</style>
{/literal}

			<!-- Content unit - Two columns -->
			<div class="column2-unit-left">
				<dl>
					<dt>STR ({$character.str}):</dt>
					<dd>
						<span><em style="left:{$character.str*2}px"></em></span>
					</dd>
					<dt>CON ({$character.con}):</dt>
					<dd>
						<span><em style="left:{$character.con*2}px"></em></span>
					</dd>
					<dt>ACC ({$character.acc}):</dt>
					<dd>
						<span><em style="left:{$character.acc*2}px"></em></span>
					</dd>
				</dl>
			</div>
			<div class="column2-unit-right">
				<dl>
					<dt>SPR ({$character.spr}):</dt>
					<dd>
						<span><em style="left:{$character.spr*2}px"></em></span>
					</dd>
					<dt>AGI ({$character.agi}):</dt>
					<dd>
						<span><em style="left:{$character.agi*2}px"></em></span>
					</dd>
{if 1}
					<dt>LUK ({$character.luk}):</dt>
					<dd>
						<span><em style="left:{$character.luk*2}px"></em></span>
					</dd>
{/if}
				</dl>
			</div>
			<hr class="clear-contentunit" />

			<h1>General Information:</h1>

			<table>
				<tr>
					<th class="top" style="width:140px">Char-Type</td>
					<td>{$character.char}</td>
				</tr>
				<tr>
					<th class="top">Class</td>
					<td>{$character.job}</td>
				</tr>
				<tr>
					<th class="top">Level</td>
					<td>{$character.level} ({$character.exp} Exp)</td>
				</tr>
				<tr>
					<th class="top">Gold</td>
					<td>{$character.gold}</td>
				</tr>
				<tr>
					<th class="top">Guild</td>
					<td>{$character.guild}</td>
				</tr>
				<tr>
					<th class="top">Attribute-Points</td>
					<td>{$character.ap}</td>
				</tr>
				<tr>
					<th class="top">Skill-Points</td>
					<td>{$character.tp}</td>
				</tr>
			</table>

		</div>
                
		{include file='file:sidebars/right.tpl'}

	</div>

{include file='file:core/footer.tpl'}
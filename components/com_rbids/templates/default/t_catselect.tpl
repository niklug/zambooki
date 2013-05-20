{set_css}
{include file='js/t_javascript_language.tpl'}
	<h3 style="color:#FAA000; text-decoration:underline;">Select category</h3>
<form name="category_select" action="{$ROOT_HOST}index.php?option={$option}" method="post">
<input type="hidden" name="option" value="{$option}"/>
<input type="hidden" name="task" value="form"/>
<input type="hidden" name="Itemid" value="{$Itemid}"/>

	<div class="category_select_toolbox">
		<a href="javascript:document.category_select.submit();">
			<img src="{$IMAGE_ROOT}arrow-down2.png" border="0" alt="Select" /> 
			<span id="button_up">{"COM_RBIDS_SELECT_CATEGORY"|translate}</span>
		</a>
	</div>

	<table class="auction_categories_select" border="0" width="100%">
	{foreach from=$categories key=key item=category}
	<tr class="cat_row_{cycle values="1,2"}">
		<td>
				<div id="cat_{$category->id}" style="display:none;">{$category->description}</div>
				<input type="radio" name="category" value="{$category->id}" onclick="toggleButton();" {if $cat==$category->id} checked="checked" {/if} />
				<sup>|_</sup>
				{section name="cur" loop=$category->depth-1}
					&nbsp;&nbsp;&nbsp;&nbsp;
				{/section}
				<span {if $category->description } class="hasTip" title="{$category->description}"{/if} >
					{$category->catname}
				</span>
		</td>
	</tr>
	{/foreach}
	</table>

</form>
{import_js_block}
	var CatList = Array(10000);
	{foreach from=$categories key=key item=category}
			CatList[{$category->id}] = '{$category->catname}';
	{/foreach}

{literal}    
    function toggleButton(){
    	document.getElementById('button_up').innerHTML = language["bid_post_in_cat"]
    	document.getElementById('button_dwn').innerHTML = language["bid_post_in_cat"]
    }
{/literal}
{/import_js_block}
	<div class="category_select_toolbox">
	<a href="javascript:document.category_select.submit();">
		<img src="{$IMAGE_ROOT}arrow-up2.png" alt="Select" border="0" /> <span id="button_dwn">{"COM_RBIDS_SELECT_CATEGORY"|translate}</span>
	</a>
	</div>

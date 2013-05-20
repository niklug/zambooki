{set_css}
{import_js_file url="auctions.js"}

<h2>{$page_title}</h2>
<div align = "right" style = "text-align:right;">
    <ul id = "auction_tabmenu">
        <li>
            <a class = "{if ('categories' == $task)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&task=categories&Itemid={$Itemid}">
	    {"COM_RBIDS_VIEW_CATEGORIES_CLASICAL"|translate}</a>
        </li>
        <li>
            <a class = "{if ('tree' == $task)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&task=tree&Itemid={$Itemid}">
	    {"COM_RBIDS_VIEW_CATEGORIES_TREE"|translate}</a>
        </li>
    </ul>
</div>
{foreach from=$categories item=category}
<div class = "auction_treecat">
    <div class = "cat_link">
        <a href = "{$category->link}">{$category->catname}</a>

        <a href = "{$category->link}">
            <img src = "{$IMAGE_ROOT}category.gif" border = "0" alt = "" /></a>
        <a href = "{$category->view}">
            <img src = "{$IMAGE_ROOT}product.gif" border = "0" alt = "" /></a>
	    {if $is_logged_in}
                <a href = "{$category->link_watchlist}">
			{if $category->watchListed_flag}
                            <img src = "{$IMAGE_ROOT}f_watchlist_0.png" border = "0" alt = "" title = "{'COM_RBIDS_REMOVE_FROM_WATCHLIST'|translate}" />
				{else}
                            <img src = "{$IMAGE_ROOT}f_watchlist_1.png" border = "0" alt = "" title = "{'COM_RBIDS_ADD_TO_WATCHLIST'|translate}" />
			{/if}</a>
                <a href = "{$category->link_new_listing}"><img src = "{$IMAGE_ROOT}new_listing.png" border = "0" alt = "{"COM_RBIDS_NEW_LISTING_IN_CATEGORY"|translate}"
                                                               title = "{"COM_RBIDS_NEW_LISTING_IN_CATEGORY"|translate}" /></a>
	    {/if}
<span style="font-size: 10px;font-weight: normal;">
        ({$category->nr_a}
	    {if $category->nr_a > 1 || !$category->nr_a}
		    {"COM_RBIDS_NR_AUCTIONS"|translate}
		    {else}
		    {"COM_RBIDS_NR_AUCTION"|translate}
	    {/if}

        | {$category->subcategories|@count}
	    {if $category->subcategories|@count > 1 || !$category->subcategories|@count}
		    {"COM_RBIDS_NR_SUBCATEGORIES"|translate})
		    {else}
		    {"COM_RBIDS_NR_SUBCATEGORY"|translate})
	    {/if}
	</span>

	    {if $category->subcategories|@count > 0}
                <a style = "outline: none;" href = "#" onclick = 'jQuery("#auction_cats_{$category->id}").slideToggle("slow");return false;'><img src = "{$TEMPLATE_IMAGES}f_expand_01.png"
                                                                                                                                                  title = "{'COM_RBIDS_HIDE_SUBCATEGORIES'|translate}"
                                                                                                                                                  class = "auction_link"
                                                                                                                                                  style = "margin-left:8px;" /></a>
	    {/if}


    </div>

</div>
	{if $category->subcategories|@count>0}
        <div id = "auction_cats_{$category->id}" class = "auction_treecatsub">
	{include file="elements/category/t_subcategories.tpl" subcategories=$category->subcategories}
        </div>
	{/if}
{/foreach}

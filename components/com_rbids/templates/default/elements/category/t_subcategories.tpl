{foreach from=$subcategories item=subcategory}
<div class = "auction_treecatsub_head">
    <div class = "cat_link">
        <a href = "{$subcategory->link}">{$subcategory->catname}</a>

        <a href = "{$subcategory->link}">
            <img src = "{$IMAGE_ROOT}category.gif" border = "0" alt = "" />
        </a>
        <a href = "{$subcategory->view}">
            <img src = "{$IMAGE_ROOT}product.gif" border = "0" alt = "" />
        </a>
	    {if $is_logged_in}
                <a href = "{$subcategory->link_watchlist}">
			{if $subcategory->watchListed_flag}
                            <img src = "{$IMAGE_ROOT}f_watchlist_0.png" border = "0" alt = "" title = "{'COM_RBIDS_REMOVE_FROM_WATCHLIST'|translate}" />
				{else}
                            <img src = "{$IMAGE_ROOT}f_watchlist_1.png" border = "0" alt = "" title = "{'COM_RBIDS_ADD_TO_WATCHLIST'|translate}" />
			{/if}
                </a>
                <a href = "{$subcategory->link_new_listing}"><img src = "{$IMAGE_ROOT}new_listing.png" border = "0" alt = "{"COM_RBIDS_NEW_LISTING_IN_CATEGORY"|translate}"
                                                                  title = "{"COM_RBIDS_NEW_LISTING_IN_CATEGORY"|translate}" /></a>
	    {/if}
        <span style = "font-weight: normal;font-size: 10px;">({$subcategory->nr_a}
		{if $subcategory->nr_a > 1 || !$subcategory->nr_a}
			{"COM_RBIDS_NR_AUCTIONS"|translate}
			{else}
			{"COM_RBIDS_NR_AUCTION"|translate}
		{/if}
            |
		{$subcategory->subcategories|@count}
		{if $subcategory->subcategories|@count > 1 || !$subcategory->subcategories|@count}
			{"COM_RBIDS_NR_SUBCATEGORIES"|translate})
			{else}
			{"COM_RBIDS_NR_SUBCATEGORY"|translate})
		{/if}
        </span>
    </div>

</div>
<div id = "auction_cats_{$subcategory->id}" class = "auction_treecatsub">
	{if $subcategory->subcategories|@count>0}
			{include file="elements/category/t_subcategories.tpl" subcategories=$subcategory->subcategories}
		{/if}
</div>
{/foreach}

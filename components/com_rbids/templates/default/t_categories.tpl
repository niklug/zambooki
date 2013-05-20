{* revised 1.5.4 *}
{set_css}
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
{* Alphabet filter *}
<div style = "text-align: center;">{$filter_letter}</div>

{if $current_cat}
<div class = "auction_cat_breadcrumb">
    <a href = "{$links->getCategoryRoute()}">{"COM_RBIDS_ALL"|translate}</a>
    <strong>{$current_cat->catname}</strong>
	{if $current_cat->description}
            <div class = "auction_cat_description">
		    {$current_cat->description}
            </div>
	{/if}
</div>
{/if}
<table class = "auction_categories" cellspacing = "0" cellpadding = "3">
{if $categories|@count==0}
    <tr>
        <td>{"COM_RBIDS_THERE_ARE_NO_SUBCATEGORIES"|translate}</td>
    </tr>
	{else}
	{section name=category loop=$categories}
		{if $smarty.section.category.rownum is odd}
                <tr>
		{/if}
            <td width = "50%" valign = "top">
                <div class = "auction_maincat">
                    <a href = "{$categories[category]->link}">{$categories[category]->catname}</a><span style="font-weight: normal;font-size: 12px">({$categories[category]->nr_a})</span>
			{if $categories[category]->nr_a>0}
                            <a href = "{$categories[category]->view}">
                                <img src = "{$IMAGE_ROOT}product.gif" border = "0" alt = "" /></a>
			{/if}
			{if $is_logged_in}
                            <a href = "{$categories[category]->link_watchlist}">
				    {if $categories[category]->watchListed_flag}
                                        <img src = "{$IMAGE_ROOT}f_watchlist_0.png" border = "0" alt = "" width = "16" title = "{'COM_RBIDS_REMOVE_FROM_WATCHLIST'|translate}" />
					    {else}
                                        <img src = "{$IMAGE_ROOT}f_watchlist_1.png" border = "0" alt = "" width = "16" title = "{'COM_RBIDS_ADD_TO_WATCHLIST'|translate}" />
				    {/if}
                            </a>
                            <a href = "{$categories[category]->link_new_listing}"><img src = "{$IMAGE_ROOT}new_listing.png" border = "0" alt = "{"COM_RBIDS_NEW_LISTING_IN_CATEGORY"|translate}"
                                                                                       title = "{"COM_RBIDS_NEW_LISTING_IN_CATEGORY"|translate}" /></a>
			{/if}
                </div>
                <div class = "auction_subcat_container">

			{if $categories[category]->subcategories|@count}
				{section name=subcategory loop=$categories[category]->subcategories}
                                    <div>
                                        <a href = "{$categories[category]->subcategories[subcategory]->link}"><span style="font-weight: bold;">{$categories[category]->subcategories[subcategory]->catname}</span></a>
                                        ({$categories[category]->subcategories[subcategory]->nr_a})
					    {if $categories[category]->subcategories[subcategory]->nr_a>0}
                                                <a href = "{$categories[category]->subcategories[subcategory]->view}">
                                                    <img src = "{$IMAGE_ROOT}product.gif" border = "0" alt = "" />
                                                </a>
					    {/if}

					    {if $is_logged_in}
                                                <a href = "{$categories[category]->subcategories[subcategory]->link_watchlist}">
							{if $categories[category]->subcategories[subcategory]->watchListed_flag}
                                                            <img src = "{$IMAGE_ROOT}f_watchlist_0.png" border = "0" alt = "" width = "16" title = "{'COM_RBIDS_REMOVE_FROM_WATCHLIST'|translate}" />
								{else}
                                                            <img src = "{$IMAGE_ROOT}f_watchlist_1.png" border = "0" alt = "" width = "16" title = "{'COM_RBIDS_ADD_TO_WATCHLIST'|translate}" />
							{/if}
                                                </a>
                                                <a href = "{$categories[category]->subcategories[subcategory]->link_new_listing}"><img src = "{$IMAGE_ROOT}new_listing.png" border = "0"
                                                                                                                                       alt = "{"COM_RBIDS_NEW_LISTING_IN_CATEGORY"|translate}"
                                                                                                                                       title = "{"COM_RBIDS_NEW_LISTING_IN_CATEGORY"|translate}" /></a>
					    {/if}
                                    </div>
				{/section}
			{/if}
                </div>
            </td>
		{if $smarty.section.category.rownum is not odd}
                </tr>
		{/if}
	{/section}
{/if}
</table>

<div align = "right" style = "text-align:right;">
    <ul id = "auction_tabmenu">
        <li>
            <a class = "{if ('show_search' == $task)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&task=show_search&Itemid={$Itemid}">
	    {"COM_RBIDS_VIEW_SEARCH_AUCTIONS"|translate}</a>
        </li>
        <li>
            <a class = "{if ('searchusers' == $task)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&task=searchusers&controller=user&usertype=2&Itemid={$Itemid}">
	    {"COM_RBIDS_VIEW_SEARCH_USERS"|translate}</a>
        </li>
    </ul>
</div>

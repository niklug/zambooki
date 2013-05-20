<div align = "right" style = "text-align:right;">
    <ul id = "auction_tabmenu">
        <li>
            <a class = "{if !($search)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&task=googlemaps&controller=maps&Itemid={$Itemid}">
	    {"COM_RBIDS_VIEW_MAP_LIST_AUCTIONS"|translate}</a>
        </li>
        <li>
            <a class = "{if ($search)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&task=googlemaps&controller=maps&search=1&Itemid={$Itemid}">
	    {"COM_RBIDS_VIEW_MAP_SEARCH_RADIUS"|translate}</a>
        </li>
    </ul>
</div>

<div align = "right" style = "text-align:right;">
    <ul id = "auction_tabmenu">

    {if $cfg->enable_auctiontype_invite && $isInvited}
        <li>
            <a class = "{if ('myinvitedauctions' == $task)}active{else}inactive{/if}" href = "{$links->getUserInvitedAuctionsRoute()}">
		    {"COM_RBIDS_INVITED_AUCTIONS"|translate}</a>
        </li>
    {/if}
        <li>
            <a class = "{if ('mybids' == $task)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&task=mybids&Itemid={$Itemid}">
	    {"COM_RBIDS_MY_BIDS"|translate}</a>
        </li>
        <li>
            <a class = "{if ('mywonbids' == $task)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&task=mywonbids&Itemid={$Itemid}">
	    {"COM_RBIDS_MY_WON_BIDS"|translate}</a>
        </li>

    </ul>
</div>

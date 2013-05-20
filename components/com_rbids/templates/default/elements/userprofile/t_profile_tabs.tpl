<div align = "left" style = "text-align:left;">
    <ul id = "auction_tabmenu">
        <li>
            <a class = "{if ('userdetails' == $task)}active{else}inactive{/if}" href = "{$links->getUserdetailsRoute()}">
	    {"COM_RBIDS_MY_DETAILS"|translate}</a>
        </li>
        <li>
            <a class = "{if ('payments.history' == $task)}active{else}inactive{/if}" href = "{$links->getPaymentsHistoryRoute()}">
	    {"COM_RBIDS_PAYMENT_BALANCE"|translate}</a>
        </li>
        <li>
            <a class = "{if ('myratings' == $task)}active{else}inactive{/if}" href = "{$links->getUserRatingsRoute()}">
	    {"COM_RBIDS_MY_RATINGS"|translate}</a>
        </li>
    </ul>
</div>

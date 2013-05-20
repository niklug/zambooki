
{if $index is odd}
	{assign var=class value="1"}
	{else}
	{assign var=class value="2"}

{/if}
{if $auction->featured && $auction->featured!='none'}
	{assign var=class_featured value="listing-"|cat:$auction->featured}
	{else}
	{assign var=class_featured value=""}
{/if}

{* First row *}
<tr class = "{if $class_featured}{$class_featured}{else}auction_row_{$class}{/if}">
    <td class = "auction_dt" valign = "top" colspan = "5">
        <div class = "auction_title">
	{if $class_featured}
            <img src = "{$TEMPLATE_IMAGES}f_featured.png" title = "{'COM_RBIDS_NEW_MESSAGES'|translate}" alt = "{'COM_RBIDS_NEW_MESSAGES'|translate}" />
	{/if}
	{if $auction->auction_type == $AUCTION_TYPES.AUCTION_TYPE_PRIVATE}
            <img src = "{$TEMPLATE_IMAGES}private.png" title = "{'COM_RBIDS_PRIVATE'|translate}" alt = "{'COM_RBIDS_PRIVATE'|translate}" />
	{/if}
            <a href = "{$auction->get('links.bids')}">{$auction->title}</a>
        </div>
    {positions position="cell-header" item=$auction page="auctions"}
    </td>
</tr>
{* Second row *}
<tr class = "{if $class_featured}{$class_featured}{else}auction_row_{$class}{/if}">

    <td class = "auction_dbk" valign = "bottom">
    {if $auction->shortdescription}
        <div style = "float: left;">
            &nbsp;<img src = "{$TEMPLATE_IMAGES}f_expand_01.png" title = "{'COM_RBIDS_SHOW_DESCRIPTION'|translate}" class = "auction_link"
                       alt = "{'COM_RBIDS_SHOW_DESCRIPTION'|translate}" onclick = "auctionObject.toggleDescription('auction_short_description_{$index}',this);" />
        </div>
        <div id = "auction_short_description_{$index}" class = "auction_short_description">
		{$auction->shortdescription}
        </div>
    {/if}
        <span class = "auction_category" style="{if !$auction->shortdescription}margin-left: 0;{/if}">
                    <img src = "{$TEMPLATE_IMAGES}folder.gif"
                         title = "{'COM_RBIDS_CATEGORY'|translate}"
                         alt = "{'COM_RBIDS_CATEGORY'|translate}"
                         style = "vertical-align: bottom;margin-right: -11px;"
                            />
	{if $auction->get('catname')}
            <a href = "{$auction->get('links.filter_cat')}">{$auction->get('catname')}</a>
		{else}
            &nbsp;-&nbsp;
	{/if}
		</span>
    {if $auction->has_file}
        <img src = "{$TEMPLATE_IMAGES}attach.gif"
             title = "{'COM_RBIDS_HAS_ATTACHMENTS'|translate}"
             alt = "{'COM_RBIDS_HAS_ATTACHMENTS'|translate}"
             style = "height:16px;vertical-align: middle;margin-left: 3px;"
                />
        <span style = "color:grey;font-size: 10px;">{'COM_RBIDS_FILES'|translate}</span>
    {/if}
    {if $auction->picture}
        <img src = "{$TEMPLATE_IMAGES}morepics_on.png"
             title = "{'COM_RBIDS_HAS_PICTURES'|translate}"
             alt = "{'COM_RBIDS_HAS_PICTURES'|translate}"
             style = "height:16px;vertical-align: middle;"
                />
        <span style = "color:grey;font-size: 10px;">{'COM_RBIDS_IMAGES'|translate}</span>
    {/if}

        <a href = "{$auction->get('links.otherauctions')}"
           style = "margin: 0 5px 0 5px;"
           title = "{'COM_RBIDS_MORE_AUCTIONS_FROM_THIS_USER'|translate}">
	{$auction->get('username')}</a>

    {if !$auction->isMyAuction() & $is_logged_in}
	    {if $auction->get('favorite')}
                <span class = 'add_to_watchlist'>
          			<a href = '{$auction->get('links.del_from_watchlist')}'>
                                      <img src = "{$IMAGE_ROOT}f_watchlist_0.png"
                                           style = "height:16px;vertical-align: bottom;"
                                           title = "{'COM_RBIDS_REMOVE_FROM_WATCHLIST'|translate}"
                                           alt = "{'COM_RBIDS_REMOVE_FROM_WATCHLIST'|translate}" /></a>
          		</span>
		    {else}
                <span class = "add_to_watchlist">
          			<a href = '{$auction->get('links.add_to_watchlist')}'>
                                      <img src = "{$IMAGE_ROOT}f_watchlist_1.png"
                                           style = "height:16px;vertical-align: bottom;"
                                           title = "{'COM_RBIDS_ADD_TO_WATCHLIST'|translate}"
                                           alt = "{'COM_RBIDS_ADD_TO_WATCHLIST'|translate}" /></a>
          		</span>
	    {/if}
    {/if}
    {positions position="cell-left" item=$auction page="auctions"}
    </td>
    <td class = "auction_dbk" valign = "top">
        <div style="text-align: right;">
            <!-- [+] MAX PRICE -->
			<span class = "auction_price_bold">
			{if $auction->max_price}
                            <span style = "font-size: 24px;text-align: right;">{$auction->max_price|string_format:"%.2f"}</span>&nbsp;{$auction->currency}
				{else}
				{'COM_RBIDS_NOT_SPECIFIED'|translate}
			{/if}
                        </span>
        </div>

    {positions position="cell-middle" item=$auction page="auctions"}
    </td>

    <td class = "auction_dbk" valign = "top" style = "text-align: center;">
    {if $auction->show_bidder_nr == 1}
        <span class = "hasTip" title = "{'COM_RBIDS_NR_BIDSNR_BIDDERS'|translate}" style="font-weight: bold;">
    			{if $auction->get('nr_bids')!==null}
				    {$auction->get('nr_bids')}
				    {else}
                                &nbsp;-&nbsp;
			    {/if}
            /
		{if $auction->get('nr_bidders')!==null}
			{$auction->get('nr_bidders')}
			{else}
                    &nbsp;-&nbsp;
		{/if}
                </span>
	    {else}
        -
    {/if}
        <br />
    {if $auction->get('winning_bid')}
	    {"COM_RBIDS_WINNING_BID"|translate}: {$auction->get('winning_bid')|string_format:"%.2f"}&nbsp;{$auction->currency}
	    {elseif $auction->get('lowest_price')}
	    {if $auction->show_best_bid==1}
		    {"COM_RBIDS_BEST_BID"|translate}: {$auction->get('lowest_price')|string_format:"%.2f"}&nbsp;{$auction->currency}
		    {else}
                -
	    {/if}
    {/if}
    </td>

    <td class = "auction_dbk" valign = "top" align = "right" style = "white-space: nowrap;font-weight: bold;">
        <!-- [+] Time Ending -->
    {if $auction->close_offer}
        <span class = 'canceled_on'>
		{if $auction->end_date gt $auction->closed_date && !$auction->winner_id}
			{'COM_RBIDS_CANCELED_ON'|translate}:
			{else}
			{'COM_RBIDS_CLOSED_ON'|translate}:
		{/if}
        </span>
	    {printdate date=$auction->closed_date}
	    {elseif  $auction->get('expired')}
        <span class = 'expired'>{"COM_RBIDS_EXPIRED"|translate}</span>
	    {else}
        <span class = "timer">{$auction->get('countdown')}</span>
    {/if}
        <!-- [-] Time Ending -->

    </td>
</tr>

<tr class = "{if $class_featured}{$class_featured}{else}auction_row_{$class}{/if}">
    <td colspan = "6" align = "right" class = "auction_foot">
    {positions position="cell-footer" item=$auction page="auctions"}
    </td>
</tr>
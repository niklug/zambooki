{if $is_logged_in}
	
<table id = "table_bids_list" xmlns = "http://www.w3.org/1999/html" xmlns = "http://www.w3.org/1999/html" xmlns = "http://www.w3.org/1999/html">

    <tbody>
	    {section name=bids loop=$bids}
		    {assign var=nr_cols value=3}
		    {if $bids[bids]->userid==$joomlauser->id}
		    {* Row 1 -> row number | user name :: rating :: [send message] | bid price | [accept bid] :: [verify NDA] *}
                    <tr class = "{cycle values="auction_bids_mybid1,auction_bids_mybid2"} {if $bids[bids]->accept}auction_winner{/if}">
                        <a name = 'mybid'></a>
			    {else}
                    <tr class = "{cycle values="auction_bids_list1,auction_bids_list2"} {if $bids[bids]->accept}auction_winner{/if}">
		    {/if}
            <td>
	    {* [+] inside table *}
                <table width = "100%">
		{* Row 1 -> Bid date | attachment *}
                    <tr>
                        <td style = "color:#808080;font-size: 11px;">{printdate date=$bids[bids]->modified}</td>
                        <td style = "text-align: right;" colspan = "2">
				{if ($bids[bids]->file_name !==""  && $cfg->enable_bid_attach =="1"  ) }
				{* Bid attachment is available just for auctioneer and bid owner *}
					{if $auction->isMyAuction() || $bids[bids]->userid==$joomlauser->id}
                                            <img src = "{$IMAGE_ROOT}attach.png" style = "border:0; margin:1px; vertical-align:middle;" />
                                            <a href = "{$links->getDownloadBidAttach($bids[bids]->id, $auction->id)}" target = "_blank">{$bids[bids]->file_name}</a>
					{/if}
				{/if}
                        </td>
                    </tr>
		{* Row 2 *}
                    <tr>
		    {* User name :: rating :: send message *}
                        <td style="width: 444px;">
                            <a href = "{$links->getUserdetailsRoute($bids[bids]->userid)}" title = "{'COM_RBIDS_VIEW_PROFILE'|translate}">{$bids[bids]->username}</a>

                            <span class = "rating">{$bids[bids]->rating}</span>

				{if ( $auction->get('must_rate') && $bids[bids]->accept ) }
					{if $auction->isMyAuction()}
                                            <span><a href = "{$links->getShowRateAuctionRoute($auction->id,$bids[bids]->userid)}">{"COM_RBIDS_RATE_BIDDER"|translate}</a></span>
						{else}
                                            <span><a href = "{$links->getShowRateAuctionRoute($auction->id,$auction->userid)}">{"COM_RBIDS_RATE_AUCTIONEER"|translate}</a></span>
					{/if}
				{/if}

				{if $auction->isMyAuction() && $cfg->allow_messages}
                                    <a href = "javascript:void(0);" id = "sendm"
                                       onclick = "auctionObject.SendMessage(this,0,{$bids[bids]->userid},'{$bids[bids]->username}');">({"COM_RBIDS_SEND_MESSAGE"|translate})</a>
				{/if}
                        </td>
		    {* Bid Price :: [verify NDA] *}
                        <td>
                            <span class = "bid_price">{$bids[bids]->bid_price|string_format:"%.2f"}&nbsp;{$auction->currency}</span>
				{if ($auction->NDA_file !=="" && $cfg->nda_option =="1" ) }
					{if $auction->isMyAuction() || $bids[bids]->userid == $joomlauser->id}
                                            <a href = "{$links->getDownloadUserNDA($auction->id,$bids[bids]->userid)}" target = "_blank">{"COM_RBIDS_CHECK_NDA"|translate}</a>
					{/if}
				{/if}
                        </td>
		    {* [Accept bid] *}

                        <td style = "text-align: right;">
				{if $auction->isMyAuction() && $auction->close_offer !=1 && $auction->automatic != 1 && !$cfg->select_winner_automatic == 1 }
                                    <a href = "{$links->getAcceptBid($bids[bids]->id)}" onclick = "return confirm('{'COM_RBIDS_CONFIRM_ACCEPT_BID'|translate}');">
                                        <img src = "{$IMAGE_ROOT}auctionicon16.gif" border = "0" />{"COM_RBIDS_ACCEPT"|translate}
                                    </a>
				{/if}
                        </td>
                    </tr>

		{* Row 3 -> user message *}
			{if $bids[bids]->message}
                            <tr>

                                <td colspan = "{$nr_cols}" style = "vertical-align: top;">
                                    <div class = "auction_msg_text">{$bids[bids]->message}</div>
                                </td>
                            </tr>
			{/if}
		{* [-] inside table *}
                    </td></tr></table>

		    {sectionelse}
		    {if $auction->auction_type == $AUCTION_TYPES.AUCTION_TYPE_PRIVATE}
                        <h2>{"COM_RBIDS_BIDS_ARE_PRIVATE"|translate}</h2>
			    {else}
                        <h2>{"COM_RBIDS_NO_BIDS"|translate}</h2>
		    {/if}
	    {/section}
    </tbody>
</table>
	

{/if}

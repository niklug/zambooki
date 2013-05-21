{include file='js/t_javascript_language.tpl'}
{include file='js/t_javascript_countdown.tpl'}

{* Include Tabbing Scripts *}
{createtab}
{set_css}
{if $payment_items_header}
<div class = "rbids_headerinfo" xmlns = "http://www.w3.org/1999/html">{$payment_items_header}</div>
{/if}

<table width = "100%" class = "auction_details" cellpadding = "0" cellspacing = "0">

{* Top line where is included auction category and back to listing link *}
    <tr>
        <td>
            <img src = "{$TEMPLATE_IMAGES}folder.gif" title = "{'COM_RBIDS_CATEGORY'|translate}" alt = "{'COM_RBIDS_CATEGORY'|translate}" style = "vertical-align: middle;" />
	{if $auction->get('catname')}
            <a href = "{$auction->get('links.filter_cat')}" style = "">{$auction->get('catname')}</a>
		{else}&nbsp;-&nbsp;{/if}
            <span style = "display:inline-block;float:right;"><a href = "{$auction->get('links.auctions_listing')}">{'COM_RBIDS_BACK_TO_LIST'|translate}</a></span>
        </td>
    </tr>

{*  Send bid | Auction infos *}

    <tr class = "auction_detail_text">
        <td>

	{* Title | watchlist | header custom fields | actions buttons *}
            <table width = "100%">
                <tr class = "tr_auction_title_details">
                    <td>

		    {if $auction->isMyAuction() && !$auction->published}
                        <span class = "auction_title_details"><i>{$auction->title}</i> ({"COM_RBIDS_UNPUBLISHED"|translate})</span>
			    {else}
                        <span class = "auction_title_details">{$auction->title}</span>
		    {/if}
		    {if $auction->auction_type == $AUCTION_TYPES.AUCTION_TYPE_PRIVATE}
                        <img src = "{$TEMPLATE_IMAGES}private.png" title = "{'COM_RBIDS_PRIVATE'|translate}" alt = "{'COM_RBIDS_PRIVATE'|translate}" />&nbsp;
                        ({"COM_RBIDS_PRIVATE"|translate}) {infobullet text="Private help"|translate}
		    {/if}
                    </td>
                    <td>{include file="elements/auctiondetail/t_action_buttons.tpl"}</td>
                </tr>
            </table>

	{positions position="header" item=$auction page="auctions"}

        </td>
    </tr>


    <tr>
        <td>

	{if $auction->isMyAuction()}
            <div class = "current">
	    {include file="elements/auctiondetail/t_bidstatus.tpl"}
	    {include file="elements/auctiondetail/t_paypal_button.tpl"}
            </div>
            <span class = "v_spacer_5"></span>
		{else}

		{if $auction->close_offer==1}
                    <div class = "current" style = "text-align: center;">
                        <span class = "rbids_heading_status">{"COM_RBIDS_AUCTION_CLOSED_ON"|translate}: {printdate date=$auction->closed_date}<br />
	                        {if $auction->get('i_am_winner')}
		                        {"COM_RBIDS_YOU_ARE_THE_WINNER"|translate}
	                        {/if}
                            </span>
                    </div>
                    <span class = "v_spacer_5"></span>
			{elseif $auction->get('expired')}
                    <div class = "current" style = "text-align: center;"><span class = "rbids_heading_status">{"COM_RBIDS_AUCTION_EXPIRED"|translate}</span></div>
                    <span class = "v_spacer_5"></span>
			{elseif $is_logged_in}
                    <div class = "box_bid_details">
			    {if $auction->auction_type == $AUCTION_TYPES.AUCTION_TYPE_INVITE && !$auction->isInvited()}
			        {include file="elements/auctiondetail/t_bid_not_invited.tpl"}
				    {else}
				        {include file="elements/auctiondetail/t_bid.tpl"}
			    {/if}
                    </div>
			{else}
                    <div class = "box_bid_details">{include file="elements/auctiondetail/t_bid_guest.tpl"}</div>
		{/if}
	{/if}

        </td>
    </tr>
</table>

<table width = "100%" class = "auction_details" cellpadding = "0" cellspacing = "5">
    <tr>
    {* Left column split all row for right side *}
        <td valign = "top" class = "detail_auction_description">
            <div class = "box_content">
                <div>
		{$auction->description}

                    <span class = "v_spacer_5"></span>

		{if $is_logged_in}
                    <span class = "h_spacer_5"></span>

			{if ($auction->NDA_file != '' && $uploaded_NDA != 1 && $cfg->nda_option == 1) }
                            <div class = "info_text" style = "color: Grey;">{"COM_RBIDS_IN_ORDER_TO_BID_ON_THIS_AUCTION_YOU_MUST_DOWNLOAD_THE_NDA"|translate}</div>
                            <a href = "{$auction->get('links.download_nda')}" target = "_blank">
                                <img src = "{$IMAGE_ROOT}nda_download.gif" style = "border:0; margin:1px; vertical-align:middle;" />{"COM_RBIDS_DOWNLOAD_NDA_FILE"|translate}
                            </a>
			{/if}
		{/if}

		{if $auction->file_name != ""}
                    <a href = "{$auction->get('links.download_file')}" target = "_blank">
                        <img src = "{$IMAGE_ROOT}attach.png" style = "border:0; margin-left:10px; vertical-align:middle;" />{$auction->file_name}
                    </a>
		{/if}
                    <span class = "v_spacer_5"></span>
                </div>
            </div>
            <div class = "box_bottom">
                <div>
                    <span style = "font-weight: normal;">{"COM_RBIDS_JOB_TIMEFRAME"|translate}:</span>
                    <label class = "auction_lables">
		    {if $auction->job_deadline}
					{$auction->job_deadline} {"COM_RBIDS_DAYS"|translate}
					{else}
					{"COM_RBIDS_NOT_SPECIFIED"|translate}
					{/if}
                    </label>
                </div>
            </div>
            <span class = "v_spacer_5"></span>
	{positions position="detail-left" item=$auction page="auctions"}
        <div class = "box_title">{'COM_RBIDS_LOCATION_ON_MAP'|translate}</div>
        <div class="box_content">{include file="elements/auctiondetail/t_tab_maps.tpl"}</div>
        <div class = "box_title">{'COM_RBIDS_SCREENSHOTS'|translate}</div>
        <div class="box_content">{include file="elements/auctiondetail/t_tab_screenshots.tpl"}</div>
        </td>

    {* Right side include auctioneer details and auction details *}
        <td valign = "top" class = "detail_auction_info">
            <div class = "box_title">{'COM_RBIDS_AUCTIONEER_DETAILS'|translate}</div>
            <table width = "100%" cellpadding = "0" cellspacing = "3" class = "box_right_details">
                <tr>
                    <td>
                        <label class = "auction_lables"> <a href = "{$auction->get('links.auctioneer_profile')}">{$auctioneer->username}</a></label>
                        <span class = "left_spacer_10"></span>
                        <a href = "{$auction->get('links.auctioneer_profile')}" title = "{'COM_RBIDS_DETAILS'|translate}">
                            <span class = "rating">{$auction->get('ownerrating')}</span></a>
                    </td>
                </tr>
                <tr>
                    <td>
		    {"COM_RBIDS_MEMBER_SINCE"|translate}:
                        <label class = "auction_lables">{printdate date=$auctioneer->registerDate use_hour=0}</label>
                    </td>
                </tr>
                <tr>
                    <td>
		    {if $auctioneer->city || $auctioneer->country}
			    {"COM_RBIDS_USER_LOCATION"|translate}:
                        <label class = "auction_lables">
				{$auctioneer->city}{if $auctioneer->city},{/if}
				{$auctioneer->country}
				{if !$auctioneer->country}-{/if}
                        </label>
		    {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href = "{$auction->get('links.otherauctions')}">{"COM_RBIDS_OTHER_AUCTIONS"|translate}</a>
		    {"COM_RBIDS_FROM_THIS_USER"|translate}
                    </td>
                </tr>
            </table>

            <span class = "v_spacer_5"></span>

            <div class = "box_title">{'COM_RBIDS_DETAILS'|translate}</div>
            <table width = "100%" cellpadding = "0" cellspacing = "3" class = "box_right_details">
                <tr>
                    <td>
		    {"COM_RBIDS_MAX_PRICE"|translate}:
                        <label class = "auction_lables">
			{if $auction->max_price}
				{$auction->max_price|string_format:"%.2f"}&nbsp;{$auction->currency}
				{else}
				{"COM_RBIDS_NOT_SPECIFIED"|translate}
			{/if}
                        </label>
                    </td>
                </tr>
                <tr>
                    <td><label class = "auction_lables">{$auction->get('nr_bidders')}</label>&nbsp;{"COM_RBIDS_BIDDERS"|translate}</td>
                </tr>
                <tr>
                    <td>{"COM_RBIDS_START_DATE"|translate}: <label class = "auction_lables">{$auction->get('startdate_text')}</label></td>
                </tr>
                <tr>
                    <td>
		    {"COM_RBIDS_EXPIRE_DATE"|translate}: <label class = "auction_lables">{$auction->get('enddate_text')}</label>
                    </td>
                </tr>
                <tr>
                    <td>
		    {if !$auction->get('expired') && !$auction->close_offer}
			    {"COM_RBIDS_AUCT_DETAILS_ENDING_IN"|translate}:
                        <label class = "auction_lables">
                            <span class = "timer">{$auction->get('countdown')}</span>
                        </label>
			    {elseif $auction->get('expired') && !$auction->close_offer}
			    {"COM_RBIDS_AUCT_DETAILS_ENDING_IN"|translate}:
                        <label class = "auction_lables">{"COM_RBIDS_AUCTION_EXPIRED"|translate}</label>
		    {/if}
                    </td>
                </tr>
                <tr>
                    <td><label class = "auction_lables">{$auction->hits}</label>&nbsp;{"COM_RBIDS_HITS"|translate}</td>
                </tr>
            </table>
	{positions position="detail-right" item=$auction page="auctions"}
        <div style="margin-top: 4px;" class = "box_title">{'COM_RBIDS_LIST_OF_BIDS'|translate}</div>
        <div class="box_content">{include file="elements/auctiondetail/t_tab_bids.tpl"}</div>
        </td>
    </tr>
</table>

<table width = "100%" class = "auction_details" cellpadding = "0" cellspacing = "0">
    <tr>
        <td>{positions position="middle" item=$auction page="auctions"}</td>
    </tr>
{* -------------------  TABBING PART ---------------------*}
    <tr>
        <td>
            <div style="margin-top: 4px;" class = "box_title">{'COM_RBIDS_MESSAGES'|translate}</div>
            <div class="box_content">{include file="elements/auctiondetail/t_tab_messages.tpl"}</div>
        </td>
    </tr>
    <tr>
        <td><span class = "v_spacer_15"></span>{$auction->get('links.tags')}</td>
    </tr>
    <tr>
        <td>
	{positions position="footer" item=$auction page="auctions"}
        </td>
    </tr>

</table>

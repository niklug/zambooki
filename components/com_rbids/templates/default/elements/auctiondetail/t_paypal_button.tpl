<!-- [+] Paypal Form -->
{if $auction->winner_id && $auction->isMyAuction()}
	{assign var=winner value=$auction->get('winner')}
	{if $winner->paypalemail}
        <div id = "paypal_button" style="text-align: center">
            <form name = 'paypalForm' action = "https://www.paypal.com/cgi-bin/webscr" method = "post" name = "paypal">
                <input type = "hidden" name = "cmd" value = "_xclick" />
                <input type = "hidden" name = "business" value = "{$winner->paypalemail}" />
                <input type = "hidden" name = "item_name" value = "{$auction->title}" />
                <input type = "hidden" name = "item_number" value = "{$auction->id}" />
                <input type = "hidden" name = "invoice" value = "{$auction->auction_nr}" />
                <input type = "hidden" name = "amount" value = "{$auction->get('winning_bid')|string_format:"%.2f"}" />
                <input type = "hidden" name = "return" value = "{$auction->get('links.bids')}" />
                <input type = "hidden" name = "tax" value = "0" />
                <input type = "hidden" name = "rm" value = "2" />
                <input type = "hidden" name = "no_note" value = "1" />
                <input type = "hidden" name = "no_shipping" value = "1" />
                <input type = "hidden" name = "currency_code" value = "{$auction->currency}">
                <input type = "image" src = "https://www.paypal.com/en_US/i/btn/x-click-but06.gif" name = "submit" alt = "{"COM_RBIDS_BUY_NOW"|translate}" style = "margin-left: 50px;" />
            </form>
        </div>
		{else}
        <div id = "paypal_button" style="text-align: center">
            <span class="rbids_heading_status">{'COM_RBIDS_WINNER_NO_PAYPAL_ADDRESS'|translate}</span>
        </div>
	{/if}
{/if}
<!-- [-] Paypal Form -->

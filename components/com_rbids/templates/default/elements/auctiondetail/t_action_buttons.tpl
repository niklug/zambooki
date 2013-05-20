<table width = "100%" cellpadding = "0" cellspacing = "0">
    <tr>
        <td align = "right" style = "">
            <div style = "padding:5px;">
	    {if !$auction->isMyAuction() && $auction->close_offer == 0}
		    {if $is_logged_in}
			    {if $auction->get('favorite')}
                                <span class = 'add_to_watchlist'><a href = '{$auction->get('links.del_from_watchlist')}'>
                                    <img src = "{$IMAGE_ROOT}f_watchlist_0.png" title = "{'COM_RBIDS_REMOVE_FROM_WATCHLIST'|translate}" alt = "{'COM_RBIDS_REMOVE_FROM_WATCHLIST'|translate}" /></a>
							</span>
				    {else}
                                <span class = 'add_to_watchlist'>
					<a href = '{$auction->get('links.add_to_watchlist')}'>
                                            <img src = "{$IMAGE_ROOT}f_watchlist_1.png" title = "{'COM_RBIDS_ADD_TO_WATCHLIST'|translate}"
                                                 alt = "{'COM_RBIDS_ADD_TO_WATCHLIST'|translate}" />
                                        </a>
				</span>
			    {/if}

		    {/if}
	    {/if}
	    {if $auction->isMyAuction()}
		    {if $auction->close_offer == 1}
                        <input type = "image"
                               src = "{$IMAGE_ROOT}republish_auction.png"
                               onclick = "window.location = '{$auction->get('links.republish')}';"
                               alt = "{'COM_RBIDS_REPUBLISH'|translate}"
                               title = "{'COM_RBIDS_REPUBLISH'|translate}"
                                />
			    {else}
		    {* [+] Button to load Invite Form *}
			    {$invite_button}
		    {* [-] Button to load Invite Form *}

                        <input type = "image" src = "{$IMAGE_ROOT}edit_auction.png"
                               alt = "{'COM_RBIDS_EDIT'|translate}"
                               title = "{'COM_RBIDS_EDIT'|translate}"
                               style = "border: none;"
                               onclick = "window.location = '{$auction->get('links.edit')}';"
                                />
                        <input type = "image" src = "{$IMAGE_ROOT}delete.png"
                               alt = "{'COM_RBIDS_CANCEL'|translate}"
                               title = "{'COM_RBIDS_CANCEL'|translate}"
                               style = "border: none;"
                               onclick = "
                               document.getElementById('overlay').style.display='block';
                               document.getElementById('close_auction_div').style.display='block';
                               "
                                />
		    {/if}

	    {* [+] Cancel Form Hidden *}
                <div id = "overlay" style = "display: none;">
                    <div id = "close_auction_div" style = "display:none;">
                        <form name = "cancel_auction_form" method = "POST" action = "{$auction->get('links.cancel')}">
                            <input type = "hidden" name = "id" value = "{$auction->id}" />
                            <input type = "hidden" name = "option" value = "{$option}" />
                            <input type = "hidden" name = "task" value = "cancelauction" />
                            <input type = "hidden" name = "Itemid" value = "{$Itemid}" />
                            <table>
                                <tr>

                                    <td valign = "top">
                                        <div style = "text-align: left;">{"COM_RBIDS_CANCEL_REASON"|translate}:</div>
                                        <textarea id = "cancel_reason" name = "cancel_reason" cols = "34" rows = "7"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan = "2" align = "center">
                                        <span class = "v_spacer_5"></span>
                                        <input type = "button" value = "{'COM_RBIDS_CANCEL_AUCTION'|translate}"
                                               onclick = "if(confirm('{"COM_RBIDS_ARE_YOU_SURE_YOU_WANT_TO_CANCEL"|translate}')) document.cancel_auction_form.submit();"
                                               class = "button" />
                                        <input type = "button"
                                               value = "{'COM_RBIDS_CLOSE'|translate}"
                                               onclick = "
                                               document.getElementById('close_auction_div').style.display='none';
                                               document.getElementById('overlay').style.display='none';
                                                document.getElementById('cancel_reason').value = '' "
                                               class = "button"
                                                />
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
	    {* [-] Cancel Form Hidden *}


	    {* IF not my auction*}
		    {else}
		    {if $auction->close_offer == 0}
                        <a href = '{$auction->get('links.report')}'>
                            <img src = "{$IMAGE_ROOT}report_auction.png"
                                 title = "{'COM_RBIDS_REPORT_AUCTION_AS_ABUSIVE'|translate}"
                                 alt = "{'COM_RBIDS_REPORT_AUCTION_AS_ABUSIVE'|translate}"
                                    />
                        </a>
		    {/if}
	    {/if}

            </div>

        </td>
    </tr>
{if $auction->isMyAuction()}
	{if $cfg->admin_approval && !$auction->approved}
            <tr>
                <td>
                    <div class = "rbid_pending_notify">{"COM_RBIDS_AUCTION_IS_PENDING_ADMINISTRATOR_APPROVAL"|translate}</div>
                </td>
            </tr>
	{/if}
{/if}

</table>
<!-- [-] Buttons Bar -->

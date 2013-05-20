<form action = "{$ROOT_HOST}index.php?option={$option}&task=sendbid&id={$auction->id}"
      method = "post"
      name = "auctionForm"
      onsubmit = "return auctionObject.BidFormValidate(this);"
      enctype = "multipart/form-data">

    <input type = "hidden" name = "max_price" value = "{$auction->max_price}" />
    <input type = "hidden" name = "Itemid" value = "{$Itemid}" />

    <table width = "100%" class = "send_bid_table">
        <tr>
            <td align = "left" valign = "top" class = "auction_bid" colspan = "2">
                {"COM_RBIDS_BID_PRICE"|translate}:
                <input name = "amount" class = "inputbox" type = "text" style="width:40%;height: 25px;text-align: right;font-size: 14px;color:#494949;" value = "" size = "10" alt = "bid" {$disable_bids}/>&nbsp;{$auction->currency}&nbsp;
            </td>

            <td align = "left" valign = "top" rowspan = "4">
                <label for = "bid_message">{'COM_RBIDS_COMMENT'|translate}:</label> <br />
                <textarea name = "message" id = "bid_message" cols = "40" rows = "8"></textarea>
            </td>
        </tr>

    {if ($auction->NDA_file != '' && $uploaded_NDA != 1 && $cfg->nda_option == 1) }
        <tr>
            <td colspan = "2">
                <div style = "margin-top: 5px;margin-bottom: 5px;">
	        <span id = "bid" style="float: left;">
			{"COM_RBIDS_UPLOAD_SIGNED_NDA"|translate}:&nbsp;
		</span>
                    <input name = "NDA_file" id = "NDA_file_id" class = "inputbox" type = "file" size = "20" alt = "NDA_file" style="height: 20px;" />

                    <div>
                        <small style = "color: Grey;">{"COM_RBIDS_NDA_REQUIRED_IN_ORDER_TO_PLACE_A_BID"|translate}</small>
                    </div>
                </div>
            </td>
        </tr>
    {/if}
    {if $cfg->enable_bid_attach}
        <tr>
            <td align = "left" valign = "top" colspan = "2">
                <label for = "bid_attachment" style="float: left;">{'COM_RBIDS_ATTACH_TO_BID'|translate}:&nbsp;</label>
                <input type = "file" name = "bid_attachment" id = "bid_attachment"  style="height: 20px;" class = "inputfile{if $cfg->bid_attach_compulsory} required{/if}" />
                <br />
                <small style = "color: Grey;">{"COM_RBIDS_MAXIMUM_FILE_SIZE"|translate|replace:"%s":$cfg->attach_max_size}</small>
		    {if $cfg->attach_extensions}
                        <br />
                        <small style = "color: Grey;">{"COM_RBIDS_ALLOWED_ATTACHEMENT_EXTENSIONS"|translate} {$cfg->attach_extensions}</small>
		    {/if}
            </td>
        </tr>
    {/if}
        <tr>
            <td align = "left" valign = "top">
                <span class="v_spacer_5"></span>
	    {if $terms_and_conditions}
		    <small>
                <input type = "checkbox" class = "inputbox" name = "agreement" value = "1" {$disable_bids} />
		    {'COM_RBIDS_AGREE'|translate}
                <a href = "javascript: void(0);" onclick = "window.open('{$auction->get('links.terms')}','messwindow','location=1,status=1,scrollbars=1,width=500,height=500')"
                   id = "auction_category">
			{'COM_RBIDS_TERMS_AND_CONDITIONS'|translate}</a>
                    </small>
	    {/if}
            </td>
            <td valign = "top">
                <span class="v_spacer_5"></span>
                <input type = "submit" name = "send" value = "{'COM_RBIDS_SEND_BID'|translate}" class = "button" {$disable_bids}/>
            </td>
        </tr>
    </table>
</form>


{* Messages Tab *}
{if $cfg->allow_messages}
	
<form action = "{$ROOT_HOST}index.php" method = "POST" name = "auctionMessageForm">
    <input type = "hidden" name = "option" value = "{$option}" />
    <input type = "hidden" name = "task" value = "savemessage" />
    <input type = "hidden" name = "controller" value = "messages" />
    <input type = "hidden" name = "id" value = "{$auction->id}" />
    <input type = "hidden" name = "Itemid" value = "{$Itemid}" />

	{foreach from=$auction->get('messages') item=item key=key}
	{* If message is private either 'userto','userfrom' or 'auctioner' can view it. *}
		{if $item->private && ($my->id == $item->userid1 || $my->id == $item->userid2 || $auction->isMyAuction())}
                    <div class = "auction_message_{cycle values='0,1'}">
			    {if $item->userid2!=0}
                                <span class = "auction_msg_from">{"COM_RBIDS_FROM"|translate}: {if $item->fromuser}<a
                                        href = "{$links->getUserdetailsRoute($item->userid1)}">{$item->fromuser}</a>{else}{"COM_RBIDS_GUEST"|translate}{/if}</span>
                                <span class = "auction_msg_to">{"COM_RBIDS_TO"|translate}: <a href = "{$links->getUserdetailsRoute($item->userid2)}">{$item->touser}</a></span>
				    {else}
                                <i>{"COM_RBIDS_BROADCAST_MESSAGE"|translate}</i>,
			    {/if}
                        <span class = "auction_msg_date">{"COM_RBIDS_DATE"|translate}: {printdate date=$item->modified}&nbsp;
				{if $item->userid1!=$joomlauser->id && $item->userid2!=0}
                                    <a href = "javascript:void(0);" onclick = "auctionObject.SendMessage(this,{$item->id},0,'{$item->fromuser}',{$item->private});">
					    {"COM_RBIDS_REPLY"|translate}</a>
				{/if}
            		        </span>
                        <br />
				<span class = "auction_msg_title">
					{"COM_RBIDS_MESSAGE"|translate}:
					<img src = "{$ROOT_HOST}components/com_rbids/images/private_yes.png" alt = "" title = "{'COM_RBIDS_PRIVATE_MESSAGE_TITLE'|translate}" />
				</span>

                        <div class = "auction_msg_text">{$item->message|stripslashes}</div>
                    </div>
                    <div class = "auction_message_separator">&nbsp;</div>
		{* ToDo Factory: Refactor to remove this duplicate code. This is happen due to smarty 'continue' lack. *}
			{elseif !$item->private}

                    <div class = "auction_message_{cycle values='0,1'}">
			    {if $item->userid2!=0}
                                <span class = "auction_msg_from">{"COM_RBIDS_FROM"|translate}: {if $item->fromuser}<a
                                        href = "{$links->getUserdetailsRoute($item->userid1)}">{$item->fromuser}</a>{else}{"COM_RBIDS_GUEST"|translate}{/if}</span>
                                <span class = "auction_msg_to">{"COM_RBIDS_TO"|translate}: <a href = "{$links->getUserdetailsRoute($item->userid2)}">{$item->touser}</a></span>
				    {else}
                                <i>{"COM_RBIDS_BROADCAST_MESSAGE"|translate}</i>,
			    {/if}
                        <span class = "auction_msg_date">{"COM_RBIDS_DATE"|translate}: {printdate date=$item->modified}&nbsp;
				{if $item->userid1!=$joomlauser->id && $item->userid2!=0}
                                    <a href = "javascript:void(0);" onclick = "auctionObject.SendMessage(this,{$item->id},0,'{$item->fromuser}',{$item->private});">
					    {"COM_RBIDS_REPLY"|translate}</a>
				{/if}
            		</span>
                        <br />
                        <span class = "auction_msg_title">{"COM_RBIDS_MESSAGE"|translate}: </span>

                        <div class = "auction_msg_text">{$item->message|stripslashes}</div>
                    </div>
                    <div class = "auction_message_separator">&nbsp;</div>

		{/if}

	{/foreach}

	{if !$auction->isMyAuction() && (!$auction->close_offer || $auction->get('i_am_winner'))}
            <a href = "javascript:void(0);" style = "display:auto;"
               onclick = "auctionObject.SendMessage(this,0,0,'{$auctioneer->username}');">{"COM_RBIDS_SEND_MESSAGE"|translate}</a>
		{else}
            <a href = "javascript:void(0);"
               style = "display:auto;"
               id = "lnk_broadcast_msg"
               onclick = "auctionObject.SendBroadcastMessage(this);">{"COM_RBIDS_BROADCAST_MESSAGE"|translate}</a>
	{/if}

    <div id = "auction_message_box" style = "display:none;">

        <input type = "hidden" name = "idmsg" id = "idmsg" value = "">
        <input type = "hidden" name = "msgisprivate" id = "msgisprivate" value = "">
        <input type = "hidden" name = "bidder_id" id = "bidder_id" value = "">

        <a name = "mess"></a>
	    {"COM_RBIDS_MESSAGE"|translate}&nbsp;{"COM_RBIDS_TO"|translate}:&nbsp;<span id = "message_to"></span><br />
        <textarea class = "inputbox" name = "message" id = "message" rows = "15" cols = "60"></textarea>

        <div style = "margin-top: 10px;">
            <input type = "submit"
                   name = "send"
                   value = "{'COM_RBIDS_SEND_MESSAGE'|translate}"
                   class = "button"
                    />
            <input type = "submit"
                   name = "cancel"
                   value = "{'COM_RBIDS_CANCEL'|translate}"
                   class = "button"
                   onclick = "document.getElementById('auction_message_box').style.display='none';
                                 document.getElementById('message').value = '';
                                 return false;"
                    />
        </div>


	    {if $cfg->enable_captcha}

                <div>{"COM_RBIDS_PLEASE_ENTER_THIS_CAPTCHA_TEXT"|translate}:</div>
		    {$captcha}

	    {/if}
    </div>
</form>
<div id = "broadcast_msg" style = "display:none;">
    <form action = "{$ROOT_HOST}index.php" method = "post" name = "auctionBroadcastForm">
        <input type = "hidden" name = "option" value = "{$option}" />
        <input type = "hidden" name = "task" value = "saveBroadcastMessage" />
        <input type = "hidden" name = "controller" value = "messages" />
        <input type = "hidden" name = "id" value = "{$auction->id}" />
        <input type = "hidden" name = "Itemid" value = "{$Itemid}" />

        <strong>{"COM_RBIDS_BROADCAST_MESSAGES_TO_ALL_BIDDERS"|translate}</strong>

        <textarea class = "inputbox" name = "message" id = "broadcast_message" rows = "15" cols = "60"></textarea>

        <div style = "margin-top: 10px;">
            <input type = "submit"
                   name = "send"
                   value = "{'COM_RBIDS_SEND_MESSAGE'|translate}"
                   class = "button"
                    />
            <input type = "submit"
                   name = "cancel"
                   value = "{'COM_RBIDS_CANCEL'|translate}"
                   class = "button"
                   onclick = "document.getElementById('broadcast_msg').style.display = 'none';
                                  document.getElementById('broadcast_message').value = '';
                                  document.getElementById('lnk_broadcast_msg').style.display = 'inline';
                                  return false;"
                    />
        </div>
    </form>
</div>
	
{/if}

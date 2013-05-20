{* Include Validation script *}
{include file='js/t_javascript_language.tpl'}
{set_css}
<h2>{"COM_RBIDS_INVITED_FOR_AUCTIONS"|translate}</h2>
{include file='elements/mybids/t_mybids_tabs.tpl'}

<div class = "invitedAuctions">

{if $lists.invitedAuctions|@count}

	{section name=invauc loop=$lists.invitedAuctions}
            <div class = "invitedAuctionLink">
                <span class = "title">
                    <a href = 'index.php?option=com_rbids&task=details&id={$lists.invitedAuctions[invauc]->id}'>{$lists.invitedAuctions[invauc]->title}</a>

                <span class = "author">» {* ASCII 175 CODE *}  {$lists.invitedAuctions[invauc]->username}</span>
                        <span class = "status">» {* ASCII 175 CODE *}
	                        {if $lists.invitedAuctions[invauc]->close_offer == 1}
		                        {"COM_RBIDS_AUCTION_IS_CLOSED"|translate}
		                        {elseif 'expired' == $lists.invitedAuctions[invauc]->status}
		                        {"COM_RBIDS_AUCTION_IS_EXPIRED"|translate}
		                        {else}
		                        {"COM_RBIDS_AUCTION_IS_PUBLISHED"|translate}
	                        {/if}
                        </span>
		</span>
            </div>
	{/section}

	{else}
    <div class = "invitedAuctionSpan">{'COM_RBIDS_INVITED_AUCTIONS_NOT_INVITED'|translate}</div>
{/if}

</div>


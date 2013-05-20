{set_css}
{include file='js/t_javascript_language.tpl'}

<h2>{'COM_RBIDS_USER_PROFILE'|translate}: {$user->username}</h2>

<table width = "100%" border = "0" cellpadding = "0" cellspacing = "0" class = "user_detailstable">
    <tr>
        <td colspan = "2">
	{positions position="header" item=$user page="user_profile"}
        </td>
    </tr>
    <tr>
        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_NAME"|translate}</td>
        <td>{$user->name}</td>
    </tr>
    <tr>
        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_SURNAME"|translate}</td>
        <td>{$user->surname}</td>
    </tr>
{if $cfg->hide_contact != "1"}
    <tr>
        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_ADDRESS"|translate}</td>
        <td>{$user->address}</td>
    </tr>
    <tr>
        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_CITY"|translate}</td>
        <td>{$user->city}</td>
    </tr>
    <tr>
        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_COUNTRY"|translate}</td>
        <td>{$user->country}</td>
    </tr>
    <tr>
        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_PHONE"|translate}</td>
        <td>{$user->phone}</td>
    </tr>
	{if $cfg->allow_messenger && !$cfg->hide_contact}

		{if $user->YM_is_visible}
                    <tr>
                        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_YM"|translate}</td>
                        <td>{$user->YM}</td>
                    </tr>
		{/if}

		{if $user->Hotmail_is_visible}
                    <tr>
                        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_HOTMAIL"|translate}</td>
                        <td>{$user->Hotmail}</td>
                    </tr>
		{/if}
		{if $user->Skype_is_visible}
                    <tr>
                        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_SKYPE"|translate}</td>
                        <td>{$user->Skype}</td>
                    </tr>
		{/if}
		{if $user->Linkedin_is_visible}
                    <tr>
                        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_LINKEDIN"|translate}</td>
                        <td>{$user->Linkedin}</td>
                    </tr>
		{/if}
		{if $user->Facebook_is_visible}
                    <tr>
                        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_FACEBOOK"|translate}</td>
                        <td>{$user->Facebook}</td>
                    </tr>
		{/if}
	{/if}
	{if $cfg->allowpaypal && !$cfg->hide_contact}
		{if $user->paypalemail_is_visible}
                    <tr>
                        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_PAYPAL_EMAIL"|translate}</td>
                        <td>{$user->paypalemail}</td>
                    </tr>
		{/if}
	{/if}
{/if}
    <tr>
        <td width = "150px" class = "rbid_user_label">{"COM_RBIDS_ACTIVITY_DOMAINS"|translate}</td>
        <td>
	{foreach from=$user->activity_domains item=item}
		<a href='{$links->getAuctionListRoute($item->aucLinkFilterCatIdUserId)}'>{$item->catname}</a>
	{/foreach}
        </td>
    </tr>
    <tr>
        <td width = "150px" valign = "top" class = "rbid_user_label">{"COM_RBIDS_ABOUT_ME"|translate}</td>
        <td>{$user->about_me}</td>
    </tr>
    <tr>
        <td colspan = "2">
	{positions position="bottom" item=$user page="user_profile"}
        </td>
    </tr>
{* @since 1.5.0 *}
    <tr>
        <td height = "10px" colspan = "2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan = "2"><strong>{"COM_RBIDS_LATEST__RATINGS"|translate}</strong></td>
    </tr>
    <tr>
        <td colspan = "2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan = "2">
            <table width = "100%">
                <tr>
                    <th class = "list_ratings_header">{"COM_RBIDS_RATING"|translate}</th>
                    <th class = "list_ratings_header">{"COM_RBIDS_RATED_BY"|translate}</th>
                    <th class = "list_ratings_header">{"COM_RBIDS_RATED_FOR_AUCTION"|translate}</th>
                    <th class = "list_ratings_header">{"COM_RBIDS_RATED_ON"|translate}</th>
                </tr>
	    {foreach from=$lists.ratings item=item}
                <tr class = "myrating{cycle values='0,1'}">
                    <td width = "15%">
                        <span class = "rating">{$item->rating}</span>
                    </td>
                    <td width = "15%">
                        <a href = '{$links->getUserdetailsRoute($item->voter)}'>{$item->username}</a>
                    </td>
                    <td width = "*%">
                        <a href = '{$links->getAuctionDetailRoute($item->auction_id)}'>{$item->title}</a>
                    </td>
                    <td width = "*%">
			    {printdate date=$item->modified use_hour=0}
                    </td>
                </tr>
                <tr class = "myrating{cycle values='0,1'}">
                    <td colspan = "3">
                        <div class = "msg_text">{$item->message}</div>
                    </td>
                </tr>
	    {/foreach}
            </table>
        </td>
    </tr>
</table>
<div class = "user_details_header">
    <a href = "{$links->getUserRatingsRoute($user->userid)}">{"COM_RBIDS_SEE_ALL_RATINGS"|translate}</a>
    <a href = '{$links->getOtherAuctionListRoute($user->userid)} '>{"COM_RBIDS_MORE_AUCTIONS"|translate}</a>
</div>

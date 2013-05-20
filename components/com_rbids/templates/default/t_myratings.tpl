{set_css}
{include file='js/t_javascript_language.tpl'}
{if $isMyRatings}
{include file='elements/userprofile/t_profile_tabs.tpl'}
{/if}
<div class = "user_ratings_header">
{"COM_RBIDS_OVERALL_RATING"|translate}: <span class = "rating">{$generalrating.rating_overall}</span>
</div>
<table width = "100%" border = "0" id = "table_myratings_box">
    <thead>
    <tr>
        <th class = "list_ratings_header">{"COM_RBIDS_USERNAME"|translate}</th>
        <th class = "list_ratings_header">{"COM_RBIDS_TITLE"|translate}</th>
        <th class = "list_ratings_header">{"COM_RBIDS_RATE"|translate}</th>
        <th class = "list_ratings_header">{"COM_RBIDS_DATE"|translate}</th>
    </tr>
    </thead>
{if $lists.ratings|@count==0}
    <tr>
        <td colspan = "3">{"COM_RBIDS_NO_RATINGS"|translate}</td>
    </tr>
	{else}
	{foreach from=$lists.ratings item=item}
            <tr class = "myrating{cycle values='0,1'}">
                <td width = "15%">
                    <a href = '{$links->getUserdetailsRoute($item->voter)}'>{$item->username}</a>
                </td>
                <td width = "*%">
                    <a href = '{$links->getAuctionDetailRoute($item->auction_id)}'>{$item->title}</a>
                </td>
                <td width = "15%">
                    <span class = "rating">{$item->rating}</span>
                </td>
                <td width = "5%">
			{$item->modified}
                </td>
            </tr>
		{if $item->message}
                    <tr class = "myrating{cycle values='0,1'}">
                        <td colspan = "4">
                            <p class = "triangle-border top">{$item->message}</p>
                        </td>
                    </tr>
		{/if}
	{/foreach}
{/if}
</table>

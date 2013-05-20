{set_css}
{include file='js/t_javascript_language.tpl'}
{include file='js/t_javascript_countdown.tpl'}

<h2>
{"COM_RBIDS_AUCTIONS_LIST"|translate}
    <a href = "index.php?option=com_rbids&task={$task}&format=feed&limitstart=" target = "_blank">
        <img src = "{$IMAGE_ROOT}f_rss.jpg" width = "14" border = "0" alt = "RSS" />
    </a>
</h2>


<div align = "right" style = "text-align:right;">
    <ul id = "auction_tabmenu">
        <li>
            <a class = "{if (!$filter_archive)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&Itemid={$Itemid}&task={$task}&filter_archive=">
	    {"COM_RBIDS_VIEW_ACTIVE"|translate}</a>
        </li>
        <li>
            <a class = "{if ($filter_archive)}active{else}inactive{/if}" href = "{$ROOT_HOST}index.php?option=com_rbids&Itemid={$Itemid}&task={$task}&filter_archive=archive">
	    {"COM_RBIDS_VIEW_ARCHIVED"|translate}</a>
        </li>
    </ul>
</div>


<form action = "{$ROOT_HOST}index.php" method = "get" name = "rbidsForm">
    <input type = "hidden" name = "option" value = "{$option}" />
    <input type = "hidden" name = "task" value = "{$task}" />
    <input type = "hidden" name = "Itemid" value = "{$Itemid}" />

{* Include filter selectboxes *}
{include file='elements/t_header_filter.tpl'}

    <table align = "center" cellpadding = "0" cellspacing = "0" border = "0" width = "100%" class = "auction_list_container">
        <tr style = "height: 30px;">
            <th style = "width: auto;">
	    {include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_TITLE"|translate key="title"} /
	    {include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_CATEGORY"|translate key="catname"} /
	    {include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_AUCTIONEER"|translate key="username"}
            </th>
            <th style = "width:120px;text-align: center;">{include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_MAX_PRICE"|translate key="max_price"}</th>
            <th style = "width:130px; text-align: center;">
	    {include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_BIDS_PLACED"|translate key="nr_bids"}        /
	    {include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_NR_BIDDERS"|translate key="nr_bidders"}
            </th>
            <th style = "width:125px;text-align: center;">{include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_ENDING_IN"|translate key="end_date"}</th>
        </tr>
    {section name=auctionsloop loop=$auction_rows}
	    {if $auction_rows[auctionsloop]->auction_type == $AUCTION_TYPES.AUCTION_TYPE_INVITE && !$auction_rows[auctionsloop]->isMyAuction()}
	    {* List auction if is not restriction to invited users active or if restriction is in effect user must be invited *}
		    {if !$cfg->allow_only_invited_users || ($cfg->allow_only_invited_users && $auction_rows[auctionsloop]->isInvited())}
		    {include file='elements/lists/t_listauctions_cell.tpl' auction=`$auction_rows[auctionsloop]` index=`$smarty.section.auctionsloop.rownum`}
		    {/if}
		    {else}
	    {include file='elements/lists/t_listauctions_cell.tpl' auction=`$auction_rows[auctionsloop]` index=`$smarty.section.auctionsloop.rownum`}
	    {/if}
    {/section}
    </table>
{include file='elements/t_listfooter.tpl'}

</form>

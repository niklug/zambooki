{set_css}
{include file='js/t_javascript_language.tpl'}
{include file='js/t_javascript_countdown.tpl'}

<h2>{"COM_RBIDS_MY_AUCTIONS"|translate}</h2>

<div class = 'new_button'>
    <a href = "{$new_auction_link}">{"COM_RBIDS_NEW"|translate}</a>
</div>

<div style = "padding:5px"></div>
<ul id = "auction_tabmenu">
{include file='elements/filter_button.tpl'                      label="COM_RBIDS_MY_AUCTIONS_TAB_PUBLISHED_OFFERS"|translate}
	{include file='elements/filter_button.tpl' filter='unpublished' label="COM_RBIDS_MY_AUCTIONS_TAB_UNPUBLISHED_OFFERS"|translate}
	{include file='elements/filter_button.tpl' filter='archive'     label="COM_RBIDS_MY_AUCTIONS_TAB_ARCHIVE"|translate}
	{include file='elements/filter_button.tpl' filter='accepted'    label="COM_RBIDS_MY_AUCTIONS_TAB_ACCEPTED_BIDS"|translate}
</ul>

<form action = "{$ROOT_HOST}index.php" method = "get" name = "rbidsForm">
    <input type = "hidden" name = "option" value = "{$option}">
    <input type = "hidden" name = "task" value = "{$task}">
    <input type = "hidden" name = "Itemid" value = "{$Itemid}">
    <input type = "hidden" name = "filter_myauctions" value = "{$filter_myauctions}">

{* Include filter selectboxes *}
{include file='elements/t_header_filter.tpl'}

    <table align = "center" cellpadding = "0" cellspacing = "0" width = "100%" id = "auction_list_container">
    {section name=auctionsloop loop=$auction_rows}
		{include file='elements/lists/t_listauctions_cell.tpl' auction=`$auction_rows[auctionsloop]` index=`$smarty.section.auctionsloop.rownum`}
	{/section}
    </table>
{include file='elements/t_listfooter.tpl'}

</form>

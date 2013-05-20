{set_css}
{include file='js/t_javascript_language.tpl'}
{include file='js/t_javascript_countdown.tpl'}

<h2>{"COM_RBIDS_MY_WON_BIDS"|translate}</h2>
{include file='elements/mybids/t_mybids_tabs.tpl'}

<form action = "{$ROOT_HOST}index.php" method = "get" name = "rbidsForm">
    <input type = "hidden" name = "option" value = "{$option}">
    <input type = "hidden" name = "task" value = "{$task}">
    <input type = "hidden" name = "Itemid" value = "{$Itemid}">


{* Include filter selectboxes *}
{include file='elements/t_header_filter.tpl'}

    <table align = "center" cellpadding = "0" cellspacing = "0" width = "100%" id = "auction_list_container">
    {section name=auctionsloop loop=$auction_rows}
		{include file='elements/lists/t_listauctions_cell.tpl' auction=`$auction_rows[auctionsloop]` index=`$smarty.section.auctionsloop.rownum`}
	{/section}
    </table>
{include file='elements/t_listfooter.tpl'}

</form>

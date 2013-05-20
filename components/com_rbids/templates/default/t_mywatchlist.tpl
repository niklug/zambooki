{set_css}
{include file='js/t_javascript_language.tpl'}
{include file='js/t_javascript_countdown.tpl'}

<h2>{"COM_RBIDS_MY_WATCHLIST"|translate}</h2>

<form action = "{$ROOT_HOST}index.php" method = "post" name = "rbidsForm">
	<input type = "hidden" name = "option" value = "{$option}" />
	<input type = "hidden" name = "controller" value = "watchlist" />
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
                <th style = "width:120px">{include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_MAX_PRICE"|translate key="max_price"}</th>
                <th style = "width:120px">
	        {include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_BIDS_PLACED"|translate key="nr_bids"}        /
	        {include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_NR_BIDDERS"|translate key="nr_bidders"}
                </th>
                <th style = "width:125px;">{include file='elements/sort_field.tpl' label="COM_RBIDS_HEADER_ENDING_IN"|translate key="end_date"}</th>
	    </tr>

	{section name=auctionsloop loop=$auction_rows}
	{include file='elements/lists/t_listauctions_cell.tpl' auction=`$auction_rows[auctionsloop]` index=`$smarty.section.auctionsloop.rownum`}
	{/section}
	</table>
{include file='elements/t_listfooter.tpl'}

</form>

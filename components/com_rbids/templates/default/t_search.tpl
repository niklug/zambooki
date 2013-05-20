{* Include Validation script *}
{include file='js/t_javascript_language.tpl'}

{set_css}

<h2>{"COM_RBIDS_SEARCH_TITLE"|translate}</h2>
{include file='elements/search/t_search_tabs.tpl'}
<form action = "{$ROOT_HOST}index.php" method = "get" name = "auctionForm">
    <input type = "hidden" name = "task" value = "showSearchResults" />
    <input type = "hidden" name = "option" value = "{$option}" />
    <input type = "hidden" name = "Itemid" value = "{$Itemid}" />

    <div class = "auction_search_outer">
        <div class = "auction_search_inner" style = "position: relative;">
            <table width = "100%" class = "auctions_search">
                <tr>
                    <td colspan = "6">
                        <label class = "auction_lables" style = "padding-right: 30px;">{"COM_RBIDS_TITLE"|translate}:</label>
                        <input type = "text" name = "keyword" class = "inputbox auction_search_keyword" size = "30" value = "{$lists.keyword}" />

                        <div class = "auction_search_sub">
                            <input type = "checkbox" class = "inputbox" name = "in_description" value = "1" checked = "checked" /> &nbsp;<label
                                class = "">{"COM_RBIDS_SEARCH_IN_DESCRIPTION"|translate}</label>
                            <input type = "checkbox" class = "inputbox" name = "filter_archive" value = "1" {if $lists.filter_archive}checked = "checked"{/if} /> &nbsp;<label
                                class = "">{"COM_RBIDS_SEARCH_IN_ARCHIVE"|translate}</label>
                            <input type = "checkbox" class = "inputbox" name = "in_tags" value = "1" {if $lists.in_tags}checked = "checked"{/if} /> &nbsp;<label
                                class = "">{"COM_RBIDS_SEARCH_IN_TAGS"|translate}</label>
                        </div>
                        <div class = "auction_search_field" style = "position:absolute; right: 20px; top: 5px;">
                            <input type = "submit" name = "search" value = "{'COM_RBIDS_SEARCH'|translate}" class = "auction_button" />
                        </div>
                    </td>
                </tr>

                <tr>
		{if $lists.cats}
                    <td width = "100px"><label class = "auction_lables">{"COM_RBIDS_CATEGORY"|translate}:</label></td>
                    <td colspan = "5">{$lists.cats}</td>
			{else}
                    <td></td>
                    <td colspan = "5"></td>
		{/if}
                </tr>


                <tr>
                    <td><label class = "auction_lables">{"COM_RBIDS_SEARCH_HEADING_BY_DATE"|translate}:</label></td>
                    <td colspan = "5" class = "search_date_td">{$lists.befored} - {$lists.afterd}</td>
                </tr>
                <tr>
                    <td><label class = "auction_lables">{"COM_RBIDS_SEARCH_HEADING_BY_PRICE"|translate}:</label></td>
                    <td colspan = "5">
                        <input type = "text" name = "startprice" class = "inputbox" size = "10" style = "text-align: right;" value = "{$lists.startprice}" />
                        -
                        <input type = "text" name = "endprice" class = "inputbox" size = "10" style = "text-align: right;" value = "{$lists.endprice}" />
                        <span class = "small">{$lists.currency}</span>
                    </td>
                </tr>


                <tr>
                    <td><label class = "auction_lables">{"COM_RBIDS_USERNAME"|translate}:</label></td>
                    <td style = "width: 150px;"><input type = "text" name = "username" class = "inputbox" /></td>
                    <td><label class = "auction_lables">{"COM_RBIDS_CITY"|translate}:</label></td>
                    <td style = "width: 150px;"><input type = "text" name = "city" class = "inputbox" size = "30" value = "{$lists.city}" /></td>
		{if $lists.country}
                    <td><label class = "auction_lables">{"COM_RBIDS_COUNTRY"|translate}:</label></td>
                    <td style = "width: 150px;">{$lists.country}</td>
			{else }
                    <td></td>
                    <td></td>
		{/if}
                </tr>
                <tr>
                    <td colspan = "6">
		    {$custom_fields_html}
                    </td>
                </tr>

            </table>
        </div>
    </div>

</form>

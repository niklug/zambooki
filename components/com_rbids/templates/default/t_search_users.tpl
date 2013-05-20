{* Include Validation script *}
{include file='js/t_javascript_language.tpl'}

{set_css}

<h2>{"COM_RBIDS_SEARCH_TITLE"|translate}</h2>
{include file='elements/search/t_search_tabs.tpl'}
<form action = "{$ROOT_HOST}index.php" method = "post" name = "auctionForm">
    <input type = "hidden" name = "task" value = "showUsers" />
    <input type = "hidden" name = "controller" value = "user" />
    <input type = "hidden" name = "option" value = "{$option}" />
    <input type = "hidden" name = "reset" value = "all" />
    <input type = "hidden" name = "Itemid" value = "{$Itemid}" />

    <table width = "100%" class = "auctions_search">
        <tr>
            <td width = "120">
                <div class = "auction_search_outer">
                    <div class = "auction_search_inner" style = "position: relative;">
                        <label class = "auction_labels" style = "padding-right: 30px;">{"COM_RBIDS_KEYWORD"|translate}:</label>
                        <input type = "text" name = "keyword" class = "inputbox auction_search_keyword" size = "20" />
                        <input type = "submit" name = "search" value = "{'COM_RBIDS_SEARCH'|translate}" class = "auction_button" />
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan = "2">
	    {$lists.custom_fields}
            </td>
        </tr>
    </table>
</form>

{set_css}
{include file='js/t_javascript_language.tpl'}
<h2>{$page_title}</h2>
{include file="elements/maps/t_maps_tabs.tpl"}

{if $cfg->google_key}
{include file="js/t_javascript_maps.tpl"}
	{import_js_block}
		{literal}
                window.addEvent('domready', function () {
                load_gmaps();
                gmap_refreshauctions();
                });
		{/literal}
	{/import_js_block}
<table class = "auctions_list_map_filters">
    <tr>
        <td><span class = "label">{"COM_RBIDS_CATEGORY"|translate}</span>:</td>
        <td>{$lists.category}</td>
        <td><span class = "label">{"COM_RBIDS_SHOW"|translate}</span>:</td>
        <td>
            <select id = "ads_limitbox" onchange = "gmap_refreshauctions();">
                <option value = "10">10</option>
                <option value = "20" selected = "">20</option>
                <option value = "50">50</option>
                <option value = "0">{"COM_RBIDS_ALL"|translate}</option>
            </select>
        </td>
    </tr>
</table>
<br />
<br />
<div id = "wait_loader" style = "width:auto; text-align:center; display:none;"><img src = "{$IMAGE_ROOT}ajax-loader2.gif" /></div>
<br />
<div style = "width:auto; font-family:Arial, sans-serif; font-size:11px; border:1px solid black">
    <div id = "map_canvas" style = "overflow: hidden; width:auto; height:600px"></div>
</div>
	{else}
	{"COM_RBIDS_NO_GOOGLE_MAP_DEFINED"|translate}
{/if}

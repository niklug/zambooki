{set_css}
{include file='js/t_javascript_language.tpl'}
<h2>{$page_title}</h2>
{include file="elements/maps/t_maps_tabs.tpl"}
{if ($cfg->google_key!="") }
{include file="js/t_javascript_maps.tpl"}
	{import_js_block}
		{literal}
                window.addEvent('domready', function () {
                load_gmaps();
                gmap_refreshauctions_search();
                });
		{/literal}
	{/import_js_block}
<table class="auctions_search_map_filters">
    <tr>
        <td>
            <span class = "label">{"COM_RBIDS_ADDRESS"|translate}</span>:
            <input type = "text" id = "addressInput" value="{$addressInput}" />

            <span class = "label">{"COM_RBIDS_RADIUS"|translate}</span>:
            <select id = "radiusSelect">
		    {foreach from=$lists.radius_units item=item}
                        <option value = "{$item}">{$item}</option>
		    {/foreach}
            </select>
		{if $cfg->googlemap_distance==1}
			{"COM_RBIDS_KM"|translate}
			{else}
			{"COM_RBIDS_MILES"|translate}
		{/if}
        </td>
        <td>
            <input type = "button" class = "button" onclick = "gmap_refreshauctions_search()" value = "{'COM_RBIDS_FIND'|translate}" />
        </td>
    </tr>
</table>
<br />
<br />
<div style = "width:auto; font-family:Arial, sans-serif; font-size:11px; border:1px solid black">


    <div id = "map_canvas"
         style = "overflow: hidden;
                width:auto;
                height:400px"></div>

        	<span id = "wait_loader" style = "width:auto; text-align:center; display:none;">
	                <img src = "{$IMAGE_ROOT}ajax-loader2.gif" style = "width:170px !important;" />
	        </span>

    <div id = "search_sidebar"
         style = "overflow-y: scroll;
	        width:auto;
	        height: 200px;
	        font-size: 11px;
	        color: #000"></div>

</div>
	{else}
	{"COM_RBIDS_NO_GOOGLE_MAP_DEFINED"|translate}
{/if}

{include file='js/t_javascript_language.tpl'}
<h3 style="color:#FAA000; text-decoration:underline;">{"COM_RBIDS_GOOGLEMAPS"|translate}</h3>
<div>
{if ($cfg->google_key!="") }
    {include file="js/t_javascript_maps.tpl"}
    {import_js_block}
        {literal}
        window.addEvent('domready', function () {
            gmap_selectposition.delay(500);
        });
        {/literal}
    {/import_js_block}
	<div id="map_canvas" style="width: 500px; height: 300px"></div>
{else}
    {"COM_RBIDS_NO_GOOGLE_MAP_DEFINED"|translate}
{/if}
</div>
<div style="text-align:center;width: 500px; ">
	<input type="text" readonly="readonly" id="gmap_posx" value="{$googleMapX}"/>
	<input type="text" readonly="readonly" id="gmap_posy" value="{$googleMapY}"/>
	<br /><h3><a href="#" style="color:#FAA000; font-weight:bold;" onclick="submit_gmap_coords()">{"COM_RBIDS_SELECT"|translate}</a></h3>
</div>

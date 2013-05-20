{include file='js/t_javascript_language.tpl'}
{if ($googleX!="" && $googleY!="") || $cfg->google_key!="" }
	<div id="map_canvas" style="width: {$cfg->googlemap_gx|default:250}px; height: {$cfg->googlemap_gy|default:250}px"></div>
    {include file="js/t_javascript_maps.tpl"}
    {import_js_block}
        {literal}
        window.addEvent('domready', function () {
            gmap_showmap.delay(500);
        });
        {/literal}
    {/import_js_block}
{else}
	{"COM_RBIDS_NO_GOOGLE_MAP_DEFINED"|translate}
{/if}
	

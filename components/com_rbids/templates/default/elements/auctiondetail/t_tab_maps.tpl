{if $cfg->google_key && $cfg->map_in_auction_details &&(($auction->googlex && $auction->googley)||($user->googleMaps_x && $user->googleMaps_y)) }
   {if ($auction->googlex && $auction->googley)}
        {include file='js/t_javascript_maps.tpl' googleMapX=`$auction->googlex` googleMapY=`$auction->googley`}
   {elseif ($user->googleMaps_x && $user->googleMaps_y)}
        {include file='js/t_javascript_maps.tpl' googleMapX=`$user->googleMaps_x` googleMapY=`$user->googleMaps_x`}
   {/if}
   {import_js_block}
     {literal}
        window.addEvent('domready', function () {
            load_gmaps();
            gmap_showmap.delay(500);

            $$('dl.tabs .tab3').addEvent('click',function(){
                google.maps.event.trigger(map, 'resize');
            });
        });
     {/literal}
   {/import_js_block}
   {starttab paneid="tab3" text="COM_RBIDS_LOCATION_ON_MAP"|translate}
        <div id="map_canvas" style="width: {$cfg->googlemap_gx|default:250}px; height: {$cfg->googlemap_gy|default:250}px;margin-top:15px;margin-bottom: 15px;"></div>
   {endtab}
{/if}

{if $cfg->enable_countdown}
    {import_js_file url="countdown.js"}
    {import_js_block}
        {literal}
    	window.addEvent('domready', function(){
    		new auction_countdown( '.timer', language["bid_days"],language["bid_expired"]);
    	});
        {/literal}
    {/import_js_block}
{/if}

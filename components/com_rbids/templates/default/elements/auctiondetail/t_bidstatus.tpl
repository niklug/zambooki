<div style = "text-align: center;">
<span class="rbids_heading_status">
        {$bids|@count}&nbsp;{"COM_RBIDS_NR_OF_BIDS"|translate}{if $cfg->allow_messages}&nbsp; &frasl; &nbsp;{$auction->get('messages')|@count}&nbsp;{"COM_RBIDS_NR_OF_MESSAGES"|translate}{/if}
    </span>
</div>

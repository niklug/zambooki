{if $auction->get('imagecount')}
	{starttab paneid="tab4" text="COM_RBIDS_SCREENSHOTS"|translate}
		<div id = 'gallery_box' style = "display: block;">{$auction->get('gallery')}</div>
	{endtab}
{/if}

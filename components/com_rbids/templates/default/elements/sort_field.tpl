{if $filter_order == $key}
    <a href="javascript:void(0);" onclick="auctionObject.submitListForm('{$key}','!{$filter_order_Dir}');">{$label}
	{if $filter_order_Dir == "DESC"}
		<img src="{$TEMPLATE_IMAGES}sort-arrow-down.gif" style="border:none;" alt="Desc" />
	{else}
		<img src="{$TEMPLATE_IMAGES}sort-arrow-up.gif" style="border:none;" alt="Asc" />
	{/if}
    </a>
{else}
    <a href="javascript:void(0);" onclick="auctionObject.submitListForm('{$key}','{$filter_order_Dir}');">{$label}</a>
{/if}

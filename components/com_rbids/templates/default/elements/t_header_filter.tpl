{$inputsHiddenFilters}
{if $htmlLabelFilters|@count >0}
    <div>
		{foreach from=$htmlLabelFilters key=key item=item name=filters}
		  {$key} - <span class="rbid_filter">{$item}</span>{if !$smarty.foreach.filters.last},{/if}
    	{/foreach}
        	<a href="index.php?option=com_rbids&task={if $task=='myauctions'}myauctions{else}listauctions{/if}&reset=all&Itemid={$Itemid}">
                <img src="{$IMAGE_ROOT}remove_filter1.png" border="0" title="Remove Filters" alt="Remove filter"
                    onmouseover="this.src='{$IMAGE_ROOT}remove_filter2.png';"
                    onmouseout="this.src='{$IMAGE_ROOT}remove_filter1.png';" /></a>
    	<br /><br />
    </div>
{/if}
{if $task=='showSearchResults'}
    <a href="index.php?option=com_rbids&task=show_search&reload=1&Itemid={$Itemid}">{"COM_RBIDS_BACK_TO_SEARCH"|translate}</a>
{/if}

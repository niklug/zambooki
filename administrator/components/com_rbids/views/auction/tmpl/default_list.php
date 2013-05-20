<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
	JHTML::_('behavior.tooltip');
	JHTML::_('behavior.caption');
?>
<form action = "index.php?option=com_rbids" method = "post" name = "adminForm">
    <input type = "hidden" name = "task" value = "offers" />
    <input type = "hidden" name = "reset" value = "0" />
    <input type = "hidden" name = "boxchecked" value = "0" />
    <input type = "hidden" name = "filter_order" value = "<?php echo $this->order; ?>" />
    <input type = "hidden" name = "filter_order_Dir" value = "<?php echo $this->order_Dir; ?>" />

    <table>
        <tr>
            <td width = "100%">
                <span class = "label"><?php echo JText::_('COM_RBIDS_FILTER'); ?>:</span>
                <input type = "text" name = "search" id = "search" size = "30" value = "<?php echo $this->search;?>" class = "text_area"
                       title = "<?php echo JText::_('COM_RBIDS_FILTER_BY_TITLE_OR_ENTER_ADD_ID');?>" />
                <span class = "label"><?php echo JText::_('COM_RBIDS_USERNAME_FILTER'); ?>:</span>
                <input type = "text" name = "filter_authorid" id = "filter_authorid" size = "30" value = "<?php echo $this->filter_authorid;?>" class = "text_area"
                       title = "<?php echo JText::_('COM_RBIDS_BY_USERNAME');?>" />

		    <?php if ($this->cfg->admin_approval): ?>
		    <?php echo JText::_('COM_RBIDS_APPROVAL_STATUS'); ?>:
		    <?php echo JHtml::_('auctionapproved.selectlist', 'filter_approved', '', $this->filter_approved); ?>
		    <?php endif;?>
                <button onclick = "this.form.submit();"><?php echo JText::_('COM_RBIDS_GO'); ?></button>
                <button onclick = "this.form.reset.value='1';this.form.submit();"><?php echo JText::_('COM_RBIDS_RESET'); ?></button>
            </td>
            <td nowrap = "nowrap">

            </td>
        </tr>
    </table>
    <table class = "adminlist" cellspacing = "1">
        <thead>
        <tr>
            <th width = "5"><input type = "checkbox" name = "toggle" value = "" onclick = "checkAll(<?php echo count($this->auctions); ?>);" /></th>
            <th><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_TITLE'), 'a.title', $this->order_Dir, $this->order, 'offers'); ?></th>
		<?php if ($this->cfg->admin_approval): ?>
            <th width = "1%" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_APPROVAL_STATUS'), 'a.approved', $this->order_Dir, $this->order, 'offers'); ?></th>
		<?php endif;?>
            <th width = "1%" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_PUBLISHED'), 'a.published', $this->order_Dir, $this->order, 'offers'); ?></th>
            <th width = "1%" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_RUNNING_CLOSED'), 'a.close_offer', $this->order_Dir, $this->order, 'offers'); ?></th>
            <th width = "100"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_MIN_BID'), 'min_bid', $this->order_Dir, $this->order, 'offers'); ?></th>
            <th align = "center" width = "100"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_MAX_ACCEPTED_BID'), 'a.max_price', $this->order_Dir, $this->order, 'offers'); ?></th>
            <th align = "center" width = "120">
		    <?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_NR_BIDS'), 'nr_bids', $this->order_Dir, $this->order, 'offers'); ?> /
		    <?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_NR_BIDDERS'), 'nr_bidders', $this->order_Dir, $this->order, 'offers'); ?>
            </th>
            <th width = "1%" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_BLOCKED'), 'a.close_by_admin', $this->order_Dir, $this->order, 'offers'); ?></th>
            <th align = "center" width = "10"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_HITS'), 'a.hits', $this->order_Dir, $this->order, 'offers'); ?></th>
            <th align = "center" width = "20"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_END_DATE'), 'a.end_date', $this->order_Dir, $this->order, 'offers'); ?></th>
            <th width = "3%"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_AUCTIONID'), 'a.id', $this->order_Dir, $this->order, 'offers'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan = "12">
		    <?php echo $this->pagnation->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
	<?php
		$k = 0;
		for ($i = 0, $n = count($this->auctions); $i < $n; $i++) {
			$row = & $this->auctions[$i];
			$link = 'index.php?option=com_rbids&task=edit&cid[]=' . $row->id;
			$link_user = 'index.php?option=com_rbids&task=detailUser&cid[]=' . $row->userid;

			$img_publish = ($row->published) ? JHtml::_('image.administrator', 'admin/tick.png') : JHtml::_('image.administrator', 'admin/disabled.png');
			$alt_publish = ($row->published) ? JText::_('COM_RBIDS_PUBLISHED__CLICK_TO_UNPUBLISH') : JText::_('COM_RBIDS_UNPUBLISHED__CLICK_TO_PUBLISH');

			$img_block = ($row->close_by_admin) ? JHtml::_('image.administrator', 'admin/publish_r.png') : JHtml::_('image.administrator', 'admin/disabled.png');
			$alt_block = ($row->close_by_admin) ? JText::_('COM_RBIDS_BLOCKED__CLICK_TO_UNBLOCK') : JText::_('COM_RBIDS_RUNNING__CLICK_TO_BLOCK');

			$img_close = ($row->close_offer) ? JHtml::_('image.administrator', 'admin/publish_x.png') : JHtml::_('image.administrator', 'admin/publish_g.png');
			$alt_close = ($row->close_offer) ? JText::_('COM_RBIDS_CLOSED__CLICK_TO_OPEN') : JText::_('COM_RBIDS_RUNNING__CLICK_TO_CLOSE');

			$img_approved = ($row->approved) ? JHtml::_('image.administrator', 'admin/tick.png') : JHtml::_('image.administrator', 'admin/publish_x.png');
			$alt_approved = ($row->approved) ? JText::_('COM_RBIDS_APPROVED__CLICK_TO_CHANGE') : JText::_('COM_RBIDS_PENDING_APPROVAL__CLICK_TO_CHANGE');

			?>
                <tr class = "<?php echo "row$k"; ?>">

                    <td align = "center">
			    <?php echo JHTML::_('grid.id', $i, $row->id);?>
                    </td>
                    <td>
                        <a href = "<?php echo JRoute::_($link); ?>"><?php echo htmlspecialchars($row->title, ENT_QUOTES); ?></a><br />
                        <br />
			    <?php echo JText::_('COM_RBIDS_AUTHOR'); ?>: <a href = "<?php echo JRoute::_($link_user); ?>"><?php echo htmlspecialchars($row->editor, ENT_QUOTES); ?></a><br />
			    <?php echo JText::_('COM_RBIDS_CATEGORY');?>: <?php if ($row->name) echo htmlspecialchars($row->name, ENT_QUOTES); else echo "---"; ?><br />
                    </td>
			<?php if ($this->cfg->admin_approval): ?>
                    <td align = "center">
    				<span class = "editlinktip hasTip" title = "<?php echo JText::_($alt_approved);?>">
    				    <a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','approve_toggle')">
					        <?php echo $img_approved;?></a>
    				</span>
                    </td>
			<?php endif;?>
                    <td align = "center">
				<span class = "editlinktip hasTip" title = "<?php echo JText::_($alt_publish);?>">
				    <a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
					    <?php echo $img_publish;?></a>
				</span>
                    </td>
                    <td align = "center">
				<span class = "editlinktip hasTip" title = "<?php echo JText::_($alt_close);?>">
				    <a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','<?php echo $row->close_offer ? 'openoffer' : 'closeoffer' ?>')">
					    <?php echo $img_close;?>
                                    </a>
				</span>
                    </td>
                    <td nowrap = "nowrap" align = "center">
			    <?php echo isset($row->min_bid) ? $row->min_bid : "--"; ?>
                    </td>
                    <td nowrap = "nowrap" align = "center">
			    <?php echo $row->max_price, ",", $row->currency; ?>
                    </td>
                    <td nowrap = "nowrap" align = "center">
			    <?php echo ($row->nr_bids == 0) ? "--" : $row->nr_bids; ?>&nbsp;&nbsp; /&nbsp;&nbsp; <?php echo ($row->nr_bidders == 0) ? "--" : $row->nr_bidders; ?>
                    </td>

                    <td align = "center">
				<span class = "editlinktip hasTip" title = "<?php echo JText::_($alt_block);?>">
				    <a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','<?php echo $row->close_by_admin ? 'unblock' : 'block' ?>')">
					    <?php echo $img_block;?>
                                    </a>
				</span>
                    </td>
                    <td nowrap = "nowrap" align = "center">
			    <?php echo $row->hits; ?>
                    </td>
                    <td nowrap = "nowrap">
			    <?php echo JHtml::_('date', $row->end_date, $this->cfg->date_format . ' ' . $this->cfg->date_time_format); ?>
                    </td>
                    <td align = "center">
			    <?php echo $row->id; ?>
                    </td>
                </tr>
			<?php
			$k = 1 - $k;
		}
	?>
        </tbody>
    </table>

</form>

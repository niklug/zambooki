<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action = "index.php?option=com_rbids" method = "post" name = "adminForm">
    <input type = "hidden" name = "task" value = "reviews_administrator" />
    <input type = "hidden" name = "id" value = "" />
    <input type = "hidden" name = "boxchecked" value = "0" />
    <input type = "hidden" name = "filter_order" value = "<?php echo $this->order; ?>" />
    <input type = "hidden" name = "filter_order_Dir" value = "<?php echo $this->order_Dir; ?>" />
    <table class = "adminlist" cellspacing = "1">
        <thead>
        <tr>
            <th width = "5" align = "center">&nbsp;</th>
            <th width = "10%" class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_RATING_DATE'), 'modified', $this->order_Dir, $this->order, 'reviews_administrator'); ?></th>
            <th class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_AUCTION'), 'auctiontitle', $this->order_Dir, $this->order, 'reviews_administrator'); ?></th>
            <th width = "10%" class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_VOTER'), 'rauthor', $this->order_Dir, $this->order, 'reviews_administrator'); ?></th>
            <th width = "10%" class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_USER_RATED'), 'user_rated', $this->order_Dir, $this->order, 'reviews_administrator'); ?></th>
            <th width = "10%" class = "title"><?php echo JText::_("COM_RBIDS_VOTE_VALUE");?></th>
            <th><?php echo JText::_("COM_RBIDS_REVIEW_TEXT");?></th>
            <th width = "3%" class = "title"><?php echo JText::_("COM_RBIDS_DELETE"); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan = "8">
		    <?php echo $this->page->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
	<?php
		$k = 0;
		for ($i = 0, $n = count($this->ratings); $i < $n; $i++) {
			$row = &$this->ratings[$i];
			$img_delete = JHtml::_('image.administrator', 'admin/publish_x.png');
			?>
                <tr class = "<?php echo "row$k"; ?>">
                    <td align = "center"><?php echo JHTML::_('grid.id', $i, $row->id);?></td>
                    <td align = "center"><?php echo $row->modified;?></td>
                    <td align = "left"><a href = "index.php?option=com_rbids&task=edit&cid[]=<?php echo $row->auction_id;?>"><?php echo $row->auctiontitle;?></a></td>
                    <td align = "center"><a href = "index.php?option=com_rbids&task=detailUser&cid[]=<?php echo $row->voter;?>"><?php echo $row->rauthor;?></a></td>
                    <td align = "center"><a href = "index.php?option=com_rbids&task=detailUser&cid[]=<?php echo $row->user_rated;?>"><?php echo $row->urated;?></a></td>
                    <td align = "center"><?php echo $row->rating; ?></td>
                    <td align = "left"><span class = "has_tip"
                                             title = "<?php echo strip_tags($row->message); ?>"><?php echo (strlen($row->message) > 20) ? (substr($row->message, 0, 20) . "..") : $row->message; ?></span>
                    </td>
                    <td align = "center">
                        <a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','del_comment')">
				<?php echo $img_delete;?></a>
                    </td>
                </tr>
			<?php
			$k = 1 - $k;
		}
	?>
        </tbody>
    </table>
</form>

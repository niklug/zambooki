<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action = "index.php?option=com_rbids" method = "post" name = "adminForm">
    <input type = "hidden" name = "task" value = "comments_administrator" />
    <input type = "hidden" name = "id" value = "" />
    <input type = "hidden" name = "boxchecked" value = "0" />
    <input type = "hidden" name = "filter_order" value = "<?php echo $this->order; ?>" />
    <input type = "hidden" name = "filter_order_Dir" value = "<?php echo $this->order_Dir; ?>" />

    <table class = "adminlist" cellspacing = "1">
        <thead>
        <tr>
            <th width = "5" align = "center">&nbsp;</th>
            <th width = "10%"
                class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_MESSAGE_DATE'), 'modified', $this->order_Dir, $this->order, 'comments_administrator'); ?></th>
            <th class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_AUCTION'), 'auctiontitle', $this->order_Dir, $this->order, 'comments_administrator'); ?></th>
            <th width = "10%"
                class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_FROM_USER'), 'rauthor', $this->order_Dir, $this->order, 'comments_administrator'); ?></th>
            <th width = "10%"
                class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_TO_USER'), 'user_replied', $this->order_Dir, $this->order, 'comments_administrator'); ?></th>
            <th><?php echo "Message";?></th>
            <th width = "3%" class = "title"><?php echo JText::_("COM_RBIDS_PUBLISHUNPUBLISH"); ?></th>
            <th width = "3%" class = "title"><?php echo JText::_("COM_RBIDS_DELETE");?></th>
            <th width = "3%" class = "title"><?php echo JText::_("COM_RBIDS_PRIVATE_MESSAGE");?></th>
        </tr>
        </thead>
        <tbody>
	<?php
		$k = 0;
		for ($i = 0, $n = count($this->messages); $i < $n; $i++) {
			$row = &$this->messages[$i];
			$img_publish = ($row->published) ? JHtml::_('image.administrator', 'admin/tick.png') : JHtml::_('image.administrator', 'admin/disabled.png');
			$img_delete = JHtml::_('image.administrator', 'admin/publish_x.png');
			?>
                <tr class = "<?php echo "row$k"; ?>">
                    <td align = "center"><?php echo JHTML::_('grid.id', $i, $row->id);?></td>
                    <td align = "center"><?php echo JHtml::_('date', $row->modified, $this->cfg->date_format . ' ' . $this->cfg->date_time_format);?></td>
                    <td align = "left">
                        <a href = "index.php?option=com_rbids&task=edit&cid[]=<?php echo $row->auction_id;?>">
				<?php echo $row->auctiontitle;?></a></td>
                    <td align = "center"><a href = "index.php?option=com_rbids&task=detailUser&cid[]=<?php echo $row->userid1;?>">
			    <?php echo $row->rauthor;?></a></td>
                    <td align = "center"><a href = "index.php?option=com_rbids&task=detailUser&cid[]=<?php echo $row->userid2;?>">
			    <?php echo $row->user_replied;?></a></td>
                    <td align = "left"><?php echo $row->message; ?></td>
                    <td align = "center">
                        <a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','toggle_comment')">
				<?php echo $img_publish;?></a>
                    </td>
                    <td align = "center">
                        <a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','del_comment')">
				<?php echo $img_delete;?></a>
                    </td>
                    <td align = "center">

			    <?php
			    echo $row->private ? JHtml::_('image.administrator', 'private_yes.png', 'components/com_rbids/assets/images/') :
				    JHtml::_('image.administrator', 'private_no.png', 'components/com_rbids/assets/images/');
			    ?>


                </tr>
			<?php
			$k = 1 - $k;
		}
	?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan = "9">
		    <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
    </table>

</form>

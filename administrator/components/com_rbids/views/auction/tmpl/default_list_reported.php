<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
	JHtml::_('behavior.tooltip');
	JFactory::getDocument()->addScriptDeclaration("
			    Joomla.submitbutton = function(action){
			        frm=document.adminForm;
			        frm.status.value=action;
			        if(action=='block')
			                frm.task.value='block';
			        else
			                frm.task.value='change_reported_status';
			        Joomla.submitform(frm.task.value);
			    }
		");
?>
<form action = "index.php?option=com_rbids" method = "post" name = "adminForm">
    <input type = "hidden" name = "task" value = "reported_offers" />
    <input type = "hidden" name = "status" value = "" />
    <input type = "hidden" name = "boxchecked" value = "0" />
    <input type = "hidden" name = "filter_order" value = "<?php echo $this->order; ?>" />
    <input type = "hidden" name = "filter_order_Dir" value = "<?php echo $this->order_Dir; ?>" />

    <table>
        <tr>
            <td width = "100%">
                <span class = "label"><?php echo JText::_('COM_RBIDS_FILTER'); ?>:</span>
                <input type = "text"
                       name = "search"
                       id = "search"
                       size = "30"
                       value = "<?php echo $this->search;?>"
                       class = "text_area"
                       style = "font-size: 12px;"
                       title = "<?php echo JText::_('COM_RBIDS_FILTER_BY_TITLE_OR_ENTER_ADD_ID');?>"
                        />
                <button onclick = "this.form.submit();"><?php echo JText::_('COM_RBIDS_GO'); ?></button>
                <span class = "label"><?php echo JText::_('COM_RBIDS_FILTER_STATUS'); ?>:</span>&nbsp;
                <select name = "filter_solved" onchange = "this.form.submit();">
                    <option value = ""  <?php if ($this->solved == '') echo "selected"; ?> ><?php echo JText::_("COM_RBIDS_ALL");?></option>
                    <option value = "1" <?php if ($this->solved == '1') echo "selected"; ?> ><?php echo JText::_("COM_RBIDS_SOLVED");?></option>
                    <option value = "0" <?php if ($this->solved == '0') echo "selected"; ?> ><?php echo JText::_("COM_RBIDS_UNSOLVED");?></option>
                </select>
            </td>
            <td nowrap = "nowrap" align = "right">
            </td>
        </tr>
    </table>

    <table class = "adminlist" cellspacing = "1">
        <thead>
        <tr>
            <th width = "5"><input type = "checkbox" name = "toggle" value = "" onclick = "checkAll(<?php echo count($this->auctions); ?>);" /></th>
            <th width = "3%"
                class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_SOLVED__IN_PROCESS'), 'solved', $this->order_Dir, $this->order, 'reported_offers'); ?></th>
            <th class = "title" width = "15%"
                nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_REPORTED_DATE'), 'modified', $this->order_Dir, $this->order, 'reported_offers'); ?></th>
            <th class = "title" width = "25%"
                nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_REPORTED_AUCTION'), 'title', $this->order_Dir, $this->order, 'reported_offers'); ?></th>
            <th class = "title" width = "10%"
                nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_REPORTED_BY'), 'username', $this->order_Dir, $this->order, 'reported_offers'); ?></th>
            <th class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_MESSAGE'), 'message', $this->order_Dir, $this->order, 'reported_offers'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan = "7">
		    <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
	<?php
		$k = 0;
		for ($i = 0, $n = count($this->auctions); $i < $n; $i++) {
			$row = & $this->auctions[$i];
			$link = 'index.php?option=com_rbids&task=edit&cid[]=' . $row->auction_id;
			$img = $row->solved ? 'tick.png' : 'publish_x.png';
			$img2 = $row->processing ? 'tick.png' : 'publish_x.png';
			$task1 = $row->solved ? 'unsolved' : 'solved';
			$alt = $row->solved ? JText::_('COM_RBIDS_SOLVED__CLICK_TO_REOPEN') : JText::_('COM_RBIDS_UNSOLVED__CLICK_TO_MARK_AS_DONE');
			$message = (strlen($row->message) > 30) ? substr($row->message, 0, 30) . ".." : $row->message;
			$title = (strlen($row->title) > 30) ? substr($row->title, 0, 30) . ".." : $row->title;
			if ($row->close_by_admin) $title .= "(" . JText::_("COM_RBIDS_CLOSED_BY_ADMIN") . " )";

			?>
                <tr class = "<?php echo "row$k"; ?>">
                    <td align = "center">
			    <?php echo JHTML::_('grid.id', $i, $row->auction_id); ?>
                    </td>
                    <td align = "center">
                        <a href = "javascript: void(0);" onclick = "return rbids_listItemTask('cb<?php echo $i;?>','<?php echo $task1;?>')">
                            <img src = "<?php echo JURI::base(); ?>components/com_rbids/assets/images/<?php echo $img;?>" width = "12" height = "12" border = "0"
                                 alt = "<?php echo $alt; ?>" title = "<?php echo $alt; ?>" />
                        </a>
                    </td>
                    <td align = "center">
			    <?php echo JHtml::_('date', $row->modified, $this->cfg->date_format . ' ' . $this->cfg->date_time_format); ?>
                    </td>
                    <td>
                        <a href = "<?php echo JRoute::_($link); ?>"><?php echo htmlspecialchars($title, ENT_QUOTES); ?></a>
                    </td>
                    <td align = "center">
			    <?php if ($row->username) echo htmlspecialchars($row->username, ENT_QUOTES); else echo "---"; ?>
                    </td>
                    <td align = "center">
			    <?php echo htmlspecialchars($message, ENT_QUOTES);; ?>
                    </td>
                    <td>
                        <a href = "index.php?option=com_rbids&task=write_admin_message&return_task=reported_offers&auction_id=<?php echo $row->auction_id; ?>"><?php echo JText::_("COM_RBIDS_WRITE_MESSAGE"); ?></a>
                    </td>
                </tr>
			<?php
			$k = 1 - $k;
		}
	?>
        </tbody>
    </table>

</form>

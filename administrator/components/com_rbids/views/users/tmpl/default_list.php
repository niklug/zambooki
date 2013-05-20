<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
	JHTML::_('behavior.tooltip');
?>

<form action = "index.php?option=com_rbids" method = "post" name = "adminForm">
    <input type = "hidden" name = "task" value = "users" />
    <input type = "hidden" name = "boxchecked" value = "0" />
    <input type = "hidden" name = "filter_order" value = "<?php echo $this->order; ?>" />
    <input type = "hidden" name = "filter_order_Dir" value = "<?php echo $this->order_Dir; ?>" />
    <table>
        <tr>
            <td width = "100%">
		    <span class="label"><?php echo JText::_('COM_RBIDS_FILTER'); ?>:</span>
                <input type = "text"
                       name = "search"
                       id = "search"
                       size="30"
                       style="font-size: 12px;"
                       title = "<?php echo JText::_('COM_RBIDS_FILTER_BY_TITLE_OR_ENTER_ADD_ID');?>"
                       value = "<?php echo $this->search;?>" class = "text_area" onchange = "document.adminForm.submit();"
	                />
                <button onclick = "this.form.submit();"><?php echo JText::_('COM_RBIDS_GO'); ?></button>
                <button onclick = "document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_RBIDS_RESET'); ?></button>
            </td>
            <td nowrap = "nowrap">
		    <?php
		    ?>
            </td>
        </tr>
    </table>

    <table class = "adminlist" cellspacing = "1">
        <thead>
        <tr>
            <th width = "5"><input type = "checkbox" name = "toggle" value = "" onclick = "checkAll(<?php echo count($this->users); ?>);" /></th>
            <th width = "5%" class = "title"><?php echo JText::_("COM_RBIDS_BLOCKUNBLOCK"); ?></th>
            <th class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_NAME'), 'u.name', $this->order_Dir, $this->order, 'users'); ?></th>
            <th width = "5%" class = "title"><?php echo JText::_("COM_RBIDS_VERIFIED"); ?></th>
            <th width = "5%" class = "title"><?php echo JText::_("COM_RBIDS_POWERSELLER"); ?></th>
            <th class = "title" width = "15%" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_USERNAME'), 'u.username', $this->order_Dir, $this->order, 'users'); ?></th>
            <th width = "5%" class = "title"><?php echo JText::_("COM_RBIDS_IS_SELLER"); ?></th>
            <th width = "5%" class = "title"><?php echo JText::_("COM_RBIDS_IS_BIDDER"); ?></th>
            <th class = "title" width = "20%" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_EMAIL'), 'u.email', $this->order_Dir, $this->order, 'users'); ?></th>

            <th align = "center" width = "70px" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_TOTAL'), 'nr_raff', $this->order_Dir, $this->order, 'users'); ?></th>
            <th align = "center" width = "70px" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_PUBLISHED'), 'nr_published', $this->order_Dir, $this->order, 'users'); ?></th>
            <th align = "center" width = "70px" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_FEATURED'), 'nr_featured', $this->order_Dir, $this->order, 'users'); ?></th>
            <th align = "center" width = "70px" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_CLOSED'), 'nr_closed', $this->order_Dir, $this->order, 'users'); ?></th>
            <th align = "center" width = "70px" nowrap = "nowrap"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_BLOCKED'), 'nr_blocked', $this->order_Dir, $this->order, 'users'); ?></th>


            <th align = "center" width = "20%"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_COUNTRY'), 'b.country_name', $this->order_Dir, $this->order, 'users'); ?></th>
            <th width = "3%" class = "title"><?php echo JHTML::_('grid.sort', JText::_('COM_RBIDS_USERID'), 'b.userid', $this->order_Dir, $this->order, 'users'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan = "16">
		    <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
	<?php
		$k = 0;
		for ($i = 0, $n = count($this->users); $i < $n; $i++) {
			$row = &$this->users[$i];
			$link = 'index.php?option=com_rbids&task=detailUser&cid[]=' . $row->userid;
			?>
                <tr class = "<?php echo "row$k"; ?>">
                    <td align = "center">
			    <?php echo JHTML::_('grid.id', $i, $row->userid); ?>
                    </td>
			<?php

			if ($row->block) {
				$img = JHtml::_('image.administrator', 'admin/publish_x.png');
				$alt = JText::_('COM_RBIDS_CLICK_TO_UNBLOCK_USER');
			} else {
				$img = JHtml::_('image.administrator', 'admin/publish_g.png');
				$alt = JText::_('COM_RBIDS_CLICK_TO_BLOCK_USER');
			}
			if ($row->verified) {
				$imgv = 'verified_1.gif';
				$altv = JText::_('COM_RBIDS_VERIFIED');
			} else {
				$imgv = 'verified_0.gif';
				$altv = JText::_('COM_RBIDS_UNVERIFIED');
			}

			if ($row->powerseller) {
				$imgp = 'verified_1.gif';
				$altp = JText::_('COM_RBIDS_UNSET_POWERSELLER');
			} else {
				$imgp = 'verified_0.gif';
				$altp = JText::_('COM_RBIDS_SET_POWERSELLER');
			}

			if (RBidsHelperTools::isSeller($row->userid)) {
				$imgs = 'f_can_sell1.gif';
				$alts = JText::_('COM_RBIDS_IS_SELLER');
				$tasks = 'unsetSeller';
			} else {
				$imgs = 'f_can_sell2.gif';
				$alts = JText::_('COM_RBIDS_NOT_SELLER');
				$tasks = 'setSeller';
			}

			if (RBidsHelperTools::isBidder($row->userid)) {
				$imgb = 'f_can_buy1.gif';
				$altb = JText::_('COM_RBIDS_IS_BIDDER');
				$taskb = 'unsetBidder';
			} else {
				$imgb = 'f_can_buy2.gif';
				$altb = JText::_('COM_RBIDS_NOT_BIDDER');
				$taskb = 'setBidder';
			}
			?>
                    <td nowrap = "nowrap" align = "center">
				<span class = "editlinktip hasTip" title = "<?php echo JText::_($alt);?>">
					<a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','<?php echo $row->block === '1' ? 'unblockuser' : 'blockuser' ?>')">
						<?php echo $img;?>
                                        </a>
				</span>
                    </td>
                    <td>
			    <?php if ($row->id && $this->cfg->profile_mode == 'component') { ?>
                        <a href = "<?php echo JRoute::_($link); ?>"><?php echo $row->surname, " ", $row->name; ?></a>
			    <?php } else { ?>
			    <?php echo $row->surname, " ", $row->name; ?>
			    <?php } ?>
                    </td>
			<?php if ($row->id || $this->cfg->profile_mode != 'component') { ?>
                    <td nowrap = "nowrap" align = "center">
    				<span class = "editlinktip hasTip" title = "<?php echo JText::_($altv);?>">
    					<a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','<?php echo $row->verified ? 'unsetVerify' : 'setVerify' ?>')">
                                                <img src = "<?php echo JURI::root();?>components/com_rbids/images/<?php echo $imgv;?>" width = "16" height = "16" border = "0">
                                            </a>
    				</span>
                    </td>
                    <td nowrap = "nowrap" align = "center">
    				<span class = "editlinktip hasTip" title = "<?php echo JText::_($altp);?>">
    				<a href = "javascript:void(0);" onclick = "return listItemTask('cb<?php echo $i;?>','<?php echo $row->powerseller ? 'unsetPowerseller' : 'setPowerseller' ?>')">
                                        <img src = "<?php echo JURI::root();?>components/com_rbids/images/<?php echo $imgp;?>" width = "16" height = "16" border = "0">
                                    </a>
    				</span>
                    </td>
			<?php } else { ?>
                    <td nowrap = "nowrap" align = "center" colspan = "2"><?php echo JText::_("COM_RBIDS_PROFILE_NOT_FILLED");?></td>
			<?php } ?>
                    <td align = "left">
			    <?php echo $row->username; ?>
                    </td>
                    <td nowrap = "nowrap" align = "center">
				<span class = "editlinktip hasTip" title = "<?php echo JText::_($alts);?>">
				    <a href = "index.php?option=com_users&task=user.edit&id=<?php echo $row->userid;?>">
                                        <img src = "<?php echo JURI::root();?>components/com_rbids/images/<?php echo $imgs;?>" width = "16" height = "16" border = "0">
                                    </a>
				</span>
                    </td>
                    <td nowrap = "nowrap" align = "center">
				<span class = "editlinktip hasTip" title = "<?php echo JText::_($altb);?>">
                    <a href = "index.php?option=com_users&task=user.edit&id=<?php echo $row->userid;?>">
                        <img src = "<?php echo JURI::root();?>components/com_rbids/images/<?php echo $imgb;?>" width = "16" height = "16" border = "0">
                    </a>
				</span>
                    </td>
                    <td align = "left">
			    <?php echo $row->email; ?>
                    </td>

                    <td align = "center"><?php echo $row->nr_aucs; ?></td>
                    <td align = "center"><?php echo $row->nr_published; ?></td>
                    <td align = "center"><?php echo $row->nr_featured; ?></td>
                    <td align = "center"><?php echo $row->nr_closed; ?></td>
                    <td align = "center"><?php echo $row->nr_blocked; ?></td>

                    <td nowrap = "nowrap">
			    <?php echo $row->country; ?>
                    </td>
                    <td>
			    <?php echo $row->userid; ?>
                    </td>
                </tr>
			<?php
			$k = 1 - $k;
		}
	?>
        </tbody>
    </table>

</form>

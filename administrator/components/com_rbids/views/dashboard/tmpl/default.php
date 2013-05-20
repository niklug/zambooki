<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
//Ordering allowed ?
	JHTML::_('behavior.tooltip');
?>
<form action = "index.php" method = "post" name = "adminForm">
    <input type = "hidden" name = "option" value = "com_rbids" />
    <input type = "hidden" name = "task" value = "dashboard" />
    <fieldset class = "adminFieldList">
        <legend><?php echo JText::_('COM_RBIDS_DASHBOARD_STATISTICS')?></legend>
        <table class = "adminlist">
            <tr>
                <td width = "33%" align = "center">
                    <table width = "100%" class = "paramlist admintable">
                        <tr>
                            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_TOTAL"); ?>:</td>
                            <td class = "paramlist_value"><strong><?php  echo $this->lists["nr_total"];?></strong></td>
                        </tr>
                        <tr>
                            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_ACTIVE"); ?>:</td>
                            <td class = "paramlist_value"><strong><?php  echo $this->lists["nr_active"];?></strong></td>
                        </tr>
                        <tr>
                            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_PUBLISHED"); ?>:</td>
                            <td class = "paramlist_value"><strong><?php  echo $this->lists["nr_published"];?></strong></td>
                        </tr>
                    </table>
                </td>
                <td width = "33%" align = "center">
                    <table width = "100%" class = "paramlist admintable">
                        <tr>
                            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_UNPUBLISHED"); ?>:</td>
                            <td class = "paramlist_value"><strong><?php  echo $this->lists["nr_unpublished"];?></strong></td>
                        </tr>
                        <tr>
                            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_EXPIRED"); ?>:</td>
                            <td class = "paramlist_value"><strong><?php  echo $this->lists["nr_expired"];?></strong></td>
                        </tr>
                        <tr>
                            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_BLOCKED"); ?>:</td>
                            <td class = "paramlist_value"><strong><?php  echo $this->lists["nr_blocked"];?></strong></td>
                        </tr>
                    </table>
                </td>
                <td align = "center">
                    <table width = "100%" class = "paramlist admintable">
                        <tr>
                            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_REGISTERED_USERS"); ?>:</td>
                            <td class = "paramlist_value"><strong><?php  echo $this->lists["nr_r_users"];?></strong></td>
                        </tr>
                        <tr>
                            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_ACTIVE_USERS"); ?>:</td>
                            <td class = "paramlist_value"><strong><?php  echo $this->lists["nr_a_users"];?></strong></td>
                        </tr>
                        <tr>
                            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_MESSAGES"); ?>:</td>
                            <td class = "paramlist_value"><strong><?php  echo $this->lists["nr_messages"];?></strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset class = "adminFieldList">
        <legend><?php echo JText::_('COM_RBIDS_DASHBOARD_LATEST_5_AUCTIONS')?></legend>
        <table class = "adminlist" cellspacing = "1">
            <thead>
            <tr>
                <th class = "title" width = "50%" nowrap = "nowrap"><?php echo JText::_('COM_RBIDS_TITLE'); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_START_DATE"); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_MIN_BID"); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_MAX_BID"); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_END_DATE"); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_DASHBOARD_OWNER"); ?></th>
            </tr>
            </thead>
            <tbody>
	    <?php if (count($this->latest5auctions)): ?>
		    <?php foreach ($this->latest5auctions as $k => $auct): ?>
                <tr class = "<?php echo 'row' . $k % 2; ?>">
                    <td>
			    <?php echo JHtml::_('link', 'index.php?option=com_rbids&task=edit&cid[]=' . $auct->id, $auct->title, array('title' => JText::_('COM_RBIDS_DASHBOARD_VIEW_AUCTION_DETAILS'))); ?>
                    </td>
                    <td align = "center">
			    <?php echo $auct->start_date; ?>
                    </td>

			<?php if ($auct->min_bid && $auct->max_bid): ?>
                    <td align = "center">
			    <?php echo $auct->min_bid . ' ' . $auct->currency; ?>
                    </td>
                    <td align = "center">
			    <?php echo $auct->max_bid . ' ' . $auct->currency; ?>
                    </td>
			<?php else: ?>
                    <td colspan = "2" align = "center"><?php echo JText::_('COM_RBIDS_DASHBOARD_NO_BIDS_FOR_AUCTION'); ?></td>
			<?php endif; ?>


                    <td align = "center">
			    <?php echo $auct->end_date; ?>
                    </td>
                    <td align = "center">
			    <?php echo JHtml::_('link', 'index.php?option=com_rbids&task=detailUser&cid[]=' . $auct->user_id, $auct->owner, array('title' => JText::_('COM_RBIDS_DASHBOARD_VIEW_USER_DETAILS'))); ?>
                    </td>
                </tr>
			    <?php endforeach; ?>
		    <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
    <fieldset class = "adminFieldList">
        <legend><?php echo JText::_('COM_RBIDS_DASHBOARD_LATEST_5_PAYMENTS')?></legend>
        <table class = "adminlist" cellspacing = "1">
            <thead>
            <tr>
                <th class = "title" width = "40%" nowrap = "nowrap"><?php echo JText::_('COM_RBIDS_DASHBOARD_ORDER_NUMBER'); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_DASHBOARD_DATE"); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_DASHBOARD_AMOUNT"); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_DASHBOARD_METHOD"); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_DASHBOARD_STATUS"); ?></th>
                <th class = "title" nowrap = "nowrap"><?php echo JText::_("COM_RBIDS_DASHBOARD_USER"); ?></th>
            </tr>
            </thead>
            <tbody>
	    <?php if (count($this->latest5payments)): ?>
		    <?php foreach ($this->latest5payments as $k => $paylog): ?>
                <tr class = "<?php echo 'row' . $k % 2; ?>">
                    <td><?php echo JHtml::_('link', 'index.php?option=com_rbids&task=orders.viewdetails&id=' . $paylog->orderid, $paylog->orderid, array('title' => JText::_('COM_RBIDS_DASHBOARD_VIEW_ORDER_DETAILS'))); ?></td>
                    <td align = "center"><?php echo $paylog->date; ?></td>
                    <td align = "right"><?php echo JHtml::_('link', 'index.php?option=com_rbids&task=payments.viewdetails&id=' . $paylog->id, $paylog->amount . ' ' . $paylog->currency, array('title' => JText::_('COM_RBIDS_DASHBOARD_VIEW_PAY_DETAILS'))); ?></td>
                    <td><?php echo $paylog->payment_method; ?></td>
                    <td align = "center"><?php echo $paylog->status; ?></td>
                    <td><?php echo JHtml::_('link', 'index.php?option=com_rbids&task=detailUser&cid[]=' . $paylog->userid, $paylog->username, array('title' => JText::_('COM_RBIDS_DASHBOARD_VIEW_USER_DETAILS'))); ?></td>
                </tr>
			    <?php endforeach; ?>
		    <?php endif; ?>
            </tbody>
        </table>
    </fieldset>

</form>

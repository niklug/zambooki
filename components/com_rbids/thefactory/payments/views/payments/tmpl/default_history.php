<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- User Profile Tabs -->
<div align = "left" style = "text-align:left;" xmlns = "http://www.w3.org/1999/html">
    <ul id = "auction_tabmenu">
        <li>
            <a class = "inactive" href = "<?php echo RBidsHelperRoute::getUserdetailsRoute(); ?>">
		    <?php echo JText::_('COM_RBIDS_MY_DETAILS'); ?></a>
        </li>
        <li>
            <a class = "active" href = "<?php echo RBidsHelperRoute::getPaymentsHistoryRoute(); ?>">
		    <?php echo JText::_('COM_RBIDS_PAYMENT_BALANCE'); ?></a>
        </li>
        <li>
            <a class = "inactive" href = "<?php echo RBidsHelperRoute::getUserRatingsRoute(); ?>">
		    <?php echo JText::_('COM_RBIDS_MY_RATINGS'); ?></a>
        </li>
    </ul>
</div>

<span class = "v_spacer_15"></span>

<div class = "box_balance_info">

    <table id = "table_box_balance_info_box">
        <!-- Total Earnings -->
        <tr>
            <td><?php echo JText::_('FACTORY_LEGEND_TOTAL_EARNINGS'); ?>:</td>
            <td align = "right"><span
                    class = "label"><?php echo number_format($this->balance->balance, 2) + number_format($this->balance->withdrawn_until_now, 2) . ' ' . $this->balance->currency; ?></span></td>
        </tr>
        <!-- Withdraw until now -->
        <tr>
            <td><span class = "grey"><?php echo JText::_('FACTORY_LEGEND_REQ_WITHDRAWAL'); ?>:</span></td>
            <td align = "right"><span class = "label"><span class = "grey"><?php echo number_format($this->balance->req_withdraw, 2) . ' ' . $this->balance->currency; ?></span></span></td>
        </tr>
        <!-- Withdraw pending -->
        <tr>
            <td><?php echo JText::_('FACTORY_LEGEND_WITHDRAWN'); ?>:</td>
            <td align = "right"><span class = "label"><?php echo number_format($this->balance->withdrawn_until_now, 2) . ' ' . $this->balance->currency; ?></span></td>
        </tr>
        <!-- Balance status -->
        <tr>
            <td><?php echo JText::_("FACTORY_YOUR_CURRENT_BALANCE_IS");  ?>:</td>
            <td align = "right"><span class = "label"><span class = "my_balance_amount"><?php echo number_format($this->balance->balance, 2) . '</span> ' . $this->balance->currency; ?></span></td>
        </tr>
    </table>

</div>

<!-- Add funds to site balance -->
<div class = "box_balance_actions">
    <form action = "index.php" method = "post" name = "paymentform">
        <input name = "option" type = "hidden" value = "<?php echo APP_EXTENSION;?>">
        <input name = "task" type = "hidden" value = "balance.checkout">
        <input name = "Itemid" type = "hidden" value = "<?php echo $this->Itemid; ?>">
        <table width = "65%" id = "table_add_funds_box">
            <tr>
                <td colspan = "2"><?php echo JText::_("FACTORY_ADD_FUNDS_TO_YOUR_SITE_BALANCE");?></td>
            </tr>
            <tr>
                <td><input name = "amount" value = "" size = "6" type = "text" class = "inputbox" /> <!-- 1 space --><?php echo $this->currency; ?></td>
                <td align = "center">
                    <input type = "submit"
                           name = "submit"
                           class = "button"
                           value = "<?php echo JText::_("FACTORY_BUTTON_PURCHASE");?>"
                            />
                </td>
            </tr>
        </table>
    </form>
</div>

<span class = "v_spacer_15"></span>

<!-- Withdraw funds from site balance -->
<div class = "box_balance_actions" style = "margin-top: 13px;">

    <form action = "index.php" method = "post" name = "paymentform">
        <input name = "option" type = "hidden" value = "<?php echo APP_EXTENSION;?>">
        <input name = "task" type = "hidden" value = "balance.reqWithdraw">
        <input name = "Itemid" type = "hidden" value = "<?php echo $this->Itemid; ?>">
        <input name = "currency" type = "hidden" value = "<?php echo $this->currency?>">

        <table width = "auto" id = "table_withdraw_funds_box">
            <tr>
                <td colspan = "2"><?php echo JText::_("FACTORY_WITHDRAW_FUNDS_FROM_YOUR_SITE_BALANCE");?></td>
            </tr>
            <tr>
                <td style = "">
                    <input name = "amount"
                           id = "withdraw_amount"
                           value = ""
                           size = "6"
                           type = "text"
                           class = "inputbox"
			    <?php if ($this->balance->req_withdraw >= $this->balance->balance): ?>
                           title = "<?php echo JText::_('FACTORY_TITLE_REQ_EXCEED_BALANCE'); ?>"
                           disabled = "disabled"
			    <?php endif; ?>
                            /> <!-- 1 space --><?php echo $this->currency?>
                </td>
                <td align = "center"><input type = "submit"
                                            name = "submit"
                                            class = "button"
			<?php if ($this->balance->req_withdraw >= $this->balance->balance): ?>
                                            title = "<?php echo JText::_('FACTORY_TITLE_REQ_EXCEED_BALANCE'); ?>"
                                            disabled = "disabled"
				<?php else: ?>
				<?php // ToDo Factory: Use jQuery to create a pretty validation ?>
                                            onclick = "if(document.getElementById('withdraw_amount').value) {
                                                    return confirm('<?php echo JText::_('FACTORY_CONFIRM_REQUEST'); ?> ' +
                                                    document.getElementById('withdraw_amount').value +
                                                    ' <?php echo $this->currency; ?>') ?  true : false;
                                                    } else {
                                                    alert('<?php echo JText::_('FACTORY_ALERT_NO_AMOUNT_REQ'); ?> ');
                                                    document.getElementById('withdraw_amount').focus();
                                                    return false;
                                                    }
                                                    "
				<?php endif; ?>
                                            value = "<?php echo JText::_("FACTORY_BUTTON_WITHDRAW");?>"
                        /></td>
            </tr>
        </table>
    </form>

</div>

<span class = "v_spacer_15"></span>
<span class = "v_spacer_15"></span>

<!-- My Payment History -->
<div style = "float: left;width: 100%;">
    <h3 style = "text-transform: uppercase;"><?php echo JText::_("FACTORY_PAYMENTS_HISTORY");?></h3>

    <form action = "index.php?option=<?php echo APP_EXTENSION;?>&task=payments.history&Itemid=<?php echo $this->Itemid; ?>" method = "post" name = "paymentform">

        <div style = "text-align: right;margin-bottom: 5px;">
	        <span class = "label"><?php echo JText::_('FACTORY_FILTER_TYPE') . '</span>: ' . $this->lists['type'];?>
                    <span class = "label"><?php echo JText::_('FACTORY_FILTER_STATUS') . '</span>: ' . $this->lists['status'];?>
        </div>
        <table id = "table_payments_history_box" style = "width: 100%;">
            <thead>
            <tr>
                <th><?php echo JText::_("FACTORY_ORDER");?></th>
                <th width = "180px"><?php echo JText::_("FACTORY_DATE");?></th>
                <th><?php echo JText::_('FACTORY_ORDER_DETAILS'); ?></th>
                <th style = "width: 100px;"><?php echo JText::_("FACTORY_AMOUNT");?></th>
                <th style = "width: 70px;"><?php echo JText::_("FACTORY_STATUS");?></th>
            </tr>
            </thead>

		<?php foreach ($this->orders as $order): ?>

		<?php
		// Check if this order contain either withdraw or commission item
		$isWithdraw = '';
		$isCommission = '';
		$classUsed = '';
		if ($this->modelOrders->getOrderItems($order->id, 'withdraw')) {
			$isWithdraw = true;
			$classUsed = 'withdraw';
		} else if ($this->modelOrders->getOrderItems($order->id, 'comission')) { // In db is used 'comission' instead of 'commission'
			$isCommission = true;
			$classUsed = 'commission';
		}
		?>

		<?php $oItems = $this->modelOrders->getOrderItems($order->id); ?>

            <tr class = "<?php echo $classUsed; ?>">
                <td class = "integer">
			<?php if ($isWithdraw): ?>
			<?php echo JHtml::_('link', 'index.php?option=' . APP_EXTENSION . '&task=orderprocessor.details&orderid=' . $order->id . '&Itemid=' . $this->Itemid, str_pad($order->id, 5, '0', STR_PAD_LEFT)); ?>
			<?php else: ?>
			<?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT); ?>
			<?php endif;?>
                </td>
                <td><?php echo JHtml::_('date', $order->orderdate, $this->cfg->date_format . ' ' . $this->cfg->date_time_format);?></td>
                <td><?php echo $oItems[0]->itemdetails; ?></td>
                <td class = "integer" style = "white-space: nowrap;"><?php echo number_format($order->order_total, 2), " ", $order->order_currency;?></td>

                <td>
			<?php if ($isWithdraw): ?>

                    <span style = "color:#AAAAAA">
			<?php if ($order->status == 'P') echo JText::_("FACTORY_PENDING"); ?>
			    <?php if ($order->status == 'C') echo JText::_("FACTORY_COMPLETED"); ?>
			    <?php if ($order->status == 'X') echo JText::_("FACTORY_CANCEL"); ?>
	                </span>

			<?php else: ?>

                    <a href = "index.php?option=<?php echo APP_EXTENSION;?>&task=orderprocessor.details&orderid=<?php echo $order->id;?>&Itemid=<?php echo $this->Itemid;?>">
			    <?php if ($order->status == 'P') echo JText::_("FACTORY_PENDING");?>
			    <?php if ($order->status == 'C') echo JText::_("FACTORY_COMPLETED");?>
			    <?php if ($order->status == 'X') echo JText::_("FACTORY_CANCEL");?>
                    </a>

			<?php endif;?>
                </td>

            </tr>
		<?php endforeach;?>
        </table>


        <table width = "100%" class = "table_pagination_box pagination">
            <tr>
                <td style = "text-align: left;"><span class = "label"><?php echo $this->pagination->getPagesCounter(); ?></span></td>
                <td style = "text-align: right;">
                    <span class = "label"><?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;</span>
			<?php echo $this->pagination->getLimitBox(); ?></td>
            </tr>
            <tr class = "pagination_links">
                <td colspan = "2"><?php echo $this->pagination->getPagesLinks(); ?></td>
            </tr>
        </table>

    </form>
</div>

<?php defined('_JEXEC') or die('Restricted access'); ?>
<h1>
	<?php echo JText::_('FACTORY_ORDER_DETAILS');?>
    <span style = 'float: right;'>
	    <?php echo JHtml::_('link', 'index.php?option=' . APP_EXTENSION . '&task=payments.history&Itemid=' . $this->Itemid, JText::_('FACTORY_BACK')); ?>
    </span>
</h1>
<h3><?php echo JText::_('FACTORY_ORDER_NUMBER') . ': <span style="font-size:18px;font-weight:bold;">' . $this->order->id . '</span>';?></h3>
<h3><?php echo JText::_('FACTORY_ORDER_DATE'), ': ', JHtml::_('date', $this->order->orderdate, $this->cfg->date_format . ' ' . $this->cfg->date_time_format);?></h3>
<h2><?php echo JText::_('FACTORY_ORDER_STATUS'), ': ';?>
	<?php
	if ($this->order->status == 'P') {
		echo JText::_("FACTORY_PENDING");

		if (!$this->isCommission && !$this->isWithdraw && $this->user->id == $this->order->userid) {
			echo '<span style="float:right;">' . JHtml::link('index.php?option=' . APP_EXTENSION . '&task=payments.cancel&id=' . $this->order->id . '&Itemid=' . $this->Itemid,
				JText::_('FACTORY_CANCEL'), array('class' => 'cancel_link_button')) . '</span>';
		}
	}
	?>
	<?php if ($this->order->status == 'C') echo JText::_("FACTORY_COMPLETED");?>
	<?php if ($this->order->status == 'X') echo JText::_("FACTORY_CANCEL");?>
</h2>

<table width = "100%" class = "table_order_details_box">
    <thead>
    <tr>
        <th>#</th>
        <th><?php echo JText::_('FACTORY_ORDER_ITEM');?></th>
        <th><?php echo JText::_('FACTORY_QUANTITY');?></th>
        <th><?php echo JText::_('FACTORY_UNIT_PRICE');?></th>
        <th><?php echo JText::_('FACTORY_TOTAL_PRICE');?></th>
    </tr>
    </thead>
	<?php for ($i = 0; $i < count($this->order_items); $i++): ?>
    <tr>
        <td><?php echo $i + 1;?>.</td>
        <td style = "text-align: left"><?php echo $this->order_items[$i]->itemdetails;?></td>
        <td><?php echo $this->order_items[$i]->quantity;?></td>
        <td><?php echo number_format($this->order_items[$i]->price / $this->order_items[$i]->quantity, 2), " ", $this->order_items[$i]->currency;?></td>
        <td><?php echo number_format($this->order_items[$i]->price, 2), " ", $this->order_items[$i]->currency;?></td>

    </tr>
	<?php endfor;?>
    <td colspan = "5">&nbsp;</td>
    <tfoot>
    <tr>
        <td colspan = "4" align = "right"><span class = "order_total_label"><?php echo JText::_('FACTORY_ORDER_TOTAL');?></span></td>
        <td><span class = "order_total_value"><?php echo $this->order->order_total, " ", $this->order->order_currency;?></span></td>
    </tr>
    </tfoot>
</table>
<?php if (!$this->isWithdraw): ?>
<!--	An order can be resumed only if its status is pending and only by its user receiver/owner -->
<?php if ($this->order->status == 'P' && $this->user->id == $this->order->userid) : ?>
    <br />
    <br />
    <br />

    <table width = "100%" class = "payment_gateways" cellpadding = "0" cellspacing = "0" border = "0">
        <thead>
        <tr>
            <th>
		    <?php echo JText::_('FACTORY_CHOOSE_A_PAYMENT_GATEWAY');?>
                <span style = "float: right">
    <span style = "color: #AAAAAA;font-size: 11px;">
        <span style = "color:#999999;font-weight:bold;">
	        <?php echo JText::_('FACTORY_NOTE'); ?>:</span>
	    <?php echo JText::_('FACTORY_YOU_CAN_RESUME_THE_PAYMENT_FOR_THIS_ORDER');?>
    </span>
        </span>
            </th>
        </tr>
        </thead>
    </table>
    <table width = "100%" class = "payment_gateways" cellpadding = "0" cellspacing = "0" border = "0">
        <tbody>
        <tr>
		<?php if (count($this->payment_gateways)): ?>
		<?php for ($i = 0; $i < count($this->payment_gateways); $i++): ?>
			<?php if ($i % 3 == 0): ?>
		        </tr><tr>
			        <?php endif; ?>
                <td>
                    <div class = "gateway_name"><?php echo $this->payment_gateways[$i]->fullname?></div>
                    <div class = "gateway_form"><?php echo $this->payment_forms[$i]?></div>
                </td>
			<?php endfor; ?>

		<?php else: ?>
            <td><?php echo JText::_('FACTORY_NO_PAYMENTS_GATEWAYS_DEFINED'); ?></td>
		<?php endif; ?>
        </tr>
        </tbody>

    </table>
	<?php endif; ?>
<?php else: ?>
<div style = "margin-top: 25px;font-size: 15px;"><?php echo '<span style="font-weight:bold;">' . JText::_('FACTORY_NOTE') . ':</span> ' . JText::_('FACTORY_INFO_WITHDRAW_ORDER_DETAILS'); ?></div>
<?php endif; ?>

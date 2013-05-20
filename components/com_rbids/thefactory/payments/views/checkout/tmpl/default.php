<?php defined('_JEXEC') or die('Restricted access'); ?>
<h1><?php echo JText::_('FACTORY_CHECKOUT_YOUR_ORDER'), $this->order->id;?> </h1>
<h3><?php echo JText::_('FACTORY_ORDER_DETAILS');?></h3>

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
<br />
<br />
<br />

<table width = "100%" class = "payment_gateways" cellpadding = "0" cellspacing = "0" border = "0">
    <thead>
    <tr>
        <th><?php echo JText::_('FACTORY_CHOOSE_A_PAYMENT_GATEWAY');?></th>
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

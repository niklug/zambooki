<?php

	// Access the file from Joomla environment
	defined('_JEXEC') or die('Restricted access');
?>
<h1>
	<?php echo JText::_('FACTORY_ORDER_DETAILS');?>
</h1>
<h3><?php echo JText::_('FACTORY_ORDER_NUMBER') . ': <span style="font-size:18px;font-weight:bold;">' . $this->order->id . '</span>';?></h3>
<h3><?php echo JText::_('FACTORY_ORDER_DATE'), ': ', JHtml::_('date', $this->order->orderdate, $this->cfg->date_format . ' ' . $this->cfg->date_time_format);?></h3>
<h2><?php echo JText::_('FACTORY_ORDER_STATUS'), ': ';?>
	<?php  if ($this->order->status == 'P') echo JText::_("FACTORY_PENDING");?>
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

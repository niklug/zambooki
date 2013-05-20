<?php defined('_JEXEC') or die('Restricted access'); ?>
<table width="100%">
<tr>
    <td width="200">
        <?php $this->display('menu');?>
    </td>
    <td width="*%" valign="top">
        <form action="index.php" method="get" name="adminForm">    
            <table class="adminlist" cellspacing="1">
            <thead>
                <tr>
                    <th width="60" align="center"><?php echo JText::_("COM_RBIDS_ORDER_NR"),'#'; ?></th>
                    <th width="200" align="center"><?php echo JText::_("COM_RBIDS_ORDER_DATE"); ?></th>
                    <th class="title" width="250"><?php echo JText::_("COM_RBIDS_USERNAME");?></th>
                    <th class="title" width="150" nowrap="nowrap"><?php echo JText::_("COM_RBIDS_AMOUNT");?></th>
                </tr>
            </thead>
            <tbody>
            <?php         
            $odd=0;
            foreach($this->payments as $payment): ?>
            <tr>
                <td><?php echo $payment->orderid;?></td>
                <td><?php echo $payment->orderdate;?></td>
                <td><?php echo $payment->username;?></td>
                <td><?php echo number_format($payment->price,2)," ",$payment->currency;?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="15">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            </table>
        <input type="hidden" name="option" value="<?php echo APP_EXTENSION;?>" />
        <input type="hidden" name="task" value="pricing.payments" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="item" value="comission" />
        
        </form>    
    </td>
</tr>
</table>

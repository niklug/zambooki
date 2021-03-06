<?php defined('_JEXEC') or die('Restricted access'); ?>
<table width="100%">
<tr>
    <td width="200">
        <?php $this->display('menu');?>
    </td>
    <td width="*%" valign="top">
        <form name="adminForm" action="index.php" method="get">
        <input type="hidden" name="option" value="<?php echo APP_EXTENSION;?>" />
        <input type="hidden" name="task" value="pricing.balance" />
        <input type="hidden" name="item" value="comission" />
        <input type="hidden" name="boxchecked" value="" />
        <?php echo JText::_("COM_RBIDS_FILTER_BALANCE"),": ",$this->filterbox?>
        <table class="adminlist" >
        <thead>
        <tr>
        	<th width="5"><?php echo JText::_("COM_RBIDS_USERID");?></th>
            <th width="5"><?php echo JText::_("COM_RBIDS_USER_NAME");?></th>
            <th width="5"><?php echo JText::_("COM_RBIDS_LAST_PAYMENT_DATE");?></th>
            <th width="80"><?php echo JText::_("COM_RBIDS_BALANCE");?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $odd=0;
        foreach ($this->userbalances as $userbalance) {?>
            <tr class="row<?php echo ($odd=1-$odd);?>">
            	<td align="center">
                    <a href="index.php?option=<?php echo APP_EXTENSION;?>&task=detailUser&cid[]=<?php echo $userbalance->userid?>">
                        <?php echo $userbalance->userid;?>
                    </a>
                </td>
            	<td>
                    <a href="index.php?option=<?php echo APP_EXTENSION;?>&task=detailUser&cid[]=<?php echo $userbalance->userid?>">
                        <?php echo $userbalance->username;?>
                    </a>
                </td>
                <td><?php echo $userbalance->lastpayment;?></td>
                <td><?php echo number_format($userbalance->balance,2);?>&nbsp;<?php echo $userbalance->currency;?></td>
            </tr>
        <?php } ?>
        </tbody>
            <tfoot>
                <tr>
                    <td colspan="15">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
        
        </table>
        </form>
    </td>
</tr>
</table>

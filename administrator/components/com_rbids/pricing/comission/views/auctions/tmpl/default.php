<?php defined('_JEXEC') or die('Restricted access'); ?>
<table width="100%">
<tr>
    <td width="200">
        <?php $this->display('menu');?>
    </td>
    <td width="*%" valign="top">
        <form name="adminForm" action="index.php" method="get">
        <input type="hidden" name="option" value="<?php echo APP_EXTENSION;?>" />
        <input type="hidden" name="task" value="pricing.auctions" />
        <input type="hidden" name="item" value="comission" />
        <input type="hidden" name="boxchecked" value="" />
        <table class="adminlist" >
        <thead>
        <tr>
        	<th width="50"><?php echo JText::_("COM_RBIDS_AUCTIONID");?></th>
            <th width="*%"><?php echo JText::_("COM_RBIDS_AUCTION_TITLE");?></th>
            <th width="80"><?php echo JText::_("COM_RBIDS_WINNING_BID");?></th>
            <th width="150"><?php echo JText::_("COM_RBIDS_DATE");?></th>
            <th width="80"><?php echo JText::_("COM_RBIDS_COMMISSION");?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $odd=0;
        foreach ($this->auctions as $auctions) {?>
            <tr class="row<?php echo ($odd=1-$odd);?>">
                <td><?php echo $auctions->auction_id;?></td>
                <td>
                    <a href="index.php?option=com_rbids&task=edit&cid[]=<?php echo $auctions->auction_id;?>">
                    <?php echo $auctions->title;?>
                    </a>
                </td>
                <td><?php echo number_format($auctions->bid_price,2)," ",$auctions->currency;?></td>
                <td><?php echo $auctions->comission_date;?></td>
                <td><?php echo $auctions->amount," ",$auctions->currency;?></td>
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

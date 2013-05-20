<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $task=JRequest::getVar('task'); ?>
<style>
    td.current-comission{
        background: #fff;
        border: 1px #CCCCCC solid;
    }
</style>
<fieldset>
    <table width="200">
    <tr>
        <td align="center" height="100" valign="top" <?php echo ($task=='pricing.config')?"class='current-comission'":"";?>>
        	<a href="index.php?option=<?php echo APP_EXTENSION;?>&task=pricing.config&item=comission">
    	    	<img src="<?php echo JURI::root();?>/administrator/components/<?php echo APP_EXTENSION;?>/pricing/comission/images/plugin_config.png" height="40" border="0" style="float: none;"/><br />
        	   <strong><?php echo JText::_('COM_RBIDS_CONFIG');?></strong>
            </a>     
        </td>
    </tr>
    <tr>
        <td align="center" height="100" valign="top" <?php echo ($task=='pricing.balance')?"class='current-comission'":"";?>>
        	<a href="index.php?option=<?php echo APP_EXTENSION;?>&task=pricing.balance&item=comission">
    	    	<img src="<?php echo JURI::root();?>/administrator/components/<?php echo APP_EXTENSION;?>/pricing/comission/images/stats.png" height="40" border="0" style="float: none;"/><br />
        	   <strong><?php echo JText::_('COM_RBIDS_BIDDERS_BALANCE');?></strong>
            </a>     
        </td>
    </tr>
    <tr>
        <td align="center" height="100" valign="top" <?php echo ($task=='pricing.auctions')?"class='current-comission'":"";?>>
        	<a href="index.php?option=<?php echo APP_EXTENSION;?>&task=pricing.auctions&item=comission">
    	    	<img src="<?php echo JURI::root();?>/administrator/components/<?php echo APP_EXTENSION;?>/pricing/comission/images/auctions.png" height="40" border="0" style="float: none;"/><br />
        	   <strong><?php echo JText::_('COM_RBIDS_AUCTION_COMMISSIONS');?></strong>
            </a>     
        </td>
    </tr>
    <tr>
        <td align="center" height="100" valign="top" <?php echo ($task=='pricing.payments')?"class='current-comission'":"";?>>
        	<a href="index.php?option=<?php echo APP_EXTENSION;?>&task=pricing.payments&item=comission">
    	    	<img src="<?php echo JURI::root();?>/administrator/components/<?php echo APP_EXTENSION;?>/pricing/comission/images/payments.png" height="40" border="0" style="float: none;"/><br />
        	   <strong><?php echo JText::_('COM_RBIDS_PAYMENTS');?></strong>
            </a>     
        </td>
    </tr>
    <tr>
        <td align="center" height="100" valign="top" <?php echo ($task=='pricing.notices')?"class='current-comission'":"";?>>
        	<a href="index.php?option=<?php echo APP_EXTENSION;?>&task=pricing.notices&item=comission">
    	    	<img src="<?php echo JURI::root();?>/administrator/components/<?php echo APP_EXTENSION;?>/pricing/comission/images/plugin_email.png" height="40" border="0" style="float: none;"/><br />
        	   <strong><?php echo JText::_('COM_RBIDS_SEND_PAYMENT_NOTICES');?></strong>
            </a>     
        </td>
    </tr>
    </table>
</fieldset>

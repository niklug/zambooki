<?php
	defined('_JEXEC') or die('Restricted access');
	JHtml::_('behavior.tooltip');
?>
<form name = "adminForm" action = "index.php" method = "post">
    <input type = "hidden" name = "task" value = "">
    <input type = "hidden" name = "option" value = "<?php echo APP_EXTENSION;?>">
    <input type = "hidden" name = "item" value = "<?php echo $this->itemname;?>">
    <input type = "hidden" name = "currency" value = "<?php echo $this->currency; ?>">

    <fieldset>
        <legend><?php echo JText::_("COM_RBIDS_GENERAL_SETTINGS"); ?></legend>
        <table width = "100%" class = "paramlist admintable">
            <tbody>
            <tr>
                <td width = "220" class = "paramlist key"><?php echo JText::_("COM_RBIDS_CURRENCY_USED"); ?>:</td>
                <td>
                     <span class = "hasTip" title = "<?php echo JText::_('COM_RBIDS_CURRENCY_USED_TIP'); ?>">
                        <span style = "float: left;
                                            display: inline-block;
                                            padding-top: 6px;
                                            margin-right: 5px;">
	                        <?php echo $this->currency; ?>
                        </span>
                        <img alt = "Tooltip" src = "<?php echo JURI::root(); ?>components/com_rbids/images/tooltip.png" />
                    </span>
                </td>
            </tr>
            <tr>
                <td width = "220" class = "paramlist key"><?php echo JText::_("COM_RBIDS_DEFAULT_LISTING_PRICE"); ?>:</td>
                <td><input value = "<?php echo $this->default_price;?>" class = "inputbox" name = "default_price" size = "5"></td>
            </tr>
            </tbody>
        </table>
    </fieldset>
    <fieldset>
        <legend><?php echo JText::_("COM_RBIDS_SPECIAL_PRICES"); ?></legend>
        <table width = "100%" class = "paramlist admintable">
            <tbody>
            <tr>
                <td width = "220" class = "paramlist key"><?php echo JText::_("COM_RBIDS_PRICE_FOR_POWERSELLERS"); ?>:</td>
                <td><input value = "<?php echo $this->price_powerseller;?>" class = "inputbox" name = "price_powerseller" size = "5"></td>
            </tr>
            <tr>
                <td width = "220" class = "paramlist key"><?php echo JText::_("COM_RBIDS_PRICE_FOR_VERIFIED_USERS"); ?>:</td>
                <td><input value = "<?php echo $this->price_verified;?>" class = "inputbox" name = "price_verified" size = "5"></td>
            </tr>
            </tbody>
        </table>
    </fieldset>
</form>

<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
	$doc =& JFactory::getDocument();
	$doc->addStyleDeclaration("
        table.admintable td.paramlist_key 
        {
            width:170px !important;
        }
    ");
?>
<div id = "cpanel">
    <div style = "width: 435px;float:left;">
        <div class = 'width-100 fltlft'>
            <fieldset class = "adminform">
                <legend><?php echo JText::_('COM_RBIDS_REVERSE_CONTROL_PANEL'); ?></legend>

		    <?php
// 1
		    $link = 'index.php?option=com_rbids&amp;task=config.display';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/settings.png', JText::_("COM_RBIDS_GENERAL_SETTINGS"));
// 2
		    $link = 'index.php?option=com_rbids&amp;task=category.categories';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/categories.png', JText::_("COM_RBIDS_MANAGE_CATEGORIES"));
// 3
		    $link = 'index.php?option=com_rbids&amp;task=fields.listfields';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/custom_fields.png', JText::_("COM_RBIDS_CUSTOM_FIELDS"));

		    echo "<div style='width: 100%;'>";
// 4
		    echo "<div style='float: left;'>";
		    $link = 'index.php?option=com_rbids&amp;task=integration';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/cb_integration.png', JText::_("COM_RBIDS_PROFILE_INTEGRATION"));
		    echo "</div>";
// 5
		    echo "<div style='float: right;'>";
		    $link = 'index.php?option=com_rbids&amp;task=themes.listthemes';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/themes.png', JText::_("COM_RBIDS_THEMES"));
		    echo "</div>";

		    echo "</div>";

		    echo "<div style='clear: both;'></div>";
// 6
		    $link = 'index.php?option=com_rbids&amp;task=currencies.listing';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/currency_manager.png', JText::_("COM_RBIDS_CURRENCY_MANAGER"));
// 7
		    $link = 'index.php?option=com_rbids&amp;task=pricing.listing';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/paymentitems.png', JText::_("COM_RBIDS_PAYMENT_ITEMS"));
// 8
		    $link = 'index.php?option=com_rbids&amp;task=gateways.listing';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/paymentconfig.png', JText::_("COM_RBIDS_PAYMENT_GATEWAYS"));

// 9
		    $link = 'index.php?option=com_rbids&amp;task=mailman.mails';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/mailsettings.png', JText::_("COM_RBIDS_MAIL_SETTINGS"));
// 10
		    $link = 'index.php?option=com_rbids&amp;task=countries.listing';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/country_manager.png', JText::_("COM_RBIDS_COUNTRIES_MANAGER"));
// 11
		    $link = 'index.php?option=com_rbids&amp;task=importexport.importexport';
		    JTheFactoryAdminHelper::quickiconButton($link, 'admin/update.png', JText::_("COM_RBIDS_IMPORT_EXPORT"));

		    ?>
            </fieldset>
        </div>
    </div>
    <div style = "float:left;width: 500px;">
        <div class = 'width-100 fltlft'>
            <fieldset class = "adminform">
                <legend><?php echo JText::_('COM_RBIDS_VERSION_INFORMATION'); ?></legend>
                <table class = "paramlist admintable">
                    <tr>
                        <td class = "paramlist_key" width = "190"><?php echo JText::_("COM_RBIDS_YOUR_INSTALLED_VERSION");?></td>
                        <td><?php echo COMPONENT_VERSION; ?></td>
                    </tr>
                    <tr>
                        <td class = "paramlist_key" width = "190"><?php echo JText::_("COM_RBIDS_ACTIVE_PROFILE_INTEGRATION");?></td>
                        <td><?php
				switch ($this->cfg->profile_mode) {
					case 'component':
						echo JText::_('COM_RBIDS_COMPONENT_PROFILE');
						break;
					case 'cb':
						echo JText::_('COM_RBIDS_COMMUNITY_BUILDER');
						break;
					case 'love':
						echo JText::_('COM_RBIDS_LOVE_FACTORY');
						break;
				}
				?></td>
                    </tr>
                    <tr>
                        <td class = "paramlist_key" width = "190"><?php echo JText::_("COM_RBIDS_CURRENT_ACTIVE_THEME");?></td>
                        <td><?php echo $this->cfg->theme; ?></td>
                    </tr>
                    <tr>
                        <td class = "paramlist_key" width = "190"><?php echo JText::_("COM_RBIDS_CURRENT_ENABLED_GATEWAYS");?></td>
                        <td>
                            <ul>
				    <?php
				    foreach ($this->gateways as $gateway) {
					    echo "<li><strong>", $gateway->paysystem, "</strong></li>";
				    }
				    ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class = "paramlist_key" width = "190"><?php echo JText::_("COM_RBIDS_CURRENT_ENABLED_PAYMENT_ITEMS");?></td>
                        <td>
                            <ul>
				    <?php
				    foreach ($this->items as $item) {
					    echo "<li><strong>", $item->itemname, "</strong></li>";
				    }
				    ?>
                            </ul>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div class = 'width-100 fltlft'>
            <fieldset class = "adminform">
                <legend><?php echo JText::_('COM_RBIDS_CRON_INFORMATION'); ?></legend>
                <table class = "paramlist admintable">
                    <tr>
                        <td class = "paramlist_key" width = "190"><?php echo JText::_("COM_RBIDS_LATEST_CRON_TASK_RUN");?></td>
                        <td><?php echo intval($this->latest_cron_time) ? JHtml::date($this->latest_cron_time, $this->cfg->date_format . ' ' . $this->cfg->date_time_format) : $this->latest_cron_time; ?></td>
                    </tr>
                    <tr>
                        <td class = "paramlist_key" width = "190"><?php echo JText::_("COM_RBIDS_READ_MORE_ABOUT_CRON_JOB_SETUP");?></td>
                        <td><a href = "index.php?option=com_rbids&task=cronjob_info"><?php echo JText::_("COM_RBIDS_CLICK_HERE");?></a></td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </div>
</div>
<div style = "height:100px">&nbsp;</div>
<div style = "clear:both;"></div>

<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action = "index.php?option=com_rbids" method = "post"  name = "adminForm" id="adminForm">
	<input type = "hidden" name = "task" value = "">

	<div id = "cpanel">
		<div class = 'width-100 fltlft'>
			<fieldset class = "adminform">
				<legend><?php echo JText::_('COM_RBIDS_IMPORTEXPORT_AUCTIONS'); ?></legend>

				<?php

				$link = 'index.php?option=com_rbids&amp;task=importexport.showadmimportform';
				JTheFactoryAdminHelper::quickiconButton($link, 'admin/importauctions.png', JText::_("COM_RBIDS_IMPORT_CSV"));

				$link = 'index.php?option=com_rbids&amp;task=importexport.exportToXls';
				JTheFactoryAdminHelper::quickiconButton($link, 'admin/xlsexport.png', JText::_("COM_RBIDS_EXPORT_XLS"));

				?>
			</fieldset>
		</div>
	</div>
	<div style = "height:100px">&nbsp;</div>
	<div style = "clear:both;"></div>
</form>

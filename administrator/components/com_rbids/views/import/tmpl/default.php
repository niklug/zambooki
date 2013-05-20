<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
	JFactory::getDocument()->addScriptDeclaration(
		"
    		function validateForm(){
    			var csv = document.getElementById('csv');
    
    			if(!csv.value){
    				alert('" . JText::_("COM_RBIDS_NO_CSV_FILE") . "');
    				return false;
    			}
    		}
        "
	);
?>
<form action = "index.php" method = "post" name = "adminForm" onsubmit = "return validateForm();" enctype = "multipart/form-data">
	<input type = "hidden" name = "option" value = "com_rbids">
	<input type = "hidden" name = "task" value = "importexport.importcsv">

	<table width = "100%" class = "adminlist">
		<tr>
			<td width = "100">
				<?php echo JText::_("COM_RBIDS_CSV_FILE"), " ", JHTML::_('tooltip', JText::_("COM_RBIDS_CSV_HELP")); ?>
			</td>
			<td>
				<input type = "file" name = "csv" id = "csv" size = "50" />
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_("COM_RBIDS_IMAGES_ARCHIVE"), " ", JHTML::_('tooltip', JText::_("COM_RBIDS_IMAGES_HELP")); ?>
			</td>
			<td>
				<input type = "file" name = "arch" id = "arch" size = "50" />
			</td>
		</tr>

	</table>
</form>
<div align = "left">
	<?php if (count($this->errors)) : ?>
	<div>
		<strong><?php echo JText::_('COM_RBIDS_THERE_HAVE_BEEN_ERRORS'); ?></strong><br />
		<ul style = "color: red;">

			<?php foreach ($this->errors as $err): ?>
				<li><?php echo $err;?></li>
				<?php endforeach; ?>

		</ul>
	</div>
	<?php endif;?>
</div>


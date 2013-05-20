<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
        $cfg=&CustomFieldsFactory::getConfig();
    ?>
<form action="index.php" method="post" name="adminForm">

	<h2><?php echo JText::_("COM_RBIDS_SELECT_CUSTOM_FIELDS_TO_EXPORT");?></h2>
    <table class="adminlist">
    	<tr>
    		<th width="25"><?php echo JText::_("COM_RBIDS_CHECK");?></th>
    		<th><?php echo JText::_("COM_RBIDS_FIELD_NAME");?></th>
    		<th><?php echo JText::_("COM_RBIDS_TYPE");?></th>
    		<th><?php echo JText::_("COM_RBIDS_SECTION");?></th>
    	</tr>
    <?php foreach ($this->fields as $f => $field ){ ?>
    	<tr>
    		<td>
    			<input type="checkbox" name="cid[]" value="<?php echo $field->db_name;?>" /> 
    		</td>
    		<td>
    			<?php echo $field->name;?> <?php echo $field->db_name;?>
    		</td>
    		<td>
    			<?php echo $field->ftype;?>
    		</td>
    		<td>	
    			<?php echo $cfg['pages'][$field->page];?>
    		</td>
    	</tr>
    <?php } ?>	
    <tr>
    	<td colspan="4" align="center">
    	<a href="#" onclick="document.adminForm.submit();">
    		<img src="<?php echo JURI::root();?>/components/com_rbids/images/admin/xlsexport.png" style="vertical-align:middle;" />
            <br />
            <?php echo JText::_("COM_RBIDS_EXPORT_NOW");?>
		</a>
    	</td>
    </tr>
    </table>
    
<input type="hidden" name="option" value="com_rbids" />
<input type="hidden" name="task" value="importexport.do_ExportToXls" />
</form>
